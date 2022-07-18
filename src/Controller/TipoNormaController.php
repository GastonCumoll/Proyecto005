<?php

namespace App\Controller;

use App\Entity\TipoNorma;
use App\Form\TipoNormaType;
use App\Service\SeguridadService;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(TipoNormaRepository $tipoNormaRepository,Request $request, PaginatorInterface $paginator): Response
    {

                
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
        ]);
    }

    /**
     * @Route("/nueva", name="norma_nueva", methods={"GET", "POST"})
     */
    public function nuevoTipoNorma(TipoNormaRepository $tipoNormaRepository,Request $request, SeguridadService $seguridad): Response
    {
        
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
        if($rol=='DIG_OPERADOR'){
            $tiposDeNormas=$tipoNormaRepository->findByRol('DIG_OPERADOR');
        }
        else{
            $tiposDeNormas=$tipoNormaRepository->findAll();
        }
        return $this->render('tipo_norma/newTipo.html.twig', [
            'tipo_normas' => $tiposDeNormas,
        ]);
    }

    /**
     * @Route("/new", name="tipo_norma_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$tipoNorma->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNorma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
