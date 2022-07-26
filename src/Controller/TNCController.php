<?php

namespace App\Controller;

use App\Entity\TipoNorma;
use App\Form\TipoNorma1Type;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/t/n/c")
 */
class TNCController extends AbstractController
{
    /**
     * @Route("/", name="t_n_c_index", methods={"GET"})
     */
    public function index(TipoNormaRepository $tipoNormaRepository): Response
    {
        return $this->render('tnc/index.html.twig', [
            'tipo_normas' => $tipoNormaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="t_n_c_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoNorma = new TipoNorma();
        $form = $this->createForm(TipoNorma1Type::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoNorma);
            $entityManager->flush();

            return $this->redirectToRoute('t_n_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tnc/new.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="t_n_c_show", methods={"GET"})
     */
    public function show(TipoNorma $tipoNorma): Response
    {
        return $this->render('tnc/show.html.twig', [
            'tipo_norma' => $tipoNorma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="t_n_c_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoNorma1Type::class, $tipoNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('t_n_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tnc/edit.html.twig', [
            'tipo_norma' => $tipoNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="t_n_c_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoNorma $tipoNorma, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoNorma->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNorma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('t_n_c_index', [], Response::HTTP_SEE_OTHER);
    }
}
