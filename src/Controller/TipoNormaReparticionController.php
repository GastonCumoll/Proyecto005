<?php

namespace App\Controller;

use App\Service\SeguridadService;
use App\Repository\AreaRepository;
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
     * @Route("/{id}/edit/{t}", name="tipo_norma_reparticion_edit", methods={"GET", "POST"})
     */
    //este metodo se ejecuta cuando se quiere editar una reparticion a un tipo de norma id = id de la reparticion y t = id del tipo de norma
    public function edit($id,Request $request, TipoNormaReparticionRepository $tipoNormaReparticionRepository, EntityManagerInterface $entityManager,TipoNormaRepository $tipoNormaRepository,$t): Response
    {
        $tipo=$tipoNormaRepository->findById($t);
        
        $tipoNormaReparticion=$tipoNormaReparticionRepository->findByReparticionId($id);
        foreach ($tipoNormaReparticion as $tpr) {
            if($tpr->getTipoNormaId()==$t){
                $tipoNR=$tpr;
            }
        }
        
        $form = $this->createForm(TipoNormaReparticionType::class, $tpr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repa=$form->get('reparticionId')->getData();
            $tpr->setReparticionId($repa);
            $entityManager->persist($tpr);
            $entityManager->flush();

            return $this->redirectToRoute('reparticion_norma', ['id'=>$t], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_reparticion/edit.html.twig', [
            'tipo_norma_reparticion' => $tipoNormaReparticion,
            'form' => $form,
            'tipoNorma' =>$tipo[0],
        ]);
    }

    /**
     * @Route("/reparticionNorma/{id}", name="reparticion_norma", methods={"GET"})
     */
    //este metodo muestra las reparticiones de un tipo de norma, que es pasado por parametro en id= id de tipo de norma
    public function reparticionNorma(SeguridadService $seguridad,TipoNormaReparticionRepository $tipoNormaReparticionRepository,TipoNormaRepository $tipoNormaRepository,$id): Response
    {
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($rol);
        }else {
            $rol="";
        }

        $tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $reparticionesDeNorma=[];

        foreach ($tipoNorma[0]->getTipoNormaReparticions() as $repa) {
            $reparticionesDeNorma[]=$repa->getReparticionId();
        }

        return $this->render('tipo_norma_reparticion/index.html.twig', [
            'reparticionesDeNorma' => $reparticionesDeNorma,
            'tipoNorma' => $tipo,
            'rol'=>$rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/new/{id}", name="tipo_norma_reparticion_new", methods={"GET", "POST"})
     */
    //este metodo le agrega una reparticion a un tipo de norma que es pasado por parametro con la variable id= id del tipo de norma
    public function new(AreaRepository $areaRepository,Request $request, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        //se crean arrays para almacenar las reparticiones que tiene el tipo de norma que se esta tratando y las que le faltan.
        $repaFaltantes=[];
        $reparticionesObj=[];
        
        $tipoNormaReparticion = new TipoNormaReparticion();
        $tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $reparticionesTipo=$tipo->getTipoNormaReparticions()->toArray();
        foreach ($reparticionesTipo as $unRTipo) {
            $reparticionesObj[]=$areaRepository->findOneById($unRTipo->getReparticionId()->getId());
        }
        $todasRepa=$areaRepository->findAll();
        foreach ($todasRepa as $unaRepa) {
            if(!in_array($unaRepa,$reparticionesObj)){
                //pregunto cuales faltan:
                //repaFaltantes: las reparticiones que le faltan a ese tipo de norma
                $repaFaltantes[]=$unaRepa;
            }
        }
        //busco el id del tipo, pero int.
        $idTipo=$tipo->getId(); 
        $tipoNormaReparticion->setTipoNormaId($tipo);
        //le paso el array de reparticiones faltantes, sin antes setearle el tipoNorma al nuevo objeto de tipoNormaReparticion
        $form = $this->createForm(TipoNormaReparticionType::class, $tipoNormaReparticion,['reparticiones' => $repaFaltantes]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoNormaReparticion);
            $entityManager->flush();

            return $this->redirectToRoute('reparticion_norma', ['id'=>$idTipo], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_reparticion/new.html.twig', [
            'tipo_norma_reparticion' => $tipoNormaReparticion,
            'form' => $form,
            'idTipo'=> $idTipo,
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
     * @Route("/{id}/{t}", name="tipo_norma_reparticion_delete", methods={"POST"})
     */
    //este metodo le borra alguna reparticion a un tipo de norma, id= id de reparticion y t=id del tipo de norma
    public function delete($t,$id,Request $request, TipoNormaReparticionRepository $tipoNormaReparticionRepository, EntityManagerInterface $entityManager): Response
    {

        $tnr=$tipoNormaReparticionRepository->findByTipoNormaId($t);
        foreach ($tnr as $tnrNorma) {
            if($tnrNorma->getReparticionId()->getId()==$id){
                $tnrDelete=$tnrNorma;
            }
        }
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $entityManager->remove($tnrDelete);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reparticion_norma',['id'=>$t], Response::HTTP_SEE_OTHER);
    }
}
