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
use App\Entity\Auditoria;
use App\Entity\TipoNorma;
use App\Form\ArchivoType;
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
use App\Repository\AreaRepository;
use App\Repository\ItemRepository;
use App\Repository\NormaRepository;
use App\Repository\ArchivoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\AuditoriaRepository;
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

    //funcion para cambiar la base de datos pero usando php
    /**
     * @Route("/settipo", name="settipo", methods={"GET"})
     */
    public function settipo(NormaRepository $normaRepository,EntityManagerInterface $entityManager,TipoNormaRepository $tipoNormaRepository)
    {
        /*$normas=$normaRepository->findAll();
        $tipoLey=$tipoNormaRepository->find(2);
        $tipoOrd=$tipoNormaRepository->find(3);
        $tipoCir=$tipoNormaRepository->find(4);
        $tipoDir=$tipoNormaRepository->find(5);
        $tipoDis=$tipoNormaRepository->find(6);
        $tipoRes=$tipoNormaRepository->find(7);
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
            if(str_contains($primeros30Caracteres,"RESOLUCION") || str_contains($primeros30Caracteres,"Resolucion") || str_contains($primeros30Caracteres,"resolucion")|| str_contains($primeros30Caracteres,"DISPOSICIONES") || str_contains($primeros30Caracteres,"Disposiciones") || str_contains($primeros30Caracteres,"disposiciones")){
                $unaNorma->setTipoNorma($tipoRes);
                $entityManager->persist($unaNorma);
            }
        }
        $entityManager->flush();
        dd($normas);*/
    }

    /**
     * @Route("/", name="norma_index", methods={"GET"})
     */
    public function index(NormaRepository $normaRepository,SeguridadService $seguridad,Request $request, PaginatorInterface $paginator, TipoNormaRepository $tipoNorma, EtiquetaRepository $etiquetas, AreaRepository $areaRepository): Response
    {   
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        //si idSession = 0(no hay nadie logeado), lo toma como falso y entra al else
        //dd($idSession);
        if($idSession){
            $todasNormas=$normaRepository->findAllQueryS();
        }else{
            $todasNormas=$normaRepository->findAllQuery();//query con join de tipoNorma
        }
        
        // $todasNormas=$normaRepository->createQueryBuilder('p')
        // ->getQuery();
        // Paginar los resultados de la consulta
        $normas = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $todasNormas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $normas->setCustomParameters([
            'align' => 'center',
        ]);

        // $sesion=$this->get('session');
        // $idSession=$sesion->get('session_id')*1;

        
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
            //dd($reparticionUsuario);

            $normasUsuario = [];
            //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
            foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
            }

        }else{
            $normasUsuario="";
        }
        
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
            'normasUsuario' => $normasUsuario,
        ]);
    }

    
    /**
     * @Route("/trayecto/{id}", name="trayecto_norma")
     */
    public function trayectoNorma(PaginatorInterface $paginator,AuditoriaRepository $auditoriaRepository,NormaRepository $normaRepository,EntityManagerInterface $entityManager,Request $request,$id){
        //trayecto de la norma
        $norma=$normaRepository->findById($id);
        $auditorias=$auditoriaRepository->findByNorma($norma);
        
        $auditoriasDeNorma = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $auditorias,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $auditoriasDeNorma->setCustomParameters([
            'align' => 'center',
        ]);
        return $this->render('norma/trayecto.html.twig', [
            'auditoriasDeNorma' => $auditoriasDeNorma,
        ]);
    }

    /**
     * @Route("/updateInstancia/{id}", name="updateInstancia")
     */
    public function updateInstancia(EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request,$id)
    {
        $norma=$normaRepository->find($id);
        $estadoNorma=$norma->getEstado();
        $today=new DateTime();

        //obtener el nombre del usuario logeado;
        $session=$this->get('session');
        $usuario=$session->get('username');

        $auditoria=new Auditoria();

        $auditoria->setNorma($norma);
        $auditoria->setNombreUsuario($usuario);
        $auditoria->setFecha($today);

        if($estadoNorma=="Borrador"){
            $auditoria->setInstanciaAnterior($norma->getInstancia());
            $auditoria->setInstanciaActual($norma->getInstancia()+1);
            $auditoria->setEstadoAnterior($norma->getEstado());
            $norma->setEstado("Lista");
            $norma->setInstancia(2);
            $auditoria->setEstadoActual("Lista");
            $auditoria->setAccion("Revision");
            $entityManager->persist($auditoria);
            $norma->addAuditoria($auditoria);
            //$userObj->addAuditoria($auditoria);
            $entityManager->persist($norma);
            //$entityManager->persist($userObj);
            $entityManager->flush();

        return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
        }
        if($estadoNorma=="Lista"){
            $auditoria->setInstanciaAnterior($norma->getInstancia());
            $auditoria->setInstanciaActual($norma->getInstancia()+1);
            $auditoria->setEstadoAnterior($norma->getEstado());
            $auditoria->setEstadoActual("Publicada");
            $norma->setEstado("Publicada");
            $norma->setInstancia(3);
            $auditoria->setAccion("Publicacion");
            $entityManager->persist($auditoria);
            $entityManager->persist($norma);
            //$entityManager->persist($userObj);
            $entityManager->flush();

            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
        }
        if($estadoNorma=="Publicada"){
            $auditoria->setInstanciaAnterior($norma->getInstancia());
            $auditoria->setInstanciaActual(1);
            $auditoria->setEstadoAnterior($norma->getEstado());
            $auditoria->setEstadoActual("Borrador");
            $norma->setEstado("Borrador");
            $norma->setInstancia(1);
            $auditoria->setAccion("Vuelta a borrador");
            $entityManager->persist($auditoria);
            $entityManager->persist($norma);
            $entityManager->flush();

            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
        }
        //$entityManager->flush();

        return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/listas", name="listas", methods={"GET"})
     */
    public function listas(AreaRepository $areaRepository,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
    {

        $listaDeRolesUsuario;
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $listaDeRolesUsuario[]= $unRol["id"];
            }
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        $reparticionUsuario = $areaRepository->find($idReparticion);


        $normasUsuario = [];
        //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
        foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
            $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
        }

        $listas=$normaRepository->findListas($listaDeRolesUsuario);
        //dd($borradores);

        $normasListas = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $listas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $normasListas->setCustomParameters([
            'align' => 'center',
        ]);
        
        return $this->render('norma/indexAdmin.html.twig', [
            'rol' => $rol,
            'normas' => $normasListas,
            'tipoNormas' => $tipoNorma->findAll(),
            'etiquetas' =>$etiquetas->findAll(),
            'normasUsuario' => $normasUsuario,
        ]);
    }

    /**
     * @Route("/borrador", name="borrador", methods={"GET"})
     */
    public function borrador(AreaRepository $areaRepository,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
    {
        $listaDeRolesUsuario;
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $listaDeRolesUsuario[]= $unRol["id"];
            }
            // dd($roles);
            $rol=$roles[0]['id'];
            //dd($rol);
        }else {
            $rol="";
        }
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        $reparticionUsuario = $areaRepository->find($idReparticion);


        $normasUsuario = [];
        //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
        foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
            $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
        }
        //dd($listaDeRolesUsuario);
        $borradores=$normaRepository->findBorradores($listaDeRolesUsuario);
        //dd($borradores);

        $normasBorrador = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $borradores,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $normasBorrador->setCustomParameters([
            'align' => 'center',
        ]);
        


        return $this->render('norma/indexAdmin.html.twig', [
            'rol' => $rol,
            'normas' => $normasBorrador,
            'tipoNormas' => $tipoNorma->findAll(),
            'etiquetas' =>$etiquetas->findAll(),
            'normasUsuario' => $normasUsuario,
        ]);
    }

    /**
     * @Route("/{palabra}/busquedaRapida", name="busqueda_rapida", methods={"GET","POST"}, options={"expose"=true})
     */
    public function busquedaRapida(AreaRepository $areaRepository,TipoNormaRepository $tipo,NormaRepository $normaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //dd($palabra);
        if($palabra=="-1"){
            $normasQuery=$normaRepository->createQueryBuilder('p')
            ->getQuery();   
        }else{
            $palabra=str_replace("§","/",$palabra);
            $normasQuery=$normaRepository->findUnaPalabraDentroDelTitulo($palabra);//ORMQuery
        }

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
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);


            $normasUsuario = [];
            //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
            foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
            }

        }else{
            $normasUsuario="";
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
        $normas->setCustomParameters([
            'align' => 'center',
        ]);
        return $this->render('norma/indexAdmin.html.twig', [
            
            'normas' => $normas,
            'rol' => $rol,
            'tipoNormas' => $tipo->findAll(),
            'normasUsuario' => $normasUsuario,
        ]);
        
    }

    /**
     * @Route("/{id}/normasAjax", name="normas_ajax", methods={"GET"}, options={"expose"=true})
     */
    public function normasAjax(NormaRepository $normaRepository,ItemRepository $itemRepository,$id): Response
    {
        //normasAjax metodo para buscar normas ligadas a los items
        $item=$itemRepository->find($id);
        $normas=$item->getNormas()->toArray();
        
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
    }

    /**
     * @Route("/busquedaFiltro", name="busqueda_filtro", methods={"GET","POST"})
     */
    public function busquedaFiltro(AreaRepository $areaRepository,PaginatorInterface $paginator,TipoNormaRepository $tipoNormaRepository,EtiquetaRepository $etiquetaRepository ,NormaRepository $normaRepository,Request $request,SeguridadService $seguridad):Response
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
                $idReparticion = $seguridad->getIdReparticionAction($idSession);

                if($idReparticion){
                    $reparticionUsuario = $areaRepository->find($idReparticion);
        
        
                    $normasUsuario = [];
                    //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
                    foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                        $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
                    }
        
                }else{
                    $normasUsuario="";
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
                    'normasUsuario' => $normasUsuario,
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
    public function formBusquedaResult(AreaRepository $areaRepository,Request $request,NormaRepository $normaRepository,PaginatorInterface $paginator,EtiquetaRepository $etiquetaRepository, TipoNormaRepository $tipoNormaRepository, SeguridadService $seguridad):Response
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
        

        //dd($arrayDeNormas);
        
        //$etiquetas=$request->query->get('etiquetas'); //etiquetas en matenimiento por el momento ¿porque no me trae un array?
        $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas);
        //dd($normas);
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
        $normasP->setCustomParameters([
            'align' => 'center',
        ]);

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
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);


            $normasUsuario = [];
            //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
            foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
            }

        }else{
            $normasUsuario="";
        }
        return $this->render('norma/indexAdmin.html.twig', [
            'etiquetas' => $etiquetaRepository->findAll(),
            'tipoNormas' =>$tipoNormaRepository->findAll(),
            'normas' => $normasP,
            'rol' => $rol,
            'normasUsuario' => $normasUsuario,
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

        $today = new DateTime();
        $result = $today->format('d-m-Y H:i:s');
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        //codigo para reemplazar /manager/file y despues del '?' para poder buscar las imagenes
        $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);

        $posicion=strpos($htmlModificado,'?');
        $posicion2=strpos($htmlModificado,'=es');

        if($posicion && $posicion2){
            $cadenaAEliminar=substr($htmlModificado,$posicion,$posicion2-$posicion+3);
            $mod = str_replace($cadenaAEliminar,"",$htmlModificado);
        }
        else{
            $mod=$htmlModificado;
        }

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
    }

    /**
     * @Route("/{id}/generarPDF", name="generar_pdf")
     */
    public function generarPdf(EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoRepository $archivoRepository , $id, MpdfFactory $MpdfFactory): Response
    {
        $norma=$normaRepository->find($id);
        $normaNombre=$norma->getTitulo();
        $normaNombreLimpio=str_replace("/","-",$normaNombre);//reemplaza / por - asi puede guardarlo

        $today = new DateTime();
        $result = $today->format('d-m-Y H-i-s');
        $hoy = $today->format('d-m-Y');

        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);

        //codigo para reemplazar /manager/file y despues del '?' para poder buscar las imagenes
        $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);
        $mod = str_replace('?conf=images&amp;module=ckeditor&amp;CKEditor=decreto_texto&amp;CKEditorFuncNum=3&amp;langCode=es',"",$htmlModificado);
        
        $mPdf = $MpdfFactory->createMpdfObject([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 5,
            'margin_footer' => 5,
            'orientation' => 'P'
            ]);
        $mPdf->WriteHTML($mod);

        // In this case, we want to write the file in the public directory
        $publicDirectory = 'uploads/pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $salida='/'.$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
        $ruta='pdf/'.$normaNombreLimpio.'-MODIFICADA-'.$result.'-.pdf';
        $nombre=$normaNombreLimpio.'('.$hoy.')';
        $pdfFilepath =  $publicDirectory . $salida;

        // Write file to the desired path
        $output = $mPdf -> Output($salida,'S');
        file_put_contents($pdfFilepath, $output);

        $archi=new Archivo();
        $archi->setNorma($norma);
        $archi->setRuta($ruta);
        $archi->setNombre($nombre);
        $archi->setTipo("pdf");

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

        return $this->redirectToRoute('texto_edit', ['id' =>$id], Response::HTTP_SEE_OTHER);
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
    public function new(AreaRepository $areaRepository,SeguridadService $seguridad, Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository,$id, SluggerInterface $slugger): Response
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
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        $reparticionUsuario = $areaRepository->find($idReparticion);
        $normasUsuario = [];
        //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
        foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
            $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getId();
        }
        if(!in_array($id,$normasUsuario)){
            return $this->redirectToRoute('logout', ['bandera' => 3], Response::HTTP_SEE_OTHER); //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma, se lo desloguea
        }
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
            //dd($form->get('nombre_archivo','id')->getData());
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
                    if($form->get('nombre_archivo')->getData()){
                        $archi->setNombre($form->get('nombre_archivo')->getData());
                    }else{
                        $archi->setNombre($originalFilename);
                    }
                    
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
            //usuarios
            //obtener el nombre del usuario logeado;
            $session=$this->get('session');
            $usuario=$session->get('username');
            

            //crear auditoria
            $auditoria=new Auditoria();
            $auditoria->setFecha($today);
            $auditoria->setAccion("Carga");
            $auditoria->setInstanciaAnterior(0);
            $auditoria->setInstanciaActual(1);
            $auditoria->setEstadoActual("Borrador");
            $auditoria->setNombreUsuario($usuario);
            $auditoria->setNorma($norma);
            $entityManager->persist($auditoria);
            $norma->setInstancia(1);
            $norma->addAuditoria($auditoria);
            //$userObj->addAuditoria($auditoria);


            //setear instancia=1;
            $norma->setInstancia(1);
            $entityManager->persist($norma);
            //$entityManager->persist($userObj);


            $entityManager->flush();
            $idNorma=$norma->getId();
            return $this->redirectToRoute('borrador', [], Response::HTTP_SEE_OTHER);
            
        }
        
        return $this->renderForm('norma/new.html.twig', [
            'norma' => $norma,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/{id}/agregarArchivo", name="agregar_archivo", methods={"GET", "POST"})
     */
    public function agregarArchivo(Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
        $form = $this->createForm(ArchivoType::class, $norma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $brochureFile = $form->get('archivo')->getData();
            $nombreArchivo = $form->get('nombre')->getData();
            
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
                        $this->getParameter('brochures_directory'),$newFilename);
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
                    $archi->setNombre($nombreArchivo);
                    $archi->setTipo($carpeta);

                    $entityManager->persist($archi);
                    $norma->addArchivos($archi);
                    $today = new DateTime();
                    //usuarios
                    //obtener el nombre del usuario logeado;
                    $session=$this->get('session');
                    $usuario=$session->get('username');
                    
                    //crear auditoria
                    $auditoria=new Auditoria();
                    $auditoria->setFecha($today);
                    $auditoria->setAccion("Carga archivo");
                    $instancia=$norma->getInstancia();
                    $auditoria->setInstanciaAnterior($instancia);
                    $auditoria->setInstanciaActual(1);
                    $estadoAnt=$norma->getEstado();
                    $auditoria->setEstadoAnterior($estadoAnt);
                    $auditoria->setEstadoActual("Borrador");
                    $auditoria->setNombreUsuario($usuario);
                    $auditoria->setNorma($norma);
                    $entityManager->persist($auditoria);
                    $norma->setInstancia(1);
                    $norma->addAuditoria($auditoria);

                    //setear instancia=1;
                    $norma->setInstancia(1);
                    $entityManager->persist($norma);                        
                }
            }
            $entityManager->flush();
            return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('norma/agregarArchivo.html.twig', [
            'norma' => $norma,
            'form' => $form,
            'id' => $id,
        ]);
    }

    /**
     * @Route("/{id}", name="norma_show", methods={"GET"})
     */
    public function show(Norma $norma,$id,Request $request, SeguridadService $seguridad): Response
    {
        $repository = $this->getDoctrine()->getRepository(Relacion::class);
        $relacion= $repository->findByNorma($id);
        $auditoria=$norma->getAuditorias();
        $unUser='';
        
        foreach ($auditoria as $audi) {
            $unUser=$audi->getNombreUsuario();
        
        }
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $usuarioReparticion = 0; //variable que voy a usar en la vista para saber si el usuario es de la reparticíon de la norma

        $idReparticion = $seguridad->getIdReparticionAction($idSession);  //se obtiene la repartición del usuario logueado
        $reparticionesNorma = $norma->getTipoNorma()->getTipoNormaReparticions(); //se obtienen las reparticiones a las que pertenece ese tipo de norma a editar

        foreach($reparticionesNorma as $unaReparticion){
            if($unaReparticion->getReparticionId()->getId() == $idReparticion){
                $usuarioReparticion = 1;
            }
        }

        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }

        return $this->render('norma/show.html.twig', [
            'norma' => $norma,
            'relacion' => $relacion,
            'rol'=>$rol,
            'user' => $unUser,
            'usuarioReparticion' => $usuarioReparticion,
        ]);
    }

    /**
     * @Route("/{id}/editTexto", name="texto_edit", methods={"GET", "POST"})
     */
    public function editTexto(Request $request, SeguridadService $seguridad, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {   
        $session=$this->get('session');
        $session_id = $session->get('session_id') * 1;
        $idReparticion = $seguridad->getIdReparticionAction($session_id);  //se obtiene la repartición del usuario logueado
        $reparticionesNorma = $norma->getTipoNorma()->getTipoNormaReparticions(); //se obtienen las reparticiones a las que pertenece ese tipo de norma a editar

        foreach($reparticionesNorma as $unaReparticion){
            if($unaReparticion->getReparticionId()->getId() == $idReparticion){ //comparo el id de repartición del tipo de norma con el id de repartición del usuario logueado
                $form = $this->createForm(TextoEditType::class, $norma);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid())
                {
                    $entityManager->persist($norma);        
                    //usuarios
                    //obtener el nombre del usuario logeado;
                    $session=$this->get('session');
                    $usuario=$session->get('username');
                    $today=new DateTime();

                    //crear auditoria
                    $auditoria=new Auditoria();
                    $auditoria->setFecha($today);
                    $auditoria->setAccion("Modificacion texto");
                    $instancia=$norma->getInstancia();
                    $auditoria->setInstanciaAnterior($instancia);
                    $auditoria->setInstanciaActual(1);
                    $estadoAnt=$norma->getEstado();
                    $auditoria->setEstadoAnterior($estadoAnt);
                    $auditoria->setEstadoActual("Borrador");
                    $auditoria->setNombreUsuario($usuario);
                    $auditoria->setNorma($norma);
                    $entityManager->persist($auditoria);
                    $norma->addAuditoria($auditoria);

                    //setear instancia=1;
                    $norma->setInstancia(1);
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
        }

        return $this->redirectToRoute('logout', ['bandera' => 3], Response::HTTP_SEE_OTHER); //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma, se lo desloguea
    }

    /**
     * @Route("/{id}/edit", name="norma_edit", methods={"GET", "POST"})
     */
    public function edit(AreaRepository $areaRepository,SeguridadService $seguridad,Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
        $idTipoNorma=$norma->getTipoNorma()->getId();
        //dd($idTipoNorma);
        $session=$this->get('session');
        $session_id = $session->get('session_id') * 1;
        $idReparticion = $seguridad->getIdReparticionAction($session_id);

        $reparticionUsuario = $areaRepository->find($idReparticion);
        //dd($reparticionUsuario);
        $normasUsuario = [];
        //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
        foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
            $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getId();

        }
        //dd($idTipoNorma);
        if(!in_array($idTipoNorma,$normasUsuario,true)){
            return $this->redirectToRoute('logout', ['bandera' => 3], Response::HTTP_SEE_OTHER); //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma, se lo desloguea
        }

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

                    if($form->get('nombre_archivo')->getData()){
                        $archi->setNombre($form->get('nombre_archivo')->getData());
                    }else{
                        $archi->setNombre($originalFilename);
                    }

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

            //usuarios
            //obtener el nombre del usuario logeado
            $session=$this->get('session');
            $usuario=$session->get('username');
            $today=new DateTime();

            //crear auditoria
            $auditoria=new Auditoria();
            $auditoria->setFecha($today);
            $auditoria->setAccion("Modificacion");
            $instancia=$norma->getInstancia();
            $auditoria->setInstanciaAnterior($instancia);
            $auditoria->setInstanciaActual(1);
            $estadoAnt=$norma->getEstado();
            $auditoria->setEstadoAnterior($estadoAnt);
            $auditoria->setEstadoActual("Borrador");
            $auditoria->setNombreUsuario($usuario);
            $auditoria->setNorma($norma);
            $entityManager->persist($auditoria);
            $norma->addAuditoria($auditoria);

            //setear instancia=1;
            $norma->setInstancia(1);
            $entityManager->persist($norma);

            $entityManager->flush();
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
            
            //buscar usuario
            $session=$this->get('session');
            $usuario=$session->get('username');
            
            $today=new DateTime();

            //crear auditoria
            $auditoria=new Auditoria();
            $auditoria->setFecha($today);
            $auditoria->setAccion("Eliminacion");
            $instancia=$norma->getInstancia();
            $auditoria->setInstanciaAnterior($instancia);
            $auditoria->setInstanciaActual(4);
            $estadoAnt=$norma->getEstado();
            $auditoria->setEstadoAnterior($estadoAnt);
            $auditoria->setEstadoActual("Eliminada");
            $auditoria->setNorma($norma);
            $entityManager->persist($auditoria);
            $norma->addAuditoria($auditoria);

            //setear instancia=4;
            $norma->setInstancia(4);
            $entityManager->persist($norma);

            $entityManager->remove($norma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
    }
}
