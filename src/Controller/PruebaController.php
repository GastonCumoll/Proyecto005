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
    public function index(PruebaRepository $pruebaRepository,EntityManagerInterface $entityManager): Response
    {
        $normasPrueba=$pruebaRepository->findAll();
        
        foreach ($normasPrueba as $unaNorma) {
            $titulo=$unaNorma->getTitulo();
            //dd($titulo[0]);
            if($titulo[0]==" "){
                $primerEspacio=strpos($titulo," ");
                //dd($primerEspacio);
                if(!$primerEspacio){
                    $primerEspacio=1000;
                }
                $primeraD=strpos($titulo,"D");
                
                if(!$primeraD){
                    $primeraD=1000;
                }
                $primeraR=strpos($titulo,"R");
                if(!$primeraR){
                    $primeraR=1000;
                }
                $primeraO=strpos($titulo,"O");
                if(!$primeraO){
                    $primeraO=1000;
                }
                $primeraE=strpos($titulo,"E");
                if(!$primeraE){
                    $primeraE=1000;
                }
                $primeraL=strpos($titulo,"L");
                if(!$primeraL){
                    
                    $primeraL=1000;
                }
                $primeraC=strpos($titulo,"C");
                if(!$primeraC){
                    $primeraC=1000;
                }
                $primeraA=strpos($titulo,"A");
                if(!$primeraA){
                    $primeraA=1000;
                }
                $primeraS=strpos($titulo,"S");
                if(!$primeraS){
                    $primeraS=1000;
                }
                $primeraM=strpos($titulo,"M");
                if(!$primeraM){
                    $primeraM=1000;
                }
                $menor=min($primerEspacio,$primeraD,$primeraR,$primeraO,$primeraE,$primeraL,$primeraC,$primeraA,$primeraM,$primeraS);
                
                if($menor!=1000){
                    $tituloSinNumero=substr($titulo,$menor);
                    // dd($tituloSinNumero);
                    $unaNorma->setTitulo($tituloSinNumero);
                    //dd($unaNorma->getTitulo());
                }
            }
                $entityManager->persist($unaNorma);
        }   
        $entityManager->flush();
        // $norma=$pruebaRepository->findById(1605);
        // dd($norma);
        dd($normasPrueba[0]->getTitulo());
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
