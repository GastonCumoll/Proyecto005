<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Service\SeguridadService;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{

    /**
     * @Route("/", name="item_index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository,Request $request, SeguridadService $seguridad,PaginatorInterface $paginator): Response
    {

        $itemsAll = $itemRepository->createQueryBuilder('p')->getQuery();

        // Paginar los resultados de la consulta
        $items = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $itemsAll,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $items->setCustomParameters([
            'align' => 'center',
        ]);

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
        return $this->render('item/index.html.twig', [
            'items' => $items,
            'rol' =>$rol,
            'roles'=>$arrayRoles,
        ]);
    }
    
    /**
     * @Route("/{palabra}/busquedaParam", name="busqueda_param_item", methods={"GET","POST"}, options={"expose"=true})
     */
    //metodo para buscar un item por su nombre
    public function busquedaParam(ItemRepository $itemRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //dd($palabra);
        //$palabra es el string que quiero buscar
        $palabra=str_replace("§","/",$palabra);
        
        if($palabra==" "){
            $todosItems=[];
        }else{
            $todosItems=$itemRepository->findUnItem($palabra);//ORMQuery
        }

        // Paginar los resultados de la consulta
        $items = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todosItems,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

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
        return $this->render('item/index.html.twig', [
            'rol' => $rol,
            'items' => $items,
            'roles'=>$arrayRoles,
        ]);
        
    }

    /**
     * @Route("/new", name="item_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new Item();
        
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form['dependencias']->getData());
            //$depen=$form['dependencias']->getData();
            //$tam=sizeof($depen);
            $entityManager->persist($item);
            $entityManager->flush();
            // for($i=0;$i<$tam;$i++){
            //     $item->addDependencia($depen[$i]);
            //     $depen[$i]->setPadre($item);
            //     $entityManager->persist($depen[$i]);
            // }
            // $entityManager->persist($item);
            // $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        //dd($item);
        $hijos=$item->getDependencias();
        // dd($hijos);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($hijos as $unHijo) {
                $unHijo->setPadre($item);
                $item->addDependencia($unHijo);
                $entityManager->persist($item);
                $entityManager->persist($unHijo);
            }    
            $entityManager->flush();

            return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"POST"})
     */
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            //creo vector que contiene los hijos y un obj que contiene padre
            $hijos=$item->getDependencias();
            $padre = $item->getPadre();

            //pregunto si tiene normas
            if($item->getNormas() != null){
                $normas=$item->getNormas();
                //seteo las normas del que quiero borrar a su padre
                if($padre){
                    foreach ($normas as $unaNorma) {
                        $padre->addNorma($unaNorma);
                    }
                }
            }
            //remuevo el item
            $entityManager->remove($item);
            //$entityManager->flush();

            if($hijos != null){
                foreach ($hijos as $hijo) {
                    // dd($item->getDependencias());
                    //dump($hijo);
                    //dump($item->getPadre());
                    $hijo->setPadre($padre);
                    $entityManager->persist($hijo);
                }
                $entityManager->flush();
            }
            //dd($item);
        }
        return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }
}
