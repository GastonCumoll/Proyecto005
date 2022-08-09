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
use App\Service\ReparticionService;
use App\Repository\ArchivoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\RelacionRepository;
use App\Repository\AuditoriaRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoNormaRolRepository;
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
use App\Repository\TipoNormaReparticionRepository;
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
    public function index(ReparticionService $reparticionService,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request, PaginatorInterface $paginator, TipoNormaRepository $tipoNorma, EtiquetaRepository $etiquetas, AreaRepository $areaRepository): Response
    {   
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        //dd($idReparticion);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }
        
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            //dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }
        //dd($rol);
        //busqueda dependiendo si hay alguien logeado o no
        if($idSession){
            $todasNormas=$normaRepository->findAllQueryS($reparticionUsuario,$rol);
        }else{
            $todasNormas=$normaRepository->findAllQuery();//query con join de tipoNorma
        }
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
    //este metodo es para saber el trayecto que tuvo una norma especifica
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
     * @Route("/acceso", name="acceso",methods={"POST"})
     */
    //este metodo es ejecutado cuando el admin quiere cambiar la privacidad de la norma.
    public function acceso(EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request){
        //dependiendo de que si el checkbox esta seleccionado o no, setea un balor a b que sirve como bandera,y setea el campo publico a norma ese mismo valor
        if(!empty($_POST['checkbox'])){
            $b=1;
        }else{
            $b=0;
        }
        $id=$_POST['normaId'];
        $norma=$normaRepository->findOneById($id);
        $norma->setPublico($b);
        $entityManager->persist($norma);
        $entityManager->flush();
        return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/updateInstancia", name="updateInstancia",methods={"POST"})
     */
    public function updateInstancia(SeguridadService $seguridad,EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request)
    {
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
        $id=$_POST['normaId'];
        $norma=$normaRepository->find($id);
        $estadoNorma=$norma->getEstado();
        $today=new DateTime();
        // dd($today);
        //obtener el nombre del usuario logeado;
        $session=$this->get('session');
        $usuario=$session->get('username');

        $auditoria=new Auditoria();

        $auditoria->setNorma($norma);
        $auditoria->setNombreUsuario($usuario);
        $auditoria->setFecha($today);

        if($estadoNorma == "Borrador"){
            $cantidadBorrador=$sesion->get('cantB');
            $cantidadBorrador--;
            $sesion->set('cantB',$cantidadBorrador);
            $cantidadListas=$sesion->get('cantL');
            $cantidadListas++;
            $sesion->set('cantL',$cantidadListas);
            //$array[]="DIG_OPERADOR";
            if("DIG_OPERADOR"==$rol){
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
            }else{
                return $this->render('general/notRole.html.twig');
            }
        }
        if($estadoNorma=="Lista"){
            $cantidadListas=$sesion->get('cantL');
            $cantidadListas--;
            $sesion->set('cantL',$cantidadListas);
            if("DIG_EDITOR"==$rol){
                $auditoria->setInstanciaAnterior($norma->getInstancia());
                $auditoria->setInstanciaActual($norma->getInstancia()+1);
                $auditoria->setEstadoAnterior($norma->getEstado());
                $auditoria->setEstadoActual("Publicada");
                $norma->setEstado("Publicada");
                $norma->setInstancia(3);
                $norma->setFechaPublicacion($today);
                $auditoria->setAccion("Publicacion");
                //$norma->setPublico($b);
                $entityManager->persist($auditoria);
                $entityManager->persist($norma);
                //$entityManager->persist($userObj);
                $entityManager->flush();

                return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->render('general/notRole.html.twig');
            }
            
        }
        if($estadoNorma=="Publicada"){
            $cantidadBorrador=$sesion->get('cantB');
            $cantidadBorrador++;
            $sesion->set('cantB',$cantidadBorrador);
            if("DIG_ADMINISTRADOR"==$rol){
                $auditoria->setInstanciaAnterior($norma->getInstancia());
                $auditoria->setInstanciaActual(1);
                $auditoria->setEstadoAnterior($norma->getEstado());
                $auditoria->setEstadoActual("Borrador");
                $norma->setEstado("Borrador");
                $norma->setInstancia(1);
                $auditoria->setAccion("Vuelta a borrador");
                $norma->setPublico(0);
                $entityManager->persist($auditoria);
                $entityManager->persist($norma);
                $entityManager->flush();

                return $this->redirectToRoute('borrador', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->render('general/notRole.html.twig');
            }
        }
        //$entityManager->flush();

        return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/backBorrador/{id}", name="back_borrador")
     */
    //este metodo se ejecuta cuando un editor tiene que enviar una norma que tiene estado "Lista" a "Borrador"
    public function backBorrador(EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request,$id)
    {
        $norma=$normaRepository->find($id);
        $estadoNorma=$norma->getEstado();
        $today=new DateTime();

        //obtener el nombre del usuario logeado;
        $session=$this->get('session');
        $usuario=$session->get('username');
        //cada vez que se modifica una norma, se crea una Auditoria;
        $auditoria=new Auditoria();

        $auditoria->setNorma($norma);
        $auditoria->setNombreUsuario($usuario);
        $auditoria->setFecha($today);

        if($estadoNorma=="Lista"){
            $cantidadBorrador=$session->get('cantB');
            $cantidadBorrador++;
            $session->set('cantB',$cantidadBorrador);
            $cantidadListas=$session->get('cantL');
            $cantidadListas--;
            $session->set('cantL',$cantidadListas);
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

            return $this->redirectToRoute('borrador', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/listas", name="listas", methods={"GET"})
     */
    //este metodo trae un query de las normas que tienen "Lista" como estado
    public function listas(ReparticionService $reparticionService,TipoNormaRolRepository $tipoNormaRolRepository,TipoNormaReparticionRepository $tipoNormaReparticionRepository,AreaRepository $areaRepository,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
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
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        $listas=$normaRepository->findListas($listaDeRolesUsuario,$reparticionUsuario);
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
    //este metodo trae un query de las normas que tienen "Lista" como estado
    public function borrador(ReparticionService $reparticionService,AreaRepository $areaRepository,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
    {
        $listaDeRolesUsuario;
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $listaDeRolesUsuario[]= $unRol["id"];
            }
            $rol=$roles[0]['id'];
        }else {
            $rol="";
        }
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        $reparticionUsuario = $areaRepository->find($idReparticion);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        $borradores=$normaRepository->findBorradores($listaDeRolesUsuario,$reparticionUsuario);

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
    //este metodo ejecuta una busqueda de normas por campo titulo, que contenga la palabra pasada por parametro
    public function busquedaRapida(ReparticionService $reparticionService,AreaRepository $areaRepository,TipoNormaRepository $tipo,NormaRepository $normaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        $listaDeRolesUsuario;
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
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
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }
        //pregunto si estoy logeado
        if(!$idSession){
            //si no hay nadie logueado, hace la busqueda por la palabra que ingrese(o busca todas si no ingrese palabra(-1))
            if($palabra=="-1"){
                $normasQuery=$normaRepository->findAllQuery();
            }else{
                $palabra=str_replace("§","/",$palabra);
                $normasQuery=$normaRepository->findUnaPalabraDentroDelTitulo($palabra);//ORMQuery
            }
        }else{
            //si hay session, filtra por roles(listaDeRolesUsuario) y reparticion(reparticionUsuario) y la palabra ingresada
            if($palabra=="-1"){
                $normasQuery=$normaRepository->findAllQueryS($reparticionUsuario,$rol);
            }else{
                $palabra=str_replace("§","/",$palabra);
                $normasQuery=$normaRepository->findUnaPalabraDentroDelTituloSession($listaDeRolesUsuario,$reparticionUsuario,$palabra);//ORMQuery
            }
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
    //este metodo es ejecutado cuando se realiza una busqueda por filtros en norma index
    public function busquedaFiltro(ReparticionService $reparticionService,AreaRepository $areaRepository,PaginatorInterface $paginator,TipoNormaRepository $tipoNormaRepository,EtiquetaRepository $etiquetaRepository ,NormaRepository $normaRepository,Request $request,SeguridadService $seguridad):Response
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
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }
        $titulo=$request->query->get('titulo');//string
        $tipo=$request->query->get('tipoNorma');//string
        $numero=$request->query->get('numero');//string
        $año=$request->query->get('año');//string
        //$etiquetas=$request->query->get('etiquetas'); //etiquetas en matenimiento por el momento
        //obtiene los datos de los campos;
        //dd($titulo);
        $arrayDeEtiquetas=[];
        //pregunta si hay alguien logeado, si no hay nadie,usa findNormas, si hay alguien logeado, busca findNormasSession y le pasa la reparticion del usuario logeado
        //dentro de los metodos findNormas y findNormasSession, pregunta si alguno de los campos está vacio o no
        if(!$idSession){
            $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeEtiquetas);
        }else{
            $normas=$normaRepository->findNormasSession($titulo,$numero,$año,$tipo,$arrayDeEtiquetas,$reparticionUsuario,$rol);
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
        $normasP->setCustomParameters([
            'align' => 'center',
        ]);
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
    //este metodo dispara el formulario de busqueda de "Busqueda Avanzada".
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
    //este metodo recibe los valores del formulario de busqueda de la pagina "Busqueda Avanzada"
    public function formBusquedaResult(ReparticionService $reparticionService,AreaRepository $areaRepository,Request $request,NormaRepository $normaRepository,PaginatorInterface $paginator,EtiquetaRepository $etiquetaRepository, TipoNormaRepository $tipoNormaRepository, SeguridadService $seguridad):Response
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
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);

        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }

        $titulo=$request->query->get('titulo');
        $tipo=$request->query->get('tipoNorma');//string
        $numero=$request->query->get('numero');//string
        $año=$request->query->get('año');//string
        $etiquetas=$request->query->get('etiquetas');

        $arrayDeNormas=[];

        if($etiquetas!= null){
                $etiquetasObj=[];
                for ($i=0; $i <count($etiquetas) ; $i++) {
                    $etiquetasObj[$i]=$etiquetaRepository->findById($etiquetas[$i]);
                    foreach ($etiquetasObj[$i][0]->getNormas() as $unaNorma) {
                        $arrayDeNormas[]=$unaNorma;
                    }
                }
        }
        //Lo mismo de siempre, discrimino si hay sesion o no.
        if(!$idSession){
            $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas);
        }else{
            $normas=$normaRepository->findNormasSession($titulo,$numero,$año,$tipo,$arrayDeNormas,$reparticionUsuario,$rol);
        }

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
    //este metodo convierte el texto de la norma en pdf y lo muestra en pantalla
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
        //dd($htmlModificado);
        $posicion=strpos($htmlModificado,'?');
        $posicion2=strpos($htmlModificado,'=es');

        if($posicion && $posicion2){
            $cadenaAEliminar=substr($htmlModificado,$posicion,$posicion2-$posicion+3);
            $mod = str_replace($cadenaAEliminar,"",$htmlModificado);
        }
        else{
            $mod=$htmlModificado;
        }
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
    }

    /**
     * @Route("/{id}/generarPDF", name="generar_pdf")
     */
    //este metodo genera un pdf del texto de la norma
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
    public function new(TipoNormaRepository $tipoNormaRepository,ReparticionService $reparticionService,AreaRepository $areaRepository,SeguridadService $seguridad, Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository,$id, SluggerInterface $slugger): Response
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
        $normasU=[];
        $normasUsuarioObj=[];
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);

        foreach($normasUsuario as $nU){
            $normasUsuarioObj=$tipoNormaRepository->findByNombre($nU);
            $normasU[]=$normasUsuarioObj[0]->getId();
        }
        //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma, se lo desloguea
        if(!in_array($id,$normasU)){
            return $this->redirectToRoute('logout', ['bandera' => 3], Response::HTTP_SEE_OTHER); 
        }
        $repository = $this->getDoctrine()->getRepository(TipoNorma::class);
        $idNorma = $repository->find($id);

        $etiquetaRepository= $this->getDoctrine()->getRepository(Etiqueta::class);

        //dependiendo del tipo de norma que se crea, se ejecuta un formulario.
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
            //$norma->setFechaPublicacion($today);
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
            //pregunto si se cargo un archivo.
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
                    
                    //se crea un objeto archivo. para vicularlo con la norma en la db 
                    $newFilename=$carpeta.'/'.$newFilename;

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
            $cantidadBorrador=$sesion->get('cantB');
            $cantidadBorrador++;
            $sesion->set('cantB',$cantidadBorrador);
            //setear instancia=1;
            $norma->setInstancia(1);
            $norma->setPublico(0);
            $entityManager->persist($norma);

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
    //este metodo sirve para agregarle un archivo a la norma una vez cargada.Tambien se le puede setear el nombre que uno quiera que aparezca .
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
    //este metodo se ejecuta cuando se quiere editar solamente el texto, por lo cual crea un pdf del texto como estaba antes de editarlo.
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
    public function edit(TipoNormaRepository $tipoNormaRepository,ReparticionService $reparticionService,AreaRepository $areaRepository,SeguridadService $seguridad,Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
        $idTipoNorma=$norma->getTipoNorma()->getId();

        $session=$this->get('session');
        $session_id = $session->get('session_id') * 1;
        $idReparticion = $seguridad->getIdReparticionAction($session_id);

        $reparticionUsuario = $areaRepository->find($idReparticion);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        foreach($normasUsuario as $nU){
            $normasUsuarioObj=$tipoNormaRepository->findByNombre($nU);
            $normasU[]=$normasUsuarioObj[0]->getId();
        }
        if(!in_array($idTipoNorma,$normasU,true)){
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
    //este metodo no se usa
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
