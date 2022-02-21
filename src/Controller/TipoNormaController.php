<?php

namespace App\Controller;

use App\Entity\TipoNorma;
use App\Form\TipoNormaType;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/norma")
 */
class TipoNormaController extends AbstractController
{
    /**
     * @Route("/", name="tipo_norma_index", methods={"GET"})
     */
    public function index(TipoNormaRepository $tipoNormaRepository): Response
    {
        return $this->render('tipo_norma/index.html.twig', [
            'tipo_normas' => $tipoNormaRepository->findAll(),
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
