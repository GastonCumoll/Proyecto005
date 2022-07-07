<?php

namespace App\Controller;

use App\Entity\PruebaNorma;
use App\Entity\Prueba;
use App\Form\PruebaNormaType;
use App\Repository\PruebaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PruebaNormaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/prueba/norma")
 */
class PruebaNormaController extends AbstractController
{

    /**
     * @Route("/sql", name="sql", methods={"GET"})
     */
    public function sql(PruebaNormaRepository $pruebaNormaRepository,PruebaRepository $pruebaRepo, EntityManagerInterface $entityManager): Response
    {

        $pruebaNorma=$pruebaNormaRepository->findAll();//sistema digesto nuevo
        dd($pruebaNorma);

        // $normas=[];
        // $contador=0;
        // $contadorInco=0;
        // $pruebaN=$pruebaRepo->findAll();//sistema digesto consulta
        // $pruebaNorma=$pruebaNormaRepository->findAll();//sistema digesto nuevo
        // $vectorDigestoConsultaRepetidas=[];
        // // foreach ($pruebaN as $prueba) {
        // //     $tituloNorma=$prueba->getTitulo();
        // //     //$tituloNorma=strtolower($tituloNorma);
        // //     $tituloNorma=substr($tituloNorma,0,30);
        // //     dump($tituloNorma);
        // // }dd("hola");
        // foreach ($pruebaN as $prueba) {
        //     $tituloPrueba=$prueba->getTitulo();
        //     $tituloPrueba=strtoupper($tituloPrueba);
        //     // $tituloPrueba=strtolower($tituloPrueba);
        //     // $tituloPrueba=strtoupper($tituloPrueba);
            
        //         // str_replace($tituloPrueba,"Nº","n");
        //         // str_replace($tituloPrueba,"n°","n");
        //     $tituloPrueba=substr($tituloPrueba,0,30);
        //     //dump($tituloPrueba);
        //         foreach ($pruebaNorma as $norma) {
                    
        //             $tituloNorma=$norma->getTitulo();
        //             //$tituloNorma=substr($tituloNorma,0,30);
        //             $posicion=strpos($tituloNorma,"-");
        //             if($posicion){
        //                 $tituloNorma=substr($tituloNorma,0,$posicion);
        //             }
        //             if(str_contains($tituloPrueba,$tituloNorma)){
        //                 //dump($prueba->getTitulo());
        //                 //dump($norma->getTitulo());
        //                 $contador++;
        //                 $vectorDigestoConsultaRepetidas[]=$prueba;
        //                 break;
        //             }
                    
        //         }
        // }
        
        // $vectorDefinitivo=[];
        //     foreach ($pruebaN as $prueba) {
        //         if(!in_array($prueba,$vectorDigestoConsultaRepetidas)){
        //             // $nuevaNorma= new PruebaNorma();
        //             // $nuevaNorma->setTitulo($prueba->getTitulo());
        //             // $nuevaNorma->setTexto($prueba->getTexto());
        //             // $nuevaNorma->setPublishStartDate($prueba->getPublishStartDate());
        //             // $nuevaNorma->setIndexDate($prueba->getIndexDate());
        //             // $entityManager->persist($nuevaNorma);
        //             $vectorDefinitivo[]=$prueba;
        //         }
        //     }
        //     //$entityManager->flush();
        //     $vectorDefinitivo=array_unique($vectorDefinitivo);
        // dd($vectorDefinitivo);

        
        // dd("holi");
        
        
        // foreach ($prueba as $unaPrueba) {
        //     $tituloPrueba=$unaPrueba->getTitulo();
            
        //     $tituloPrueba=strtolower($tituloPrueba);
        //     $tituloPrueba=substr($tituloPrueba,0,20);
            
        //     foreach ($pruebaNorma as $unaPruebaNorma) {
        //         $tituloPruebaNorma=$unaPruebaNorma->getTitulo();

        //         $tituloPruebaNorma=strtolower($tituloPruebaNorma);
        //         $tituloPruebaNorma=substr($tituloPruebaNorma,0,20);
        //         //dd($tituloPruebaNorma);
        //         if(str_contains($tituloPruebaNorma,$tituloPrueba)){
        //             $normas[]=$unaPruebaNorma;
        //         }
        //     }
        // }
        // $normas=array_unique($normas);
        // dd($normas);
    }

    /**
     * @Route("/", name="prueba_norma_index", methods={"GET"})
     */
    public function index(PruebaNormaRepository $pruebaNormaRepository): Response
    {
        return $this->render('prueba_norma/index.html.twig', [
            'prueba_normas' => $pruebaNormaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prueba_norma_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pruebaNorma = new PruebaNorma();
        $form = $this->createForm(PruebaNormaType::class, $pruebaNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pruebaNorma);
            $entityManager->flush();

            return $this->redirectToRoute('prueba_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prueba_norma/new.html.twig', [
            'prueba_norma' => $pruebaNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prueba_norma_show", methods={"GET"})
     */
    public function show(PruebaNorma $pruebaNorma): Response
    {
        return $this->render('prueba_norma/show.html.twig', [
            'prueba_norma' => $pruebaNorma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prueba_norma_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PruebaNorma $pruebaNorma, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PruebaNormaType::class, $pruebaNorma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('prueba_norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prueba_norma/edit.html.twig', [
            'prueba_norma' => $pruebaNorma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prueba_norma_delete", methods={"POST"})
     */
    public function delete(Request $request, PruebaNorma $pruebaNorma, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pruebaNorma->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pruebaNorma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prueba_norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
