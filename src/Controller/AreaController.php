<?php

namespace App\Controller;

use App\Entity\Area;
use App\Form\AreaType;
use App\Form\AreaEditType;
use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use App\Service\FindReparticionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/area")
 */
class AreaController extends AbstractController
{
    /**
     * @Route("/", name="area_index", methods={"GET"})
     */
    public function index(AreaRepository $areaRepository,SeguridadService $seguridad): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
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



        return $this->render('area/index.html.twig', [
            'areas' => $areaRepository->findAll(),
            'rol'=>$rol,
            'roles'=>$arrayRoles,
        ]);
    }


    /**
     * @Route("/new", name="area_new", methods={"GET", "POST"})
     */
    public function new(AreaRepository $areaRepository,Request $request, EntityManagerInterface $entityManager,FindReparticionService $findReparticionService): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        //se buscan todas las reparticiones, luego se hace un unset de las reparticiones que ya estan cargadas en el sistema;
        $reparticiones = $findReparticionService->DatosReparticiones();
        $areas=$areaRepository->findAll();
        $idAreas=[];
        foreach ($areas as $unArea) {
            $idAreas[]=$unArea->getId();
        }
        for($i=0;$i<count($reparticiones);$i++){
            foreach ($idAreas as $unId) {
                if($unId == $reparticiones[$i]['idReparticion']){
                    unset($reparticiones[$i]);
                    break;
                }
            }
        }
        
        $area = new Area();
        $form = $this->createForm(AreaType::class,null,['reparticiones' => $reparticiones]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $idRepa=$form->get('nombre')->getData();
            //dd($idRepa);
            // $area->setId($idRepa);
            $nombreRepa=$findReparticionService->getNombreReparticion($idRepa);
            $area->setId($idRepa);
            $area->setNombre($nombreRepa);
            
            
            $entityManager->persist($area);
            
            $entityManager->persist($area);
            $entityManager->flush();

            return $this->redirectToRoute('area_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/new.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="area_show", methods={"GET"})
     */
    public function show(Area $area): Response
    {
        return $this->render('area/show.html.twig', [
            'area' => $area,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="area_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Area $area, EntityManagerInterface $entityManager): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(AreaEditType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('area_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/edit.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="area_delete", methods={"POST"})
     */
    public function delete(Request $request, Area $area, EntityManagerInterface $entityManager): Response
    {
        $sesion=$this->get('session');
        if($sesion->get('repaid') != 5){
            return $this->redirectToRoute('not_repa', [], Response::HTTP_SEE_OTHER);
        }
        if(!empty($area->getTipoNormaReparticions()->toArray())){
            $this->addFlash(
                'errorDeleteArea',
                "No puede eliminar esta repartición, ya que está vinculada a un tipo de norma."
            );
            return $this->redirectToRoute('area_index',[],Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$area->getId(), $request->request->get('_token'))) {
            $entityManager->remove($area);
            $entityManager->flush();
        }

        return $this->redirectToRoute('area_index', [], Response::HTTP_SEE_OTHER);
    }
}
