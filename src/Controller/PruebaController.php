<?php

namespace App\Controller;

use App\Entity\Prueba;
use App\Form\PruebaType;
use App\Repository\PruebaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prueba")
 */
class PruebaController extends AbstractController
{
    /**
     * @Route("/", name="prueba_index", methods={"GET"})
     */
    public function index(PruebaRepository $pruebaRepository): Response
    {
        return $this->render('prueba/index.html.twig', [
            'pruebas' => $pruebaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prueba_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prueba = new Prueba();
        $form = $this->createForm(PruebaType::class, $prueba);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prueba);
            $entityManager->flush();

            return $this->redirectToRoute('prueba_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prueba/new.html.twig', [
            'prueba' => $prueba,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prueba_show", methods={"GET"})
     */
    public function show(Prueba $prueba): Response
    {
        return $this->render('prueba/show.html.twig', [
            'prueba' => $prueba,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prueba_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Prueba $prueba, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PruebaType::class, $prueba);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('prueba_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prueba/edit.html.twig', [
            'prueba' => $prueba,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prueba_delete", methods={"POST"})
     */
    public function delete(Request $request, Prueba $prueba, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prueba->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prueba);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prueba_index', [], Response::HTTP_SEE_OTHER);
    }
}
