<?php

namespace App\Controller;

use App\Entity\TipoRelacion;
use App\Form\TipoRelacionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoRelacionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/relacion")
 */
class TipoRelacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_relacion_index", methods={"GET"})
     */
    public function index(TipoRelacionRepository $tipoRelacionRepository,Request $request, PaginatorInterface $paginator): Response
    {
        // Encuentre todos los datos en la tabla de Citas, filtre su consulta como necesite
        $todosTipos = $tipoRelacionRepository->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $tiposRelacion = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todosTipos,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        
        return $this->render('tipo_relacion/index.html.twig', [
            'tipo_relacions' => $tiposRelacion,
        ]);
    }

    /**
     * @Route("/new", name="tipo_relacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoRelacionRepository $tipoRelacionRepository): Response
    {
        $tipoRelacion = new TipoRelacion();
        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $inverso =$form['inverso']->getData();
            if($inverso!=null){
                $inverso->setInverso($tipoRelacion);
                $entityManager->persist($tipoRelacion);
                $entityManager->persist($inverso);
            }

            $entityManager->persist($tipoRelacion);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_relacion/new.html.twig', [
            'tipo_relacion' => $tipoRelacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_relacion_show", methods={"GET"})
     */
    public function show(TipoRelacion $tipoRelacion): Response
    {
        return $this->render('tipo_relacion/show.html.twig', [
            'tipo_relacion' => $tipoRelacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_relacion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoRelacion $tipoRelacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_relacion/edit.html.twig', [
            'tipo_relacion' => $tipoRelacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_relacion_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoRelacion $tipoRelacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoRelacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoRelacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
