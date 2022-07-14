<?php

namespace App\Controller;

use App\Entity\Auditoria;
use App\Form\AuditoriaType;
use App\Repository\AuditoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auditoria")
 */
class AuditoriaController extends AbstractController
{
    /**
     * @Route("/", name="auditoria_index", methods={"GET"})
     */
    public function index(AuditoriaRepository $auditoriaRepository): Response
    {
        return $this->render('auditoria/index.html.twig', [
            'auditorias' => $auditoriaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="auditoria_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auditorium = new Auditoria();
        $form = $this->createForm(AuditoriaType::class, $auditorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auditorium);
            $entityManager->flush();

            return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auditoria/new.html.twig', [
            'auditorium' => $auditorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auditoria_show", methods={"GET"})
     */
    public function show(Auditoria $auditorium): Response
    {
        return $this->render('auditoria/show.html.twig', [
            'auditorium' => $auditorium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auditoria_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Auditoria $auditorium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuditoriaType::class, $auditorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auditoria/edit.html.twig', [
            'auditorium' => $auditorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auditoria_delete", methods={"POST"})
     */
    public function delete(Request $request, Auditoria $auditorium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auditorium->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auditorium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
    }
}