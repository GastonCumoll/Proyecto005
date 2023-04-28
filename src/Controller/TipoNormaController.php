<?php

namespace App\Controller;

use App\Entity\TipoNorma;
use App\Form\TipoNormaType;
use App\Form\TipoNormaRolType;
use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use App\Service\ReparticionService;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoNormaRolRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/norma")
 */
class TipoNormaController extends AbstractController
{
    /**
     * @Route("/", name="tipo_norma_index", methods={"GET"})
     */
    public function index(SeguridadService $seguridad,TipoNormaRepository $tipoNormaRepository,Request $request, PaginatorInterface $paginator): Response
    {

        // $ejemplo=$tipoNormaRepository->findOneById(9);
        // foreach ($ejemplo->getNormas() as $unaN) {
        //     dump($unaN);
        // }
        // dd($ejemplo->getNormas()->toArray());
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
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

        // Encuentre todos los datos en la tabla de Citas, filtre su consulta como necesite
        $todosTipos = $tipoNormaRepository->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $tiposNormas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todosTipos,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        
        return $this->render('tipo_norma/index.html.twig', [
            'tipo_normas' => $tiposNormas,
            'rol' => $rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/nueva", name="norma_nueva", methods={"GET", "POST"})
     */
    //este metodo se utiliza cuando se va a cargar una norma, primero pregunta que tipo de norma es, y luego redirige al metodo de creacion de norma, con un id, que es del tipo de norma 
    public function nuevoTipoNorma(ReparticionService $reparticionService,TipoNormaRolRepository $tipoNormaRolRepository,AreaRepository $areaRepository,TipoNormaRepository $tipoNormaRepository,Request $request, SeguridadService $seguridad): Response
    {   
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];//array roles es un array que contiene todos los roles del usuario.(lo hicimos porq hay usuarios que tienen mas de un rol)
        if($seguridad->checkSessionActive($idSession)){

            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }

            $rol=$roles[0]['id'];

        }else {
            $rol="";
        }

        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);

        $reparticionUsuario = $areaRepository->find($idReparticion);

        $idTipoNorma=[];
        $tiposDeNormas=[];
        //solamente el rol operador puede cargar normas
        if(in_array('DIG_OPERADOR',$arrayRoles)){

            $tiposDeNormasRol=$tipoNormaRolRepository->findByNombreRol('DIG_OPERADOR');

            foreach ($tiposDeNormasRol as $unTipoNormaRol) {
                $idTipoNorma[]=$unTipoNormaRol->getTipoNorma();
            }

            //idTipoNorma->array de los ids de tipos de norma del rol
            foreach ($idTipoNorma as $id) {
                $tiposDeNormas[]=$tipoNormaRepository->findOneById($id);
            }
        }
        
        return $this->render('tipo_norma/newTipo.html.twig', [
            'tipo_normas' => $tiposDeNormas,
            'normasUsuario' => $normasUsuario,
        ]);
    }


    /**
     * @Route("/addRol/{id}", name="roles_tipo_norma", methods={"GET", "POST"})
     */
    //este metodo por el momento no se usa
    public function addRol(Request $request, EntityManagerInterface $entityManager,TipoNorma $tipoNormaT,TipoNormaRepository $tipoNormaRepository,$id): Response
    {
        $tipo=$tipoNormaRepository->findById($id);
        $tipoNormaT->setNombre($tipo[0]->getNombre());
        $form = $this->createForm(TipoNormaRolType::class, $tipoNormaT);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma/edit.html.twig', [
            'tipo_norma' => $tipoNormaT,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="tipo_norma_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        $tipoNorma = new TipoNorma();
        $form = $this->createForm(TipoNormaType::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($tipoNorma);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma/new.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_show", methods={"GET"})
     */
    public function show(TipoNorma $tipoNorma): Response
    {
        return $this->render('tipo_norma/show.html.twig', [
            'tipo_norma' => $tipoNorma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_norma_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(TipoNormaType::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma/edit.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        //para eliminar un tipo de norma, primero se pregunta si es que tiene alguna norma atada, rol o reparticion. Si es que las tiene, manda un mensaje flash a la vista
        //avisando que no pudo eliminar ese tipo de norma por las razones antes descriptas.
        //la funcion if(!empty($tipoNorma->getNormas()->toArray())...) se hace porque el ->getNormas() trae una collection, que no se sabe si es vacia o no
        //entonces se la convierte a array y se pregunta si tiene algo, si no tiene, es posible eliminar. Si tiene, no puede
        if(!empty($tipoNorma->getNormas()->toArray()) || !empty($tipoNorma->getTipoNormaRoles()->toArray) || !empty($tipoNorma->getTipoNormaReparticions()->toArray())){
            //dd(empty($tipoNorma->getNormas()));
            $this->addFlash(
                'errorDeleteTipoNorma',
                "No se pudo eliminar este tipo de norma debido a que tiene normas, roles y/o reparticiones asociados."
            );
            return $this->redirectToRoute('tipo_norma_index',[],Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$tipoNorma->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNorma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
