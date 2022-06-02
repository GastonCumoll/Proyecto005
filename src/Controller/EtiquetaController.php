<?php

namespace App\Controller;

use App\Entity\Etiqueta;
use App\Form\EtiquetaType;
use App\Service\SeguridadService;
use App\Repository\EtiquetaRepository;
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
    public function index(EtiquetaRepository $etiquetaRepository,Request $request, PaginatorInterface $paginator): Response
    {
        // Recuperar el administrador de entidades de Doctrine
        $em = $this->getDoctrine()->getManager();
        
        // Obtener algún repositorio de datos, en nuestro caso tenemos una entidad de Citas
        $etiqueta = $em->getRepository(Etiqueta::class);
                
        // Encuentre todos los datos en la tabla de Citas, filtre su consulta como necesite
        $allAppointmentsQuery = $etiqueta->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $appointments = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $allAppointmentsQuery,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('etiqueta/index.html.twig', [
            //'etiquetas' => $etiquetaRepository->findAll(),
            'etiquetas' => $appointments,
        ]);
    }

    /**
     * @Route("/{palabra}/busquedaParam", name="busqueda_param_etiqueta", methods={"GET","POST"}, options={"expose"=true})
     */
    public function busquedaParam(EtiquetaRepository $etiquetaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //dd($palabra);
        //$palabra es el string que quiero buscar
        $palabra=str_replace("§","/",$palabra);
        if($palabra==" "){
            $todasEtiquetas=[];
        }else{
            $todasEtiquetas=$etiquetaRepository->findUnaEtiqueta($palabra);//array
        }
        // 
        
        
        $todasEtiquetas=array_unique($todasEtiquetas);

        // Paginar los resultados de la consulta
        $etiquetas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todasEtiquetas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        return $this->render('etiqueta/index.html.twig', [
            'rol' => $rol,
            'etiquetas' => $etiquetas,
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
        if ($this->isCsrfTokenValid('delete'.$etiquetum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etiquetum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etiqueta_index', [], Response::HTTP_SEE_OTHER);
    }
}
