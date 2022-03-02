<?php

namespace App\Controller;

use DateTime;
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
 * @Route("/norma")
 */
class NormaController extends AbstractController
{
    /**
     * @Route("/", name="norma_index", methods={"GET"})
     */
    public function index(NormaRepository $normaRepository): Response
    {   
        return $this->render('norma/index.html.twig', [
            'normas' => $normaRepository->findAll(),
        ]);
    }
    
    
    /**
     * @Route("{id}/new", name="norma_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository ,$id): Response
    {
        // $repository1 = $this->getDoctrine()->getRepository(Norma::class);
        // $hola = $repository1->findById(1);
        // dd($hola);
        $repository = $this->getDoctrine()->getRepository(TipoNorma::class);
        $idNorma = $repository->find($id);
        
        if($id==1)
        {
            $norma = new Norma();
            $norma->setTipoNorma($idNorma);
            $norma->setEstado("vigente");
            $form = $this->createForm(DecretoType::class, $norma);
            $form->handleRequest($request);
        }elseif($id==2)
        {
            $norma = new Norma();
            $norma->setTipoNorma($idNorma);
            $norma->setEstado("vigente");
            $form = $this->createForm(OrdenanzaType::class, $norma);
            $form->handleRequest($request);
        }elseif($id==3)
        {
            $norma = new Norma();
            $norma->setTipoNorma($idNorma);
            $norma->setEstado("vigente");
            $form = $this->createForm(ResolucionType::class, $norma);
            $form->handleRequest($request);
        }elseif($id==4)
        {
            $norma = new Norma();
            $norma->setTipoNorma($idNorma);
            $norma->setEstado("vigente");
            $form = $this->createForm(LeyType::class, $norma);
            $form->handleRequest($request);
        }elseif($id==5)
        {
            $norma = new Norma();
            $norma->setTipoNorma($idNorma);
            $norma->setEstado("vigente");
            $form = $this->createForm(CircularType::class, $norma);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $today = new DateTime();
            $norma->setFechaPublicacion($today);
            $entityManager->persist($norma);
            $entityManager->flush();
                
            if($norma->getRela()==true){
                
                $id=$norma->getId();
                $session=$request->getSession();
                $session->set('id',$id);
                
                return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
            

            
            
            // $normaComplementa=$norma->getCA();
            // $normaComplementada=$norma->getCPor();
            

            // foreach ($normaComplementada as $unaComplementada) {
            //     $norma->addCPor($unaComplementada);
            // }
            // foreach ($normaComplementa as $unaComplementa) {
            //     $unaComplementa->addCA($norma);
            // }
            // dd($norma);
            // $entityManager->flush();

            
        }
        
        return $this->renderForm('norma/new.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="norma_show", methods={"GET"})
     */
    public function show(Norma $norma,$id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Relacion::class);
        $complementa = $repository->findByNorma($id);

        $complementada=$repository->findByComplementada($id);
        
        //dd($relaciones);
        return $this->render('norma/show.html.twig', [
            'norma' => $norma,
            'complementaA' =>$complementa,
            'complementadaPor'=>$complementada
        ]);
    }

    /**
     * @Route("/{id}/edit", name="norma_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Norma $norma, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NormaType::class, $norma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('norma/edit.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="norma_delete", methods={"POST"})
     */
    public function delete(Request $request, Norma $norma, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$norma->getId(), $request->request->get('_token'))) {
            $entityManager->remove($norma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
