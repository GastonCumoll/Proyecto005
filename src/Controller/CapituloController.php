<?php

namespace App\Controller;

use App\Entity\Capitulo;
use App\Form\CapituloType;
use App\Repository\CapituloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/capitulo")
 */
class CapituloController extends AbstractController
{
    /**
     * @Route("/{id}/capitulo/arbol", name="capitulo_show_arbol", methods={"GET"})
     */
    public function capituloArbol(CapituloRepository $capituloRepository,$id): Response
    {
        $cap=$capituloRepository->find($id);
        $temas=$cap->getTemas();

        $nombreTit=$cap->getTitulo();
        
        return $this->render('capitulo/capituloShowArbol.html.twig', [
            'capi'=>$cap,
            'titu' => $nombreTit,
            'temas' =>$temas
        ]);
    }

    /**
     * @Route("/novedades", name="novedades", methods={"GET"})
     */
    public function novedades(){
        return $this->render('capitulo/novedades.html.twig',[]);
    }

    /**
     * @Route("/", name="capitulo_index", methods={"GET"})
     */
    public function index(CapituloRepository $capituloRepository): Response
    {
        return $this->render('capitulo/index.html.twig', [
            'capitulos' => $capituloRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="capitulo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $capitulo = new Capitulo();
        $form = $this->createForm(CapituloType::class, $capitulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($capitulo);
            $entityManager->flush();

            return $this->redirectToRoute('capitulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('capitulo/new.html.twig', [
            'capitulo' => $capitulo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="capitulo_show", methods={"GET"})
     */
    public function show(Capitulo $capitulo): Response
    {
        return $this->render('capitulo/show.html.twig', [
            'capitulo' => $capitulo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="capitulo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Capitulo $capitulo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CapituloType::class, $capitulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('capitulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('capitulo/edit.html.twig', [
            'capitulo' => $capitulo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="capitulo_delete", methods={"POST"})
     */
    public function delete(Request $request, Capitulo $capitulo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$capitulo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($capitulo);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('capitulo_index', [], Response::HTTP_SEE_OTHER);
    }
}
