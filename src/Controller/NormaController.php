<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use App\Entity\Item;
use App\Entity\Tema;
use App\Entity\Norma;
use App\Form\LeyType;
use App\Form\NormaType;
use App\Entity\Etiqueta;
use App\Entity\Relacion;
use App\Entity\TipoNorma;
use App\Form\DecretoType;
use App\Form\CircularType;
use App\Form\RelacionType;
use App\Form\OrdenanzaType;
use App\Form\TipoNormaType;
use App\Form\ResolucionType;
use App\Repository\ItemRepository;
use App\Repository\TemaRepository;
use App\Repository\NormaRepository;
use App\Repository\TituloRepository;
use App\Repository\CapituloRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
     * @Route("/{id}/normasAjax", name="normas_ajax", methods={"GET"}, options={"expose"=true})
     */
    public function normasAjax(NormaRepository $normaRepository,TemaRepository $temaRepository,ItemRepository $itemRepository,$id): Response
    {
        
        //id = id de la norma 
        //$tema=$temaRepository->find($id);
        //$normas=$tema->getNormas()->toArray();
        
        //id=id del item
        $item=$itemRepository->find($id);
        $normas=$item->getNormas()->toArray();
        
        //dd($normas);
        // foreach ($normas as $unaNorma) {
        //     dd($unaNorma);
        // }
        //dd(json_encode($normas));
        
        
            $jsonData = array();  
            $idx = 0;  
            foreach($normas as $unaNorma) {  
                $temp = array(
                    'numero' => $unaNorma->getNumero(),  
                    'titulo' => $unaNorma->getTitulo(),  
                    'tipo' => $unaNorma->getTipoNorma()->getNombre(),
                    'id' => $unaNorma->getId(),

                );   
                $jsonData[$idx++] = $temp;  
            }
            //dd($jsonData);
            return new Response(json_encode($jsonData), 200, array('Content-Type'=>'application/json'));
            // return $this->render("indiceDigesto/indiceDigesto.html.twig",[
            //     'arrayNormas' => $normas,
            // ]); 
        
        
        
        
        
    }

    /**
     * @Route("{id}/mostrarTexto", name="mostrar_texto", methods={"GET"})
     */
    public function mostrarTexto(NormaRepository $normaRepository ,$id): Response
    {
        return $this->render('norma/mostrarTexto.html.twig', [
            'id' => $normaRepository->find($id),
        ]);
    }
    
    /**
     * @Route("{id}/new", name="norma_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository ,$id, SluggerInterface $slugger): Response
    {
        $repository = $this->getDoctrine()->getRepository(TipoNorma::class);
        $idNorma = $repository->find($id);
        
        $etiquetaRepository= $this->getDoctrine()->getRepository(Etiqueta::class);

        switch ($idNorma->getNombre()){
            case 'Decreto':
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(DecretoType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ordenanza':
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(OrdenanzaType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Resolucion':
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(ResolucionType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ley':
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(LeyType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Circular':
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(CircularType::class, $norma);
                $form->handleRequest($request);
                break;
            default:
                $norma = new Norma();
                $norma->setTipoNorma($idNorma);
                $form = $this->createForm(CircularType::class, $norma);
                $form->handleRequest($request);
                break;
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $today = new DateTime();
            $norma->setFechaPublicacion($today);
            $norma->setEstado("Borrador");
            
            $brochureFile = $form->get('pdfFile')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $norma->setpdfFile($newFilename);
            }

            //se almacena en la variable $etiquetas las etiquetas ingresadas en el formulario, se las separa con la función explode por comas y se las guarda en un array

            $etiquetas = explode(",", $form['nueva_etiqueta']->getData());
            $item =$form['items']->getData();
            
            foreach ($item as $unItem) {
                $newItem= new Item();
                $newItem=$unItem;
                $norma->addItem($newItem);
                $newItem->addNorma($norma); 
                $entityManager->persist($newItem);
            }

            $entityManager->persist($norma);
            $entityManager->flush();
            
            foreach ($etiquetas as $unaEtiqueta) {
                $etiquetaSinEspacios="";
                for($i=0; $i<strlen($unaEtiqueta) ;$i++) {
                        if(($unaEtiqueta[$i]==" " && $unaEtiqueta[$i-1]!=" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]==" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]!=" ")){
                            $etiquetaSinEspacios.=$unaEtiqueta[$i];
                        }
                    }
                    
                    $etiqueta=trim($etiquetaSinEspacios);
                    $etiquetaSinEspacios = $etiqueta;

                if(!$etiquetaRepository->findOneBy(['nombre' => $etiquetaSinEspacios]))
                {   
                    
                    $etiquetaNueva = new Etiqueta();
                    $etiquetaNueva->setNombre($etiquetaSinEspacios);
                    $etiquetaNueva->addNorma($norma);
                    $norma->addEtiqueta($etiquetaNueva);
                
                    $entityManager->persist($etiquetaNueva);
                }
                
                $entityManager->persist($norma);
                
            }
            $entityManager->flush();
            
            if($norma->getRela()==true){
                
                $id=$norma->getId();
                $session=$request->getSession();
                $session->set('id',$id);
                
                return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
            
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
        $relacion= $repository->findByNorma($id);
        
        return $this->render('norma/show.html.twig', [
            'norma' => $norma,
            'relacion' => $relacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="norma_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        switch ($norma->getTipoNorma()->getNombre()){
            case 'Decreto':
                $form = $this->createForm(DecretoType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ordenanza':
                $form = $this->createForm(OrdenanzaType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Resolucion':
                $form = $this->createForm(ResolucionType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ley':
                $form = $this->createForm(LeyType::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Circular':
                $form = $this->createForm(CircularType::class, $norma);
                $form->handleRequest($request);
            break;
            default:
            $form = $this->createForm(CircularType::class, $norma);
            $form->handleRequest($request);
            break;
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $brochureFile = $form->get('pdfFile')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $norma->setpdfFile($newFilename);
            }

            $etiquetas = explode(", ", $form['nueva_etiqueta']->getData());
            $item =$form['items']->getData();
            foreach ($item as $unItem) {
                $newItem= new Item();
                $newItem=$unItem;
                $norma->addItem($newItem);
                $newItem->addNorma($norma); 
                $entityManager->persist($newItem);
            }
            
            $entityManager->persist($norma);
            $entityManager->flush();

            $etiquetaRepository= $this->getDoctrine()->getRepository(Etiqueta::class);
            foreach ($etiquetas as $unaEtiqueta) {
                $etiquetaSinEspacios="";
                for($i=0; $i<strlen($unaEtiqueta) ;$i++) {
                        if(($unaEtiqueta[$i]==" " && $unaEtiqueta[$i-1]!=" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]==" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]!=" ")){
                            $etiquetaSinEspacios.=$unaEtiqueta[$i];
                        }
                    }
            if(!$etiquetaRepository->findOneBy(['nombre' => $etiquetaSinEspacios]))
            {
                $etiquetaNueva = new Etiqueta();
                $etiquetaNueva->setNombre($etiquetaSinEspacios);
                $etiquetaNueva->addNorma($norma);
                $norma->addEtiqueta($etiquetaNueva);
                $entityManager->persist($etiquetaNueva);
            }
                $entityManager->persist($norma);   
            }
            $entityManager->flush();

            if($norma->getRela()==true){
                
                $id=$norma->getId();
                $session=$request->getSession();
                $session->set('id',$id);
                
                return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            }
            
            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('norma/edit.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/{t}", name="norma_show_arbol", methods={"GET"})
     */
    public function normaArbol(Norma $norma,$id,$t): Response
    {
        
        $repository = $this->getDoctrine()->getRepository(Relacion::class);
        $relacion= $repository->findByNorma($id);
        
        $itemDeNorma=$norma->getItems();
        // dd($relacion);
        $item;
        foreach ($itemDeNorma as $unItem) {
            if($unItem->getId()==$t){
                $item = $unItem;
            }
            
        }
        // dd($temaDeNorma);

        $complementada=$repository->findByComplementada($id);
        
        //dd($relaciones);
        return $this->render('norma/normaShowArbol.html.twig', [
            'item' => $item,
            'norma' => $norma,
            'relacion' => $relacion,
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
