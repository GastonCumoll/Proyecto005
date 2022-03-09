<?php

namespace App\Controller;

use DateTime;
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
use App\Repository\TemaRepository;
use App\Repository\NormaRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/norma")
 */
class NormaController extends AbstractController
{

    /**
     * @Route("/{id}/deTema", name="normas_de_tema", methods={"GET"})
     */
    public function normasTema(NormaRepository $normaRepository,TemaRepository $temaRepository, $id): Response
    {
        $tema=$temaRepository->find($id);
        $normas=$tema->getNormas();
        
        
        return $this->render('norma/normasDeTema.html.twig', [
            'normas' => $normas
        ]);
    }

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
    public function new(Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository ,$id): Response
    {
        $repository = $this->getDoctrine()->getRepository(TipoNorma::class);
        $idNorma = $repository->find($id);
        
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
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $today = new DateTime();
            $norma->setFechaPublicacion($today);
            $norma->setEstado("Borrador");
            
            //se almacena en la variable $etiquetas las etiquetas ingresadas en el formulario, se las separa con la función explode por espacios en blanco y se las guarda en un array
            $etiquetas = explode(", ", $form['etiquetasE']->getData());
            $tema =$form['temas']->getData();
            
            
            //$entityManager->persist($tema);

            foreach ($tema as $unTema) {
                $newTema= new Tema();
                $newTema=$unTema;
                // dd($newTema);
            $norma->addTema($newTema);
            $newTema->addNorma($norma); 
            $entityManager->persist($newTema);
            }
            $entityManager->persist($norma);
            $entityManager->flush();
            //dd($norma);
            foreach ($etiquetas as $unaEtiqueta) {
                if(!$repository->findOneBy(['nombre' => $unaEtiqueta]))
                {
                $etiquetaNueva = new Etiqueta();
                $etiquetaNueva->setNombre($unaEtiqueta);
                $etiquetaNueva->addNorma($norma);
                $norma->addEtiqueta($etiquetaNueva);
                
                $entityManager->persist($etiquetaNueva);
                }
                $entityManager->persist($norma);
                $entityManager->flush();
            }

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
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $etiquetas = explode(", ", $form['etiquetasE']->getData());
            $repository = $this->getDoctrine()->getRepository(Etiqueta::class);
            foreach ($etiquetas as $unaEtiqueta) {

            if(!$repository->findOneBy(['nombre' => $unaEtiqueta]))
            {
                $etiquetaNueva = new Etiqueta();
                $etiquetaNueva->setNombre($unaEtiqueta);
                $etiquetaNueva->addNorma($norma);
                $norma->addEtiqueta($etiquetaNueva);
                $entityManager->persist($etiquetaNueva);
            }
                $entityManager->persist($norma);        
            }
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
