<?php

namespace App\Controller;

use App\Repository\NormaRepository;
use App\Entity\TipoNormaReparticion;
use App\Form\TipoNormaReparticionType;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TipoNormaReparticionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/norma/reparticion")
 */
class TipoNormaReparticionController extends AbstractController
{
    /**
     * @Route("/index", name="tipo_norma_reparticion_index", methods={"GET"})
     */
    public function index(TipoNormaReparticionRepository $tipoNormaReparticionRepository): Response
    {
        return $this->render('tipo_norma_reparticion/index.html.twig', [
            'tipo_norma_reparticions' => $tipoNormaReparticionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/reparticionNorma/{id}", name="reparticion_norma", methods={"GET"})
     */
    public function reparticionNorma(TipoNormaReparticionRepository $tipoNormaReparticionRepository,TipoNormaRepository $tipoNormaRepository,$id): Response
    {
        $rol="DIG_ADMINISTRADOR";
        $tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $reparticionesDeNorma=[];
        //dd($tipoNorma[0]->getTipoNormaReparticions());
        foreach ($tipoNorma[0]->getTipoNormaReparticions() as $repa) {
            $reparticionesDeNorma[]=$repa->getReparticionId();
        }
        //dd($reparticionesDeNorma);
        return $this->render('tipo_norma_reparticion/index.html.twig', [
            'reparticionesDeNorma' => $reparticionesDeNorma,
            'tipoNorma' => $tipo,
            'rol'=>$rol,
        ]);
    }

    /**
     * @Route("/new/{id}", name="tipo_norma_reparticion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipoNormaReparticion = new TipoNormaReparticion();
        $tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $idTipo=$tipo->getId();
        $tipoNormaReparticion->setTipoNormaId($tipo);
        $form = $this->createForm(TipoNormaReparticionType::class, $tipoNormaReparticion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($tipoNormaReparticion->getTipoNormaId());
            //dd($form->get('tipoNormaId')->getData());
            //$tipoN=dd($form->get('archivo')->getData());
            $entityManager->persist($tipoNormaReparticion);
            $entityManager->flush();

            return $this->redirectToRoute('reparticion_norma', ['id'=>$idTipo], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_reparticion/new.html.twig', [
            'tipo_norma_reparticion' => $tipoNormaReparticion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_reparticion_show", methods={"GET"})
     */
    public function show(TipoNormaReparticion $tipoNormaReparticion): Response
    {
        return $this->render('tipo_norma_reparticion/show.html.twig', [
            'tipo_norma_reparticion' => $tipoNormaReparticion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_norma_reparticion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoNormaReparticion $tipoNormaReparticion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoNormaReparticionType::class, $tipoNormaReparticion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_norma_reparticion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_reparticion/edit.html.twig', [
            'tipo_norma_reparticion' => $tipoNormaReparticion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_reparticion_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoNormaReparticion $tipoNormaReparticion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoNormaReparticion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNormaReparticion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_norma_reparticion_index', [], Response::HTTP_SEE_OTHER);
    }
}
