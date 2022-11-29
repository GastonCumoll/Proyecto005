<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Form\EtiquetaType;
use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use App\Repository\NormaRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/etiqueta")
 */
class EtiquetaController extends AbstractController
{
    /**
     * @Route("/", name="etiqueta_index", methods={"GET"})
     */
    public function index(EtiquetaRepository $etiquetaRepository,Request $request, PaginatorInterface $paginator,SeguridadService $seguridad): Response
    {
        $sesion=$this->get('session');

        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($rol);
        }else {
            $rol="";
        }
        // Otra forma de acceder al entity manager(en vez de inyectar $etiquetaRepository)
        $em = $this->getDoctrine()->getManager();
        
        //Aca obtenemos el repository
        $etiquetaRepository = $em->getRepository(Etiqueta::class);
                
        //Busca todas las etiquetas
        $etiquetasQuery = $etiquetaRepository->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $etiquetas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $etiquetasQuery,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $etiquetas->setCustomParameters([
            'align' => 'center',
        ]);
        return $this->render('etiqueta/index.html.twig', [
            //'etiquetas' => $etiquetaRepository->findAll(),
            'etiquetas' => $etiquetas,
            'rol'=>$rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/{id}/busquedaId", name="busqueda_id_etiqueta", methods={"GET","POST"}, options={"expose"=true})
     */
    //metodo para buscar las normas que tienen una etiqueta determinada
    public function busquedaId(AreaRepository $areaRepository, NormaRepository $normaRepository,EntityManagerInterface $em,TipoNormaRepository $tipoRepository,EtiquetaRepository $etiquetaRepository,$id,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {

        $etiqueta=$etiquetaRepository->find($id);//array
        // dd($etiqueta);
        $fEti=[];
        $fEti[]=$etiqueta->getNombre();
        //$etiqueta->getNormas()->toArray() trae las normas de $etiqueta, que anteriormente fue buscada por su id, y lo convierte en array. No es conveniente en el paginator 
        //trabajar con arrays, por eso en $normaRepository->findNormasEtiqueta, devuelve una instancia de query, para trabajar con el knp paginator
        $normasEtiquetas=$etiqueta->getNormas()->toArray();
        $normas=$normaRepository->findNormasEtiqueta($normasEtiquetas);

        // Paginar los resultados de la consulta
        $norma = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $etiqueta->getNormas(),
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $norma->setCustomParameters([
            'align' => 'center',
        ]);
        $arrayRoles=[];
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($rol);
        }else {
            $rol="";
        }
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }else{
            $reparticionUsuario=null;
        }
        $normasUsuario = [];
        if($reparticionUsuario){
            //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
            foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
            }
        }
        
        return $this->render('norma/indexAdmin.html.twig', [
            'tipoNormas' => $tipoRepository->findAll(),
            'etiquetas' =>$etiquetaRepository->findAll(),
            'rol' => $rol,
            'roles'=>$arrayRoles,
            'normas' => $norma,
            'normasUsuario' => $normasUsuario,
            'fEtiquetas' =>$fEti,
        ]);
    }

    /**
     * @Route("/{palabra}/busquedaParam", name="busqueda_param_etiqueta", methods={"GET","POST"}, options={"expose"=true})
     */
    //metodo que busca, dependiendo de la variable "palabra",las etiquetas con ese string de nombre, o que lo contenga.
    public function busquedaParam(EtiquetaRepository $etiquetaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //dd($palabra);
        //$palabra es el string que quiero buscar
        //cambiamos el caracter "§" por "/", porque habia un problema con las rutas.
        $palabra=str_replace("§","/",$palabra);
        if($palabra==" "){
            $todasEtiquetas=[];
        }else{
            $todasEtiquetas=$etiquetaRepository->findUnaEtiqueta($palabra);//ORMQuery
        }
        //ahora todasEtiquetas es un query de las etiquetas que contienen en su nombre la variable $palabra, que es recibida por parametro;

        // Paginar los resultados de la consulta
        $etiquetas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todasEtiquetas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $arrayRoles=[];
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($rol);
        }else {
            $rol="";
        }
        return $this->render('etiqueta/index.html.twig', [
            'rol' => $rol,
            'etiquetas' => $etiquetas,
            'roles'=>$arrayRoles,
        ]);
        
    }

    /**
     * @Route("/new", name="etiqueta_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etiquetum = new Etiqueta();
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $normas=$form['normas']->getData();
            //dd($normas);
            $entityManager->persist($etiquetum);
            foreach ($normas as $unaNorma) {
                $etiquetum->addNorma($unaNorma);
                $unaNorma->addEtiqueta($etiquetum);
                $entityManager->persist($unaNorma);
            }
            $entityManager->persist($etiquetum);
            $entityManager->flush();


            return $this->redirectToRoute('etiqueta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etiqueta/new.html.twig', [
            'etiquetum' => $etiquetum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_show", methods={"GET"})
     */
    public function show(Etiqueta $etiquetum): Response
    {
        return $this->render('etiqueta/show.html.twig', [
            'etiquetum' => $etiquetum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etiqueta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Etiqueta $etiquetum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtiquetaType::class, $etiquetum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('etiqueta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etiqueta/edit.html.twig', [
            'etiquetum' => $etiquetum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="etiqueta_delete", methods={"POST"})
     */
    public function delete(Request $request, Etiqueta $etiquetum, EntityManagerInterface $entityManager): Response
    {
        if(count($etiquetum->getNormas()) > 0){
            $this->addFlash(
                'errorDeleteEtiqueta',
                "No se pudo eliminar debido a que ya existen normas con esta etiqueta."
            );
            return $this->redirectToRoute('etiqueta_index',[],Response::HTTP_SEE_OTHER);
        }else{
            if ($this->isCsrfTokenValid('delete'.$etiquetum->getId(), $request->request->get('_token'))) {
                $entityManager->remove($etiquetum);
                $entityManager->flush();
            }
            return $this->redirectToRoute('etiqueta_index', [], Response::HTTP_SEE_OTHER);
        }
        
    }
}
