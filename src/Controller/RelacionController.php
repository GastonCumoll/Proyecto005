<?php

namespace App\Controller;

use DateTime;
use App\Entity\Norma;
use App\Entity\Relacion;
use App\Entity\Auditoria;
use App\Form\RelacionType;
use App\Repository\NormaRepository;
use App\Repository\RelacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoRelacionRepository;
use App\EventSubscriber\SecuritySubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/edit/{id}/{idA}/{idR}", name="relacion_edit", methods={"GET","POST"})
     */
    //este metodo es usado cuando se quiere editar relacion existente en 2 normas
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
            //buscar usuario
            $session=$this->get('session');
            $usuario=$session->get('username');
            //$userObj=$usuarioRepository->findOneByNombre($usuario);

            //setear el usuario(ojo q es un string,no entidad)
            $relacion->setUsuario($usuario);
            $relacionInversa->setUsuario($usuario);
            $resumen=$form['resumen']->getData();
            $relacion->setResumen($resumen);
            $relacionInversa->setResumen($resumen);

            $entityManager->persist($relacion);
            $entityManager->persist($relacionInversa);

            //usuarios
            //obtener el nombre del usuario logeado;
            $session=$this->get('session');
            $usuario=$session->get('username');
            
            //crear auditoria
            $auditoria=new Auditoria();
            $auditoria->setFecha($today);
            $auditoria->setAccion("Modificacion relacion");
            $instancia=$norma->getInstancia();
            $auditoria->setInstanciaAnterior($instancia);
            $auditoria->setInstanciaActual(1);
            $estadoAnt=$norma->getEstado();
            $auditoria->setEstadoAnterior($estadoAnt);
            $auditoria->setEstadoActual("Borrador");
            $auditoria->setNombreUsuario($usuario);
            $auditoria->setNorma($norma);
            $entityManager->persist($auditoria);
            $norma->setInstancia(1);
            $norma->addAuditoria($auditoria);
            //$userObj->addAuditoria($auditoria);


            //setear instancia=1;
            $norma->setInstancia(1);
            $entityManager->persist($norma);
            

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


    
    /**
     * @Route("/{id}/relaFormEdit", name="form_rela_edit", methods={"GET", "POST"})
     */
    //este metodo es usado cuando se quiere agregarle una relacion a una norma
    public function relacionFormEditar($id,TipoRelacionRepository $tipoRelaRepository, RelacionRepository $relacionRepository,Request $request, EntityManagerInterface $entityManager, NormaRepository $repository): Response
    {
        $today=new DateTime();
        $relacion=new Relacion();

        $relacion->setFechaRelacion($today);
        
        $repository = $this->getDoctrine()->getRepository(Norma::class);
        $norma = $repository->find($id);
        $relacion->setNorma($norma);
        $opcion=$tipoRelaRepository->findByPrioridad(1);
        $session=$this->get('session');
        $usuario=$session->get('username');
        $relacion->setUsuario($usuario);
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
            
            $relacionInversa->setUsuario($usuario);
            
            $tipoRela=$form['tipoRelacion']->getData();
            
            $relacionInversa->setTipoRelacion($tipoRela->getInverso());

            //usuarios
            //obtener el nombre del usuario logeado;
            $session=$this->get('session');
            $usuario=$session->get('username');
            
            //crear auditoria
            $auditoria=new Auditoria();
            $auditoria->setFecha($today);
            $auditoria->setAccion("Carga relacion");
            $instancia=$norma->getInstancia();
            $auditoria->setInstanciaAnterior($instancia);
            $auditoria->setInstanciaActual(1);
            $estadoAnt=$norma->getEstado();
            $auditoria->setEstadoAnterior($estadoAnt);
            $auditoria->setEstadoActual("Borrador");
            $auditoria->setNombreUsuario($usuario);
            $auditoria->setNorma($norma);
            $entityManager->persist($auditoria);
            $norma->setInstancia(1);
            $norma->addAuditoria($auditoria);
            //$userObj->addAuditoria($auditoria);


            //setear instancia=1;
            $norma->setInstancia(1);
            $entityManager->persist($norma);
            

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
