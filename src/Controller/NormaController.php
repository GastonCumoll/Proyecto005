<?php

namespace App\Controller;


use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Item;
use App\Entity\Norma;
use App\Form\LeyType;
use App\Entity\Archivo;
use App\Form\NormaType;
use App\Entity\Etiqueta;
use App\Entity\Relacion;
use App\Entity\TipoNorma;
use App\Form\DecretoType;
use App\Form\LeyTypeEdit;
use App\Form\BusquedaType;
use App\Form\CircularType;
use App\Form\RelacionType;
use App\Form\OrdenanzaType;
use App\Form\TextoEditType;
use App\Form\TipoNormaType;
use App\Form\ResolucionType;
use App\Form\DecretoTypeEdit;
use App\Form\CircularTypeEdit;
use App\Form\OrdenanzaTypeEdit;
use App\Form\ResolucionTypeEdit;
use App\Service\SeguridadService;
use App\Repository\ItemRepository;
use App\Repository\NormaRepository;
use App\Repository\ArchivoRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\EventSubscriber\SecuritySubscriber;
use Doctrine\Common\Collections\Collection;
use Knp\Component\Pager\PaginatorInterface;
use Sasedev\MpdfBundle\Factory\MpdfFactory;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @Route("/settipo", name="settipo", methods={"GET"})
     */
    public function settipo(NormaRepository $normaRepository,EntityManagerInterface $entityManager,TipoNormaRepository $tipoNormaRepository)
    {
        $normas=$normaRepository->findAll();
        $tipoLey=$tipoNormaRepository->find(2);
        $tipoOrd=$tipoNormaRepository->find(3);
        $tipoCir=$tipoNormaRepository->find(4);
        $tipoDir=$tipoNormaRepository->find(5);
        $tipoDis=$tipoNormaRepository->find(6);
        foreach ($normas as $unaNorma) {
            $titulo=$unaNorma->getTitulo();
            $primeros30Caracteres=substr($titulo,0,30);
            if(str_contains($primeros30Caracteres,"ORDENANZA") || str_contains($primeros30Caracteres,"Ordenanza") || str_contains($primeros30Caracteres,"ordenanza")){
                $unaNorma->setTipoNorma($tipoOrd);
                $entityManager->persist($unaNorma);
            }
            if(str_contains($primeros30Caracteres,"Directiva") || str_contains($primeros30Caracteres,"Directiva") || str_contains($primeros30Caracteres,"directiva")){
                $unaNorma->setTipoNorma($tipoDir);
                $entityManager->persist($unaNorma);
            }
            if(str_contains($primeros30Caracteres,"LEY") || str_contains($primeros30Caracteres,"Ley") || str_contains($primeros30Caracteres,"ley")){
                $unaNorma->setTipoNorma($tipoLey);
                $entityManager->persist($unaNorma);
            }
            if(str_contains($primeros30Caracteres,"CIRCULAR") || str_contains($primeros30Caracteres,"Circular") || str_contains($primeros30Caracteres,"circular")){
                $unaNorma->setTipoNorma($tipoCir);
                $entityManager->persist($unaNorma);
            }
            if(str_contains($primeros30Caracteres,"DISPOSICION") || str_contains($primeros30Caracteres,"Disposicion") || str_contains($primeros30Caracteres,"disposicion")|| str_contains($primeros30Caracteres,"DISPOSICIONES") || str_contains($primeros30Caracteres,"Disposiciones") || str_contains($primeros30Caracteres,"disposiciones")){
                $unaNorma->setTipoNorma($tipoDis);
                $entityManager->persist($unaNorma);
            }
        }
        $entityManager->flush();
        dd($normas);
    }

    /**
     * @Route("/", name="norma_index", methods={"GET"})
     */
    public function index(NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
    {   
        //$todasNormas=$normaRepository->findAllQuery();
        $todasNormas=$normaRepository->createQueryBuilder('p')
        ->getQuery();
        // Paginar los resultados de la consulta
        $normas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todasNormas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        return $this->render('norma/indexAdmin.html.twig', [
            'rol' => $rol,
            'normas' => $normas,
            'tipoNormas' => $tipoNorma->findAll(),
            'etiquetas' =>$etiquetas->findAll(),
        ]);
    }

    /**
     * @Route("/{palabra}/busquedaRapida", name="busqueda_rapida", methods={"GET","POST"}, options={"expose"=true})
     */
    public function busquedaRapida(TipoNormaRepository $tipo,NormaRepository $normaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //dd($palabra);
        if($palabra=="-1"){
            $normasQuery=$normaRepository->createQueryBuilder('p')
            ->getQuery();   
        }else{
            $palabra=str_replace("§","/",$palabra);
            $normasQuery=$normaRepository->findUnaPalabraDentroDelTitulo($palabra);//ORMQuery
        }
        
        
        
        //$palabra es el string que quiero buscar
        

        //$todasNormas=$normasQuery->getResult();//getResult()convierte el query en un array == ->execute();
        //$todasNormas=array_unique($todasNormas);
        //dd($todasNormas);

        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }

        // Paginar los resultados de la consulta
        $normas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $normasQuery,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('norma/indexAdmin.html.twig', [
            
            'normas' => $normas,
            'rol' => $rol,
            'tipoNormas' => $tipo->findAll(),

        ]);
        
    }

    /**
     * @Route("/{palabra}/busquedaParam", name="busqueda_param", methods={"GET","POST"}, options={"expose"=true})
     */
    public function busquedaParam(NormaRepository $normaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator,TipoNormaRepository $tipoNorma):Response
    {
        //dd($palabra);
        
        $palabra=str_replace("§","/",$palabra);
        
        // 
        //$palabra es el string que quiero buscar
        $todasNormas=$normaRepository->findUnaPalabraDentroDelTitulo($palabra);//array
        $todasNormas=array_unique($todasNormas);

        // Paginar los resultados de la consulta
        $normas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todasNormas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        $cant=count($normas);
        return $this->render('norma/indexAdmin.html.twig', [
            'rol' => $rol,
            'normas' => $normas,
            
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
     * @Route("/busquedaFiltro", name="busqueda_filtro", methods={"GET","POST"})
     */
    public function busquedaFiltro(PaginatorInterface $paginator,TipoNormaRepository $tipoNormaRepository,EtiquetaRepository $etiquetaRepository ,NormaRepository $normaRepository,Request $request,SeguridadService $seguridad):Response
    {   
        $titulo=$request->query->get('titulo');//string
        $tipo=$request->query->get('tipoNorma');//string
        $numero=$request->query->get('numero');//string
        $año=$request->query->get('año');//string
        //$etiquetas=$request->query->get('etiquetas'); //etiquetas en matenimiento por el momento
        //dd($titulo);
        $arrayDeEtiquetas=[];
        $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeEtiquetas);
        
        $sesion=$this->get('session');
                $idSession=$sesion->get('session_id')*1;
                if($seguridad->checkSessionActive($idSession)){
                    
                    // dd($idSession);
                    $roles=json_decode($seguridad->getListRolAction($idSession), true);
                    // dd($roles);
                    $rol=$roles[0]['id'];
                    // dd($rol);
                }else {
                    $rol="";
                }

                //seccion paginator
                // Paginar los resultados de la consulta
                $normasP = $paginator->paginate(
                // Consulta Doctrine, no resultados
                $normas,
                // Definir el parámetro de la página
                $request->query->getInt('page', 1),
                // Items per page
                10
                );
                return $this->renderForm('norma/indexAdmin.html.twig', [
                    'etiquetas' => $etiquetaRepository->findAll(),
                    'tipoNormas' =>$tipoNormaRepository->findAll(),
                    'normas' => $normasP,
                    'rol' => $rol,
                ]);
    }

    /**
     * @Route("/formularioBusqueda", name="formulario_busqueda", methods={"GET","POST"})
     */
    public function formBusqueda(EtiquetaRepository $etiquetaRepository, TipoNormaRepository $tipoNormaRepository, SeguridadService $seguridad):Response
    {
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        return $this->render('busqueda/formBusqueda.html.twig', [
            'etiquetas' => $etiquetaRepository->findAll(),
            'tipoNormas' =>$tipoNormaRepository->findAll(),
            'rol' => $rol,
            //'form' =>$form,
        ]);
    }

    /**
     * @Route("/formularioBusquedaResult", name="formulario_busqueda_result", methods={"GET","POST"})
     */
    public function formBusquedaResult(Request $request,NormaRepository $normaRepository,PaginatorInterface $paginator,EtiquetaRepository $etiquetaRepository, TipoNormaRepository $tipoNormaRepository, SeguridadService $seguridad):Response
    {
        $titulo=$request->query->get('titulo');
        $tipo=$request->query->get('tipoNorma');//string
        $numero=$request->query->get('numero');//string
        $año=$request->query->get('año');//string
        //if(!$request->request->get('etiquetas')){
        $etiquetas=$request->query->get('etiquetas');
        //dd($etiquetas);
        //}
            //$etiquetas[0]="";
        
        //dd($etiquetas);
        $arrayDeNormas=[];
        if($etiquetas!= null){
            //if(count($etiquetas)>1){
                $etiquetasObj=[];
                for ($i=0; $i <count($etiquetas) ; $i++) {
                   //$tamNormas=count($etiquetas[$i]->getNormas());
                    $etiquetasObj[$i]=$etiquetaRepository->findById($etiquetas[$i]);
                    //dd($etiquetasObj[$i]);
                    foreach ($etiquetasObj[$i][0]->getNormas() as $unaNorma) {
                        $arrayDeNormas[]=$unaNorma;
                    }
                }
            //}
        }
        
        

        //dd($arrayDeNormas);
        
        //$etiquetas=$request->query->get('etiquetas'); //etiquetas en matenimiento por el momento ¿porque no me trae un array?
        $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas);
        //dd($normas->getResult());
        //seccion paginator
        // Paginar los resultados de la consulta
        $normasP = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $normas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        return $this->render('norma/indexAdmin.html.twig', [
            'etiquetas' => $etiquetaRepository->findAll(),
            'tipoNormas' =>$tipoNormaRepository->findAll(),
            'normas' => $normasP,
            'rol' => $rol,
        ]);
    }

    /**
     * @Route("/busquedaAvanzada", name="busqueda_avanzada", methods={"GET","POST"})
     */
    public function busquedaAvanzada(PaginatorInterface $paginator,TipoNormaRepository $tipoNormaRepository,EtiquetaRepository $etiquetaRepository ,NormaRepository $normaRepository,Request $request,SeguridadService $seguridad)
    {
        //$variablePost = $request->request->get('titulo');  // $_POST[]
        //dd($variablePost);
        //$variableGet = $request->query->get('titulo'); // $_GET[]

        // if ($variableGet)
        //     $resultado = $repo->findAlgunos($variableGet);
        // else
        //     $resultado = $repo->findTodos();

        $form = $this->createForm(BusquedaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $titulo = $form->get('titulo')->getData();
            $tipo = $form->get('tipo')->getData();
            $numero = $form->get('numero')->getData();
            $año = $form->get('anio')->getData();
            $etiquetasDeForm = $form['etiquetas']->getData();
            dd($etiquetasDeForm);
            

            $normita=$normaRepository->findNormas($titulo,$numero,$año,$tipo->getId());//FIND NORMAS= METODO EN EL REPOSITORIO PARA BUSCAR LAS NORMAS DE A CUERDO A LOS PARAMETROS;
            //dd($normita->getResult());//->getResult() para convertir el ORM Query($normita) a array;

            //BUSQEUDA VIEJA---------------------------------------------------------------------------
            //seccion etiquetas
            // if($etiquetasDeForm[0]!=null){
            //     foreach ($etiquetasDeForm as $e) {
            //         $nombreEtiqueta[]=$e->getNombre();
            //     }

            //         $arrayEtiquetas=[];
            //         foreach ($nombreEtiqueta as $unaEtiquetaSeparada) {
            //             //$unaEtiquetaSeparada=trim($unaEtiquetaSeparada);
            //             $arrayEtiquetas=array_merge($arrayEtiquetas,$etiquetaRepository->findUnaEtiqueta($unaEtiquetaSeparada));
                        
            //             foreach ($arrayEtiquetas as $unaEtiqueta) {
            //                 $normasDeUnaEtiqueta=$unaEtiqueta->getNormas()->toArray();
                            
            //                 $normasEtiquetasMerged=array_merge($normasDeUnaEtiqueta,$normasEtiquetasMerged);
            //             }
            //         }
            // }
            
            // $normasEtiquetasMerged=array_unique($normasEtiquetasMerged);
            // //seccion tipo
            // if($tipo != null){
            //     $tipoNorma=$tipoNormaRepository->findOneByNombre($tipo->getNombre());
            //     //dd($tipoNorma);
            //     $nTipo=$tipoNorma->getNormas()->toArray();
            // }


            // if(($titulo != null)){
            //     //array_merge($norma,$normaRepository->findUnaPalabraDentroDelTitulo($titulo));

            //     $nTitulo=$normaRepository->findUnaPalabraDentroDelTitulo($titulo);
            //     $normas=$nTitulo;
            //     if(($tipo!=null)){
                    
            //         $normas=array_intersect($normas,$nTipo);
            //     }
            //     if($numero!=null){
            //         $nNumero=$normaRepository->findUnNumero($numero);
            //         $normas=array_intersect($normas,$nNumero);
            //     }
            //     if($año != null){
            //         $nAño=$normaRepository->findUnAño($año);
            //         $normas=array_intersect($normas,$nAño);
            //     }
            //     if($normasEtiquetasMerged!=null){
            //         $normas=array_intersect($normas,$normasEtiquetasMerged);
            //     }
            // }
            // else{
            //         if(($tipo!=null)){
                        
            //             $normas=$nTipo;
            //             if($numero!=null){
            //                 $nNumero=$normaRepository->findUnNumero($numero);
            //                 $normas=array_intersect($normas,$nNumero);
            //             }
            //             if($año != null){
            //                 $nAño=$normaRepository->findUnAño($año);
            //                 $normas=array_intersect($normas,$nAño);
            //             }
            //             if($normasEtiquetasMerged!=null){
            //                 $normas=array_intersect($normas,$normasEtiquetasMerged);
            //             }
            //     }
            //     else{
            //             if($numero!=null){
            //                 $nNumero=$normaRepository->findUnNumero($numero);
            //                 $normas=$nNumero;
            //                 if($año != null){
            //                     $nAño=$normaRepository->findUnAño($año);
            //                     $normas=array_intersect($normas,$nAño);
            //                 }
            //                 if($normasEtiquetasMerged!=null){
            //                     $normas=array_intersect($normas,$normasEtiquetasMerged);
            //                 }
            //             }
            //             else{
            //                 if($año != null){
            //                     $nAño=$normaRepository->findUnAño($año);
            //                     $normas=$nAño;
            //                 }
            //                 else{
            //                     if($normasEtiquetasMerged!=null){
            //                         $normas=$normasEtiquetasMerged;
            //                     }
            //                 }
            //             }
                        
            //         }
                    
            //     }
            //-------------------------------------------------------------------------------------------

                $sesion=$this->get('session');
                $idSession=$sesion->get('session_id')*1;
                if($seguridad->checkSessionActive($idSession)){
                    
                    // dd($idSession);
                    $roles=json_decode($seguridad->getListRolAction($idSession), true);
                    // dd($roles);
                    $rol=$roles[0]['id'];
                    // dd($rol);
                }else {
                    $rol="";
                }
                
                // Paginar los resultados de la consulta
            $normas = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $normita,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        //return $this->redirectToRoute('busqueda_avanzada_result', ['normas' => $normas], Response::HTTP_SEE_OTHER);
            return $this->renderForm('norma/indexAdmin.html.twig', [
                'etiquetas' => $etiquetaRepository->findAll(),
                'tipoNormas' =>$tipoNormaRepository->findAll(),
                'normas' => $normas,
                'rol' => $rol,
            ]);
        }
        
        return $this->renderForm('busqueda/busquedaAvanzada.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/mostrarPDF", name="mostrar_pdf")
     */

    public function mostrarPdf(EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoRepository $archivoRepository ,$id, MpdfFactory $MpdfFactory): Response
    {
       
        $norma=$normaRepository->find($id);
        $normaNombre=$norma->getTitulo();
        $tipoNorma=$norma->getTipoNorma()->getNombre();
        // $options = new Options();
        // $options->set('isRemoteEnabled',false);
        // $options->set('isHtml5ParserEnable',true);
        // // $options->set('defaultFont','helvetica');
        // // $bp='/var/www/vhosts/proyectodigesto/public';
        // //$options->set('chroot','C:/xampp/htdocs/proyectodigesto/public');
        // // Crea una instancia de Dompdf
        // $dompdf = new Dompdf($options);
        //$dompdf->getOptions()->setChroot('C:\\xampp\\htdocs\\proyectoDigesto\\public');

       
        // $dompdf->getOptions()->set([
        //     'defaultFont' => 'helvetica',
        //     'chroot' => '/var/www/proyectodigesto/public/upload',
        // ]);



        $today = new DateTime();
        $result = $today->format('d-m-Y H:i:s');
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        //dd($html);
        $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);
        $posicion=strpos($htmlModificado,'?');
        $posicion2=strpos($htmlModificado,'=es');
        $cadenaAEliminar=substr($htmlModificado,$posicion,$posicion2-$posicion+3);
        //dd($cadenaAEliminar);
        $mod = str_replace($cadenaAEliminar,"",$htmlModificado);
        //dd($mod);
        $mPdf = $MpdfFactory->createMpdfObject([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 5,
            'margin_footer' => 5,
            'orientation' => 'P'
            ]);
            $mPdf->WriteHTML($mod);
            //return $MpdfFactory->createDownloadResponse($mPdf, "file.pdf");
            $mPdf -> Output('','I');
        exit;

        // dd($html);
        //$data = "https://localhost:8000/upload/e2a2c396d083cacb969c5156a12a629f5ea37e42.jpg";

        //$ruta='<img src="localhost:8000';
        // Cargar HTML en Dompdf
        // $html5=str_replace('<img src="',$ruta,$html);
        //$html5=str_replace('/upload/e2a2c396d083cacb969c5156a12a629f5ea37e42.jpg',$data,$html);
        // dd($html5);
        //$dompdf->loadHtml($html);
        //$dompdf->loadHtml($html);
        // dd($retorno);

        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
       
        //$dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        //$dompdf->render();

       
        // Envíe el PDF generado al navegador (descarga forzada)
        //$dompdf->stream($tipoNorma."-".$normaNombre."-MODIFICADA-".$result."-.pdf", [
          //  "Attachment" => false
        //]);
       
        // return $this->redirectToRoute('norma_edit', ['id' =>$id], Response::HTTP_SEE_OTHER);
        //exit(1);
    }

    /**
     * @Route("/{id}/generarPDF", name="generar_pdf")
     */
    public function generarPdf(EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoRepository $archivoRepository , $id, MpdfFactory $MpdfFactory): Response
    {
        $norma=$normaRepository->find($id);
        $normaNombre=$norma->getTitulo();
        $normaNombreLimpio=str_replace("/","-",$normaNombre);//reemplaza / por - asi puede guardarlo

       

        //$options = new Options();
        //$options->set('isRemoteEnabled',true);
        //$options->setIsHtml5ParserEnabled(true);
       
        // Crea una instancia de Dompdf
        //$dompdf = new Dompdf($options);
        $today = new DateTime();
        $result = $today->format('d-m-Y H-i-s');

       
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);
        $mod = str_replace('?conf=images&amp;module=ckeditor&amp;CKEditor=decreto_texto&amp;CKEditorFuncNum=3&amp;langCode=es',"",$htmlModificado);
        // dd($html);

        $mPdf = $MpdfFactory->createMpdfObject([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 5,
            'margin_footer' => 5,
            'orientation' => 'P'
            ]);
            $mPdf->WriteHTML($mod);
            //return $MpdfFactory->createDownloadResponse($mPdf, "file.pdf");
           
       
       
        // Cargar HTML en Dompdf
        //$dompdf->loadHtml($html);
       
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        //$dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        //$dompdf->render();

        // Store PDF Binary Data
        //$output = $dompdf->output();
       
        // In this case, we want to write the file in the public directory
        $publicDirectory = 'uploads/pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $nombre='/'.$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
        $ruta='pdf/'.$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
       
       
        $pdfFilepath =  $publicDirectory . $nombre;
       
        // Write file to the desired path
        $output = $mPdf -> Output($nombre,'S');
        file_put_contents($pdfFilepath, $output);
       

        $archi=new Archivo();
        $archi->setNorma($norma);
        $archi->setRuta($ruta);
        $archi->setNombre($normaNombre);
       
        $archivos=$archivoRepository->findByNorma($id);
        foreach ($archivos as $unArchi) {
            if($unArchi->getRuta()==$ruta){
                $entityManager->remove($unArchi);
            }
        }
       
        $entityManager->persist($archi);
        $norma->addArchivos($archi);
        $entityManager->persist($norma);
        $entityManager->flush();

        // Envíe el PDF generado al navegador (descarga forzada)
        // $dompdf->stream("Norma-".$normaNombre."-MODIFICADA-".$result."-.pdf", [
        //     "Attachment" => false
        // ]);
       
        return $this->redirectToRoute('texto_edit', ['id' =>$id], Response::HTTP_SEE_OTHER);
        //exit(1);
        exit;
    }

    /**
     * @Route("{id}/mostrarTexto", name="mostrar_texto", methods={"GET"})
     */
    public function mostrarTexto(NormaRepository $normaRepository ,$id,EntityManagerInterface $entityManager): Response
    {

        $norma=$normaRepository->find($id);
        $texto=$norma->getTexto();


        return $this->render('norma/mostrarTexto.html.twig', [
            'texto' =>$texto,
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
            //dd($form['etiquetas']->getData());
            //dd($form->get('archivo')->getData());
            $today = new DateTime();
            $norma->setFechaPublicacion($today);
            $norma->setEstado("Borrador");

            
            
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
                    $carpeta=$unArchivo->guessExtension();
                    //dd($unArchivo->guessExtension());
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
                    
                    //dd($newFilename);
                    $newFilename=$carpeta.'/'.$newFilename;
                    //dd($newFilename);
                    $archi=new Archivo();
                    $archi->setRuta($newFilename);
                    $archi->setNorma($norma);
                    $archi->setNombre($originalFilename);
                    $archi->setTipo($carpeta);

                    

                    $entityManager->persist($archi);
                    //dd($archi);
                    $norma->addArchivos($archi);
                    $entityManager->persist($norma);
                }
            }
            //si permitimos la creacion de una nueva etiqueta dentro de el alta de norma:
            //$etiquetas = explode(",", $form['nueva_etiqueta']->getData());
            //se almacena en la variable $etiquetas las etiquetas ingresadas en el formulario, se las separa con la función explode por comas y se las guarda en un array
            /*foreach ($etiquetas as $unaEtiqueta) {
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
                
            }*/
            $etiquetasDeNorma=$form['etiquetas']->getData();
            
            //foreach para asignarle nuevas etiquetas ya creadas a Norma
            if($etiquetasDeNorma != null){
                foreach ($etiquetasDeNorma as $eti) {
                $eti->addNorma($norma);
                $norma->addEtiqueta($eti);
                $entityManager->persist($eti);
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
    public function show(Norma $norma,$id,Request $request, SeguridadService $seguridad): Response
    {
        
        $repository = $this->getDoctrine()->getRepository(Relacion::class);
        $relacion= $repository->findByNorma($id);
        
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        // dd($rol);
        return $this->render('norma/show.html.twig', [
            'norma' => $norma,
            'relacion' => $relacion,
            'rol'=>$rol,
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
                    'id' => $id,
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
                    $carpeta=$unArchivo->guessExtension();

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
                    $newFilename=$carpeta.'/'.$newFilename;
                    $archi=new Archivo();
                    $archi->setTipo($carpeta);
                    $archi->setRuta($newFilename);
                    $archi->setNorma($norma);
                    //$nombreArchivo=$norma->getTipoNorma()->getNombre()."N°".$norma->getNumero();
                    // dd($nombreArchivo);
                    $archi->setNombre($originalFilename);

                    

                    $entityManager->persist($archi);
                    $norma->addArchivos($archi);
                }
            }

            //si habilitamos crear etiquetas en el alta de la norma:
            //$etiquetas = explode(", ", $form['nueva_etiqueta']->getData());
            // $etiquetaRepository= $this->getDoctrine()->getRepository(Etiqueta::class);
            // foreach ($etiquetas as $unaEtiqueta) {
            //     $etiquetaSinEspacios="";
            //     for($i=0; $i<strlen($unaEtiqueta) ;$i++) {
            //             if(($unaEtiqueta[$i]==" " && $unaEtiqueta[$i-1]!=" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]==" ") || ($unaEtiqueta[$i]!=" " && $unaEtiqueta[$i-1]!=" ")){
            //                 $etiquetaSinEspacios.=$unaEtiqueta[$i];
            //             }
            //         }
            // if(!$etiquetaRepository->findOneBy(['nombre' => $etiquetaSinEspacios]))
            // {
            //     $etiquetaNueva = new Etiqueta();
            //     $etiquetaNueva->setNombre($etiquetaSinEspacios);
            //     $etiquetaNueva->addNorma($norma);
            //     $norma->addEtiqueta($etiquetaNueva);
            //     $entityManager->persist($etiquetaNueva);
            // }
            //     $entityManager->persist($norma);   
            // }
             
            $etiquetasDeNorma=$form['etiquetas']->getData();
            //foreach para asignarle nuevas etiquetas ya creadas a Norma
            foreach ($etiquetasDeNorma as $eti) {
                $eti->addNorma($norma);
                $norma->addEtiqueta($eti);
                $entityManager->persist($eti);
            }
            $entityManager->persist($norma);
            $entityManager->flush();

            // if($norma->getRela()==true){
                
            //     $id=$norma->getId();
            //     $session=$request->getSession();
            //     $session->set('id',$id);
                
            //     return $this->redirectToRoute('form_rela', [], Response::HTTP_SEE_OTHER);
            // }
            
            return $this->redirectToRoute('norma_show', ['id'=>$norma->getId()], Response::HTTP_SEE_OTHER);
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
