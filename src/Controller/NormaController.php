<?php

namespace App\Controller;


use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Item;
use App\Entity\Norma;
use App\Form\LeyType;
use App\Form\NormaType;
use App\Entity\Etiqueta;
use App\Entity\Relacion;
use App\Entity\TipoNorma;
use App\Form\DecretoType;
use App\Form\LeyTypeEdit;
use App\Entity\ArchivoPdf;
use App\Form\CircularType;
use App\Form\RelacionType;
use App\Form\OrdenanzaType;
use App\Form\TextoEditType;
use App\Form\TipoNormaType;
use App\Form\ResolucionType;
use Spipu\Html2Pdf\Html2Pdf;
use App\Form\DecretoTypeEdit;
use App\Form\CircularTypeEdit;
use App\Form\OrdenanzaTypeEdit;
use App\Form\ResolucionTypeEdit;
use App\Repository\ItemRepository;
use App\Repository\NormaRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\TipoNormaRepository;
use App\Repository\ArchivoPdfRepository;
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
    public function normasAjax(NormaRepository $normaRepository,ItemRepository $itemRepository,$id): Response
    {

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
     * @Route("/{id}/mostrarPDF", name="mostrar_pdf")
     */

    public function mostrarPdf(EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoPdfRepository $archivoPdfRepository ,$id): Response
    {
        
        $norma=$normaRepository->find($id);
        $normaNombre=$norma->getTitulo();
        $tipoNorma=$norma->getTipoNorma()->getNombre();
        $options = new Options();
        $options->set('isRemoteEnabled',true);
        // Crea una instancia de Dompdf
        $dompdf = new Dompdf($options);
        $today = new DateTime();
        $result = $today->format('d-m-Y H:i:s');
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);
        $base_path="/upload/Froala/2603abc80d9e97b1e58d97ebc520c660f0763ad2.jpg";
        
        $dompdf->setBasePath($base_path);
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();

        
        // Envíe el PDF generado al navegador (descarga forzada)
        $dompdf->stream($tipoNorma."-".$normaNombre."-MODIFICADA-".$result."-.pdf", [
            "Attachment" => false
        ]);
        
        // return $this->redirectToRoute('norma_edit', ['id' =>$id], Response::HTTP_SEE_OTHER);
        exit(1);
    }

    /**
     * @Route("/{id}/generarPDF", name="generar_pdf")
     */

    public function generarPdf(EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoPdfRepository $archivoPdfRepository ,$id): Response
    {
        $norma=$normaRepository->find($id);
        $normaNombre=$norma->getTitulo();
        $normaNombreLimpio=str_replace("/","-",$normaNombre);//reemplaza / por - asi puede guardarlo
        
        
        $options = new Options();
        $options->set('isRemoteEnabled',true);
        // Crea una instancia de Dompdf
        $dompdf = new Dompdf($options);
        $today = new DateTime();
        $result = $today->format('d-m-Y H-i-s');
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);
        
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        $publicDirectory = 'uploads/pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $nombre='/'.$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
        $ruta=$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
        
        
        $pdfFilepath =  $publicDirectory . $nombre;
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        $archi=new ArchivoPdf();
        $archi->setNorma($norma);
        $archi->setRuta($ruta);
        $archi->setNombre($normaNombre);
        
        $archivos=$archivoPdfRepository->findByNorma($id);
        foreach ($archivos as $unArchi) {
            if($unArchi->getRuta()==$ruta){
                $entityManager->remove($unArchi);
            }
        }
        
        $entityManager->persist($archi);
        $norma->addArchivosPdf($archi);
        $entityManager->persist($norma);
        $entityManager->flush();

        // Envíe el PDF generado al navegador (descarga forzada)
        // $dompdf->stream("Norma-".$normaNombre."-MODIFICADA-".$result."-.pdf", [
        //     "Attachment" => false
        // ]);
        
        return $this->redirectToRoute('texto_edit', ['id' =>$id], Response::HTTP_SEE_OTHER);
        exit(1);
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

            //dd($form->get('archivo')->getData());
            $today = new DateTime();
            $norma->setFechaPublicacion($today);
            $norma->setEstado("Borrador");

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
            
            $brochureFile = $form->get('archivo')->getData();

            if ($brochureFile) {
                foreach ($brochureFile as $unArchivo) {
                    $originalFilename = pathinfo($unArchivo->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$unArchivo->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $unArchivo->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $archi=new ArchivoPdf();
                    $archi->setRuta($newFilename);
                    $archi->setNorma($norma);
                    $archi->setNombre($originalFilename);

                    

                    $entityManager->persist($archi);
                    $norma->addArchivosPdf($archi);
                }
            }


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
            $idNorma=$norma->getId();
            //REDIRECCIONAMIENTO SI LA NORMA TIENE RELACION
            // if($norma->getRela()==true){
                
            //     $id=$norma->getId();
            //     $session=$request->getSession();
            //     $session->set('id',$id);
                
            //     return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            // }
            return $this->redirectToRoute('norma_show', ['id'=>$idNorma], Response::HTTP_SEE_OTHER);
            
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
     * @Route("/{id}/editTexto", name="texto_edit", methods={"GET", "POST"})
     */
    public function editTexto(Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
                $form = $this->createForm(TextoEditType::class, $norma);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid())
                {
                    $entityManager->persist($norma);
                    $entityManager->flush();
                    return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
                }
                return $this->renderForm('norma/edit.html.twig', [
                    'norma' => $norma,
                    'form' => $form,
                ]);
    }

    /**
     * @Route("/{id}/edit", name="norma_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
        switch ($norma->getTipoNorma()->getNombre()){
            case 'Decreto':
                $form = $this->createForm(DecretoTypeEdit::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ordenanza':
                $form = $this->createForm(OrdenanzaTypeEdit::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Resolucion':
                $form = $this->createForm(ResolucionTypeEdit::class, $norma);
                $form->handleRequest($request);
            break;
            case 'Ley':
                $form = $this->createForm(LeyTypeEdit::class, $norma);
                $form->handleRequest($request);
            break;
            default:
            $form = $this->createForm(CircularTypeEdit::class, $norma);
            $form->handleRequest($request);
            break;
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            

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
            $brochureFile = $form->get('archivo')->getData();

            if ($brochureFile) {
                foreach ($brochureFile as $unArchivo) {
                    $originalFilename = pathinfo($unArchivo->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$unArchivo->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $unArchivo->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $archi=new ArchivoPdf();
                    $archi->setRuta($newFilename);
                    $archi->setNorma($norma);
                    //$nombreArchivo=$norma->getTipoNorma()->getNombre()."N°".$norma->getNumero();
                    // dd($nombreArchivo);
                    $archi->setNombre($originalFilename);

                    

                    $entityManager->persist($archi);
                    $norma->addArchivosPdf($archi);
                }
            }

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

            // if($norma->getRela()==true){
                
            //     $id=$norma->getId();
            //     $session=$request->getSession();
            //     $session->set('id',$id);
                
            //     return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            // }
            
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
            $complementada=$repository->findByComplementada($id);
        
            return $this->render('norma/normaShowArbol.html.twig', [
                'item' => $item,
                'norma' => $norma,
                'relacion' => $relacion,
            ]);
        }
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
