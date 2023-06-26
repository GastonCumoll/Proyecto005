<?php

namespace App\Controller;

use App\Service\SeguridadService;
use App\Repository\ItemRepository;
use App\Repository\NormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\EventSubscriber\SecuritySubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Session\Session;
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
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
        }else {
            $rol="";
        }
        $item=$itemRepo->findByNombre("NORMAS VIGENTES");//los items vigentes
        foreach ($item as $unItem) {
            $items=$unItem->getDependencias();
        }
        
        return $this->render('indiceDigesto/indiceDigesto.html.twig', [
            'bandera' =>1,
            'items' => $items,
            'rol' => $rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/generate/tree", name="generarArbol", methods={"GET"})
     */
    public function generarArbolAction(ItemRepository $itemRepository)
    {
        // Obtén las raíces de tu estructura de ítems (las que no tienen padre)
        $raices = $itemRepository->findBy(['padre' => null]);
    
        // Crea un array para almacenar los datos del árbol
        $arbol = [];
    
        // Recorre las raíces y construye el árbol recursivamente
        foreach ($raices as $raiz) {
            $arbol[] = $this->construirNodoArbol($raiz);
        }
        // Devuelve los datos del árbol en formato JSON
        return new Response(json_encode($arbol), 200, array('Content-Type'=>'application/json'));
    }
    
    private function construirNodoArbol($item)
    {
        $nodo = [
            'id' => $item->getId(),
            'text' => $item->getNombre(),
        ];
    
        // Obtén las dependencias del item actual y construye los nodos hijos recursivamente
        $dependencias = $item->getDependencias();
        if (!empty($dependencias)) {
            $nodo['inc'] = [];
    
            foreach ($dependencias as $dependencia) {
                $nodo['inc'][] = $this->construirNodoArbol($dependencia);
            }
        }
    
        return $nodo;
    }

    /**
     * @Route("/tree", name="prueba_Arbol", methods={"GET"})
     */
    public function pruebaArbol(ItemRepository $itemRepo, NormaRepository $normaRepository,Request $request, SeguridadService $seguridad): Response
    {

        return $this->render('indiceDigesto/arbolito.html.twig', [
            
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