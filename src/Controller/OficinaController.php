<?php

namespace App\Controller;

use App\Entity\Oficina;
use App\Form\OficinaType;
use App\Repository\OficinaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oficina")
 */
class OficinaController extends AbstractController
{
    /**
     * @Route("/", name="oficina_index", methods={"GET"})
     */
    public function index(OficinaRepository $oficinaRepository): Response
    {
        return $this->render('oficina/index.html.twig', [
            'oficinas' => $oficinaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="oficina_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oficina = new Oficina();
        $form = $this->createForm(OficinaType::class, $oficina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oficina);
            $entityManager->flush();

            return $this->redirectToRoute('oficina_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oficina/new.html.twig', [
            'oficina' => $oficina,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="oficina_show", methods={"GET"})
     */
    public function show(Oficina $oficina): Response
    {
        return $this->render('oficina/show.html.twig', [
            'oficina' => $oficina,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="oficina_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Oficina $oficina, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OficinaType::class, $oficina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('oficina_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oficina/edit.html.twig', [
            'oficina' => $oficina,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="oficina_delete", methods={"POST"})
     */
    public function delete(Request $request, Oficina $oficina, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oficina->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oficina);
            $entityManager->flush();
        }

        return $this->redirectToRoute('oficina_index', [], Response::HTTP_SEE_OTHER);
    }
}
