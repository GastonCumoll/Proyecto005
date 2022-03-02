<?php

namespace App\Controller;

use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Norma;
use App\Form\LeyType;
use App\Form\NormaType;
use App\Entity\Relacion;
use App\Entity\TipoNorma;
use App\Form\DecretoType;
use App\Form\CircularType;
use App\Form\RelacionType;
use App\Form\OrdenanzaType;
use App\Form\TipoNormaType;
use App\Form\ResolucionType;
use App\Repository\NormaRepository;
use App\Repository\RelacionRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/relacion")
 */
class RelacionController extends AbstractController
{
    /**
     * @Route("/", name="relacion_index", methods={"GET"})
     */
    public function index(RelacionRepository $relacionRepository): Response
    {
        return $this->render('relacion/index.html.twig', [
            'relacions' => $relacionRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/agregarRelacion", name="agregar_relacion", methods={"GET", "POST"})
     */
    public function agregarRelacion(Request $request, EntityManagerInterface $entityManager, NormaRepository $repository, $id): Response
    {
        $today=new DateTime();
        $relacion=new Relacion();


        $repository= $this->getDoctrine()->getRepository(Norma::class);
        $norma=$repository->find($id);
        $relacion->setFechaRelacion($today);
        
        $relacion->setNorma($norma);

        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relacion);
            $entityManager->flush();

            return $this->redirectToRoute('relacion_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('relacion/new.html.twig', [
            'relacion' => $relacion,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/relaForm", name="form_rela", methods={"GET", "POST"})
     */
    public function relacionForm(RelacionRepository $relacionRepository,Request $request, EntityManagerInterface $entityManager, NormaRepository $repository): Response
    {
        $today=new DateTime();
        $relacion=new Relacion();

        $relacion->setFechaRelacion($today);
        $session=$request->getSession();
        $id=$session->get('id');
        $repository = $this->getDoctrine()->getRepository(Norma::class);
        $norma = $repository->find($id);
        $relacion->setNorma($norma);
        $session->invalidate();
        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relacion);
            $entityManager->flush();

            return $this->redirectToRoute('relacion_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('relacion/new.html.twig', [
            'relacion' => $relacion,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/new", name="relacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $relacion = new Relacion();
        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relacion);
            $entityManager->flush();

            return $this->redirectToRoute('relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('relacion/new.html.twig', [
            'relacion' => $relacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="relacion_show", methods={"GET"})
     */
    public function show(Relacion $relacion): Response
    {
        return $this->render('relacion/show.html.twig', [
            'relacion' => $relacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="relacion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Relacion $relacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('relacion/edit.html.twig', [
            'relacion' => $relacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="relacion_delete", methods={"POST"})
     */
    public function delete(Request $request, Relacion $relacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($relacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('relacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
