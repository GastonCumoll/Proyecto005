<?php

namespace App\Controller;

use DateTime;
use App\Entity\Norma;
use App\Entity\Relacion;
use App\Form\RelacionType;
use App\Repository\NormaRepository;
use App\Repository\RelacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoRelacionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\EventSubscriber\SecuritySubscriber;

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
    
    //editar relacion existente en 2 normas
    /**
     * @Route("/edit/{id}/{idA}/{idR}", name="relacion_edit", methods={"GET","POST"})
     */
    public function edit($id,$idR,$idA,NormaRepository $normaRepositorty,RelacionRepository $relacionRepository,Request $request,TipoRelacionRepository $tipoRelaRepository, EntityManagerInterface $entityManager): Response
    {
        $today=new DateTime();
        //buscar la relacion ya creada entre las dos normas y eliminarla ($idR)
        $relacion=$relacionRepository->find($idR);//relacion=id de la primera relacion entre las dos normas
        $relacion->setFechaRelacion($today);
        
        $repository = $this->getDoctrine()->getRepository(Norma::class);
        $norma = $repository->find($id);
        $relacion->setNorma($norma);
        $opcion=$tipoRelaRepository->findByPrioridad(1);
        
        $normaC=$normaRepositorty->find($idA);
        $relacion->setComplementada($normaC);
        // dd($normaC); $normaC= norma complementada(la segunda)

        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $relacionInversa=$relacionRepository->find($idR+1);//relacionInversa=id de la segunda relacion entre las normas
            $normaOrigen = $form['complementada']->getData();
            $relacionInversa->setNorma($normaOrigen);
            $tipoRela=$form['tipoRelacion']->getData();
            $relacion->setTipoRelacion($tipoRela);
            $relacionInversa->setTipoRelacion($tipoRela->getInverso());
            $relacion->setFechaRelacion($today);
            $relacionInversa->setFechaRelacion($today);
            $desc=$form['descripcion']->getData();
            $relacion->setDescripcion($desc);
            $relacionInversa->setDescripcion($desc);
            $user=$form['usuario']->getData();
            $relacion->setUsuario($user);
            $relacionInversa->setUsuario($user);
            $resumen=$form['resumen']->getData();
            $relacion->setResumen($resumen);
            $relacionInversa->setResumen($resumen);

            $entityManager->persist($relacion);
            $entityManager->persist($relacionInversa);
            $entityManager->flush();

            return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
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


    //agregarle una relacion a una norma
    /**
     * @Route("/{id}/relaFormEdit", name="form_rela_edit", methods={"GET", "POST"})
     */
    public function relacionFormEditar($id,TipoRelacionRepository $tipoRelaRepository, RelacionRepository $relacionRepository,Request $request, EntityManagerInterface $entityManager, NormaRepository $repository): Response
    {
        $today=new DateTime();
        $relacion=new Relacion();

        $relacion->setFechaRelacion($today);
        
        $repository = $this->getDoctrine()->getRepository(Norma::class);
        $norma = $repository->find($id);
        $relacion->setNorma($norma);
        $opcion=$tipoRelaRepository->findByPrioridad(1);
        //dd($opcion);
        $form = $this->createForm(RelacionType::class, $relacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($relacion);
            
            $relacionInversa= new Relacion();
            $normaOrigen = $form['complementada']->getData();
            $relacionInversa->setNorma($normaOrigen);
            $normaDestino = $form['norma']->getData();
            $relacionInversa->setComplementada($normaDestino);
            $relacionInversa->setFechaRelacion($today);
            $relacionInversa->setDescripcion($relacion->getDescripcion());
            $relacionInversa->setResumen($relacion->getResumen());
            $relacionInversa->setUsuario($relacion->getUsuario());
            
            $tipoRela=$form['tipoRelacion']->getData();
            
            $relacionInversa->setTipoRelacion($tipoRela->getInverso());
            
            $entityManager->persist($relacionInversa);
            $entityManager->flush();

            return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('relacion/new.html.twig', [
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
