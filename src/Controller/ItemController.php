<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/itemAjax", name="item_ajax", methods={"GET","POST"}, options={"expose"=true})
     */
    public function itemAjax(Request $request, EntityManagerInterface $entityManager,ItemRepository $itemRepository): Response
    {
        //return new JsonResponse($request->request->get('id'));
        $item=$request->request->get('id');
        $em=$itemRepository->find($item);
        
        $jsonData = array();  
            $idx = 0;  
            foreach($em->getDependencias() as $unaDependencia) {  
                $temp = array(
                    'id' => $unaDependencia->getId(),
                    'idPadre' => $unaDependencia->getIdPadre(),
                    'nombre' => $unaDependencia->getNombre(),
                );   
                $jsonData[$idx++] = $temp;  
            }
            //dd($jsonData);
            return new Response(json_encode($jsonData), 200, array('Content-Type'=>'application/json'));
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
            $depen=$form['dependencias']->getData();
            $tam=sizeof($depen);
            
            
            $entityManager->persist($item);
            $entityManager->flush();
            for($i=0;$i<$tam;$i++){
                $item->addDependencia($depen[$i]);
                $depen[$i]->setIdPadre($item);
                $entityManager->persist($depen[$i]);
            }
            $entityManager->persist($item);
            $entityManager->flush();

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

        if ($form->isSubmitted() && $form->isValid()) {
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
            $entityManager->remove($item);

            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }
}
