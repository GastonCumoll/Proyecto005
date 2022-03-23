<?php

namespace App\Controller;

use App\Entity\Tema;
use App\Form\TemaType;
use App\Repository\TemaRepository;
use App\Repository\NormaRepository;
use App\Repository\TituloRepository;
use App\Repository\CapituloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tema")
 */
class TemaController extends AbstractController
{
    
    /**
     * @Route("/{id}/tema/arbol", name="tema_show_arbol", methods={"GET"})
     */
    public function temaArbol(NormaRepository $normaRepository,TemaRepository $temaRepository, $id,TituloRepository $tituloRepository, CapituloRepository $capituloRepository): Response
    {
        $tema=$temaRepository->find($id);
        $normas=$tema->getNormas();

        
        $nombreCap=$tema->getCapitulo();
        $nombreTit=$tema->getCapitulo()->getTitulo();

        
        
        
        return $this->render('tema/temaShowArbol.html.twig', [
            'normasTema' => $normas,
            'idTema' =>$id,
            'tema' => $tema,
            
            'capi' => $nombreCap,
            'titu' => $nombreTit
        ]);
    }

    /**
     * @Route("/{id}/deCapitulo", name="tema_de_capitulo", methods={"GET"})
     */
    public function temaCapitulo(TemaRepository $temaRepository,$id): Response
    {
        $tema=$temaRepository->findByCapitulo($id);
        // dd($tema);
        
        return $this->render('tema/temaDeCapitulo.html.twig', [
            'temas'=>$tema
        ]);
    }

    /**
     * @Route("/", name="tema_index", methods={"GET"})
     */
    public function index(TemaRepository $temaRepository): Response
    {
        return $this->render('tema/index.html.twig', [
            'temas' => $temaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tema_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tema = new Tema();
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tema);
            $entityManager->flush();

            return $this->redirectToRoute('tema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tema/new.html.twig', [
            'tema' => $tema,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tema_show", methods={"GET"})
     */
    public function show(Tema $tema): Response
    {
        return $this->render('tema/show.html.twig', [
            'tema' => $tema,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tema_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tema $tema, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tema/edit.html.twig', [
            'tema' => $tema,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tema_delete", methods={"POST"})
     */
    public function delete(Request $request, Tema $tema, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tema->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tema);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tema_index', [], Response::HTTP_SEE_OTHER);
    }
}
