<?php

namespace App\Controller;

use App\Entity\TipoRelacion;
use App\Form\TipoRelacionType;
use App\Repository\TipoRelacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/relacion")
 */
class TipoRelacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_relacion_index", methods={"GET"})
     */
    public function index(TipoRelacionRepository $tipoRelacionRepository): Response
    {
        return $this->render('tipo_relacion/index.html.twig', [
            'tipo_relacions' => $tipoRelacionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_relacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoRelacion = new TipoRelacion();
        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
