<?php

namespace App\Controller;

use App\Service\SeguridadService;
use App\Repository\ItemRepository;
use App\Repository\NormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use App\EventSubscriber\SecuritySubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/indice")
 */
class IndiceController extends AbstractController
{
    /**
     * @Route("/Vigentes", name="indice_vigente", methods={"GET"})
     */
    public function indexVigente(ItemRepository $itemRepo, NormaRepository $normaRepository,Request $request, SeguridadService $seguridad): Response
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
        $item=$itemRepo->findByNombre("NORMAS VIGENTES");//los items vigentes
        foreach ($item as $unItem) {
            $items=$unItem->getDependencias();
        }
        
        return $this->render('indiceDigesto/indiceDigesto.html.twig', [
            //'normas' => $normaRepository->findAll(),
            'bandera' =>1,
            'items' => $items,
            'rol' => $rol,
            'roles'=>$arrayRoles,
        ]);
    }
    /**
     * @Route("/NoVigentes", name="indice_no_vigente", methods={"GET"})
     */
    public function indexNoVigente(ItemRepository $itemRepo, NormaRepository $normaRepository,Request $request, SeguridadService $seguridad): Response
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
        $item=$itemRepo->findByNombre("NORMAS NO VIGENTES");//los items vigentes
        foreach ($item as $unItem) {
            $items=$unItem->getDependencias();
        }
        return $this->render('indiceDigesto/indiceDigesto.html.twig', [
            //'normas' => $normaRepository->findAll(),
            'bandera' =>0,
            'items' => $items,
            'rol' => $rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/paginaPrincipal", name="pagina_principal", methods={"GET"})
     */
    public function paginaPrincipal(NormaRepository $normaRepository): Response
    {
        return $this->render('base.html.twig', [
            'normas' => $normaRepository->findAll(),
        ]);
    }
}