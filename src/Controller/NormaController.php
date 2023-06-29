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
use App\Form\ItemEditType;
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
        // $normas=$normaRepository->findAll();
        // // // foreach ($normas as $unaNorma) {
        // //     preg_match_all('/\d{4} /i', $normas->getResumen(), $matches, PREG_SET_ORDER);
        // //     dd($matches);
        //     // var_dump($matches);
        // // }
        // // dd($matches);
        // // // dd($normas->getNumero());
        // // $contador=0;
        // // $contadorFecha=0;
        // // $contadorJ=0;
        // // $contadorCoincidentes=0;
        // $array=[];
        // $resumenes=[];
        // $meses=[];
        // $años=[];
        // $fechas=[];
        // // $contadorNull=0;
        // for($i=0;$i<6464;$i++){
        //     $resumen=$normas[$i]->getResumen();
        //     if(str_contains($resumen,'DE FECHA') && strlen($resumen)<36){
        //         $posicion=strpos($resumen,'DE FECHA')+9;
        //         $dia=substr($resumen,$posicion,4);
        //         if(str_contains($resumen,'ENERO')){
        //             $mes='01';
        //         }
        //         if(str_contains($resumen,'FEBRERO')){
        //             $mes='02';
        //         }
        //         if(str_contains($resumen,'MARZO')){
        //             $mes='03';
        //         }
        //         if(str_contains($resumen,'ABRIL')){
        //             $mes='04';
        //         }
        //         if(str_contains($resumen,'MAYO')){
        //             $mes='05';
        //         }
        //         if(str_contains($resumen,'JUNIO')){
        //             $mes='06';
        //         }
        //         if(str_contains($resumen,'AGOSTO')){
        //             $mes='08';
        //         }
        //         if(str_contains($resumen,'SEPTIEMBRE') || str_contains($resumen,'SETIEMBRE')){
        //             $mes='09';
        //         }
        //         if(str_contains($resumen,'OCTUBRE')){
        //             $mes='10';
        //         }
        //         if(str_contains($resumen,'NOVIEMBRE')){
        //             $mes='11';
        //         }
        //         if(str_contains($resumen,'DICIEMBRE')){
        //             $mes='12';
        //         }
        //         if(str_contains($resumen,'JULIO')){
        //             $mes='07';
        //         }
        //         $resumen=trim($resumen);
        //         $año=substr($resumen,-5);
        //         // $mes=substr($resumen,$posicion+3,16);
                
        //         // if($dia==0){
        //         //     dd($resumen);
        //         // }
                
        //         if(intval($año)<1000){
        //             $año=substr($resumen,-6);
        //             if(intval($año)<1000){
        //                 $año=substr($resumen,-7);
        //                 if(intval($año)<1000){
        //                     $año=substr($resumen,-8);
        //                     if(intval($año)<1000){
        //                         $año=substr($resumen,-9);
        //                     }else{
        //                         $años[]=intval($año);
        //                     }
        //                 }else{
        //                     $años[]=intval($año);
        //                 }
        //             }else{
        //                 $años[]=intval($año);
        //             }
        //         }else{
        //             $años[]=intval($año);
        //         }
        //         $dia=intval($dia);
        //         $año=intval($año);
        //         $meses[]=$mes;
        //         $array[]=intval($dia);
        //         $resumenes[]=$resumen;
        //         $fecha=$año.'/'.$mes.'/'.$dia;
        //         $fecha=date("d-m-Y", strtotime($fecha));
        //         //dd($fechas);
        //         if(!$normas[$i]->getFechaSancion()){
        //             $normas[$i]->setFechaSancion($fecha);
        //         dd($normas[$i]);
        //         }
                
        //         // $fechas1=strtotime($fecha);
                
        //     }
        // }
        // dd($fechas,$resumenes);
        // foreach($normas as $unaNorma){
        //     if(str_contains($unaNorma->getResumen(),'SANCIONADA')){
        //         $contador++;
        //         if(str_contains($unaNorma->getResumen(),'DE FECHA')){
        //             //dd($string);
        //             $posicion=strpos($unaNorma->getResumen(),'DE FECHA')+10;
        //             $string=substr($unaNorma->getResumen(),$posicion,24);
                    
        //             for($i=1853;$i<2023;$i++){
        //                 $j=strval($i);
        //                 if(str_contains($string,$j)){
        //                     if(!$unaNorma->getYear()){
        //                         $unaNorma->setYear($i);
        //                     }
        //                     $contadorJ++;
        //                     break;
        //                 }
        //             }
        //             // dd($string);
        //             $contadorFecha++;
        //         }else if(str_contains($unaNorma->getResumen(),'EN FECHA')){
        //             $posicion=strpos($unaNorma->getResumen(),'EN FECHA')+10;
        //             $string=substr($unaNorma->getResumen(),$posicion,24);
                    
        //             for($i=1853;$i<2023;$i++){
        //                 $j=strval($i);
        //                 if(str_contains($string,$j)){
        //                     if(!$unaNorma->getYear()){
        //                         $unaNorma->setYear($i);
        //                     }
        //                     $contadorJ++;
        //                     break;
        //                 }
        //             }
        //         }
        //         // $posicion=strpos($unaNorma->getResumen(),'SANCIONADA')+11;
        //         // dd($posicion);
        //     }else if(str_contains($unaNorma->getResumen(),'DE FECHA')){
        //         $posicion=strpos($unaNorma->getResumen(),'DE FECHA')+10;
        //         $string=substr($unaNorma->getResumen(),$posicion,24);
                    
        //             for($i=1853;$i<2023;$i++){
        //                 $j=strval($i);
        //                 if(str_contains($string,$j)){
        //                     if(!$unaNorma->getYear()){
        //                         $unaNorma->setYear($i);
        //                     }
        //                     $contadorJ++;
        //                     break;
        //                 }
        //             }
        //     }else if(str_contains($unaNorma->getResumen(),'EN FECHA')){
        //         $posicion=strpos($unaNorma->getResumen(),'EN FECHA')+10;
        //         $string=substr($unaNorma->getResumen(),$posicion,24);
                
        //         for($i=1853;$i<2023;$i++){
        //             $j=strval($i);
        //             if(str_contains($string,$j)){
        //                 if(!$unaNorma->getYear()){
        //                     $unaNorma->setYear($i);
        //                 }
        //                 $contadorJ++;
        //                 break;
        //             }
        //         }
        //     }
        //     $entityManager->persist($unaNorma);

        // }
        // $entityManager->flush();
        
        
        // dd($normas);
        // dd($contador,$contadorFecha,$contadorJ,$contadorCoincidentes,$contadorNull);
        //     if(str_contains($num,"/")){
        //        $arrayDoble=explode("/",$num);
        //         $año=intval($arrayDoble[1]);
        //         $numAux=intval($arrayDoble[0]);

        //         // $año=$arrayDoble[0]=//numero de la norma
        //         // $arrayDoble[1]=//el año
        //         $unaNorma->setNumeroAuxiliar($numAux);
        //         $unaNorma->setYear($año);
        //         $entityManager->persist($unaNorma);
        //     }else{
        //         $numAux1=intval($unaNorma->getNumero());
        //         $unaNorma->setNumeroAuxiliar($numAux1);
        //        $entityManager->persist($unaNorma);
        //      }
        // }
        // $entityManager->flush();
        // dd($normas[0]);
        //$normas=$normaRepository->findAll();
        //foreach ($normas as $unaNorma) {
            // if(str_contains($unaNorma->getResumen(),"Sancion") || str_contains($unaNorma->getResumen(),"Sancionada") || str_contains($unaNorma->getResumen(),"SANCIONADA")){
            //     $contador++;
            // }
            // if(str_contains($unaNorma->getResumen(),"Promulgada") || str_contains($unaNorma->getResumen(),"Promulga") || str_contains($unaNorma->getResumen(),"PROMULGADA") || str_contains($unaNorma->getResumen(),"PROMULGACION") ){
            //     $contador++;
            // }
            // if(str_contains($unaNorma->getResumen(),"Boletin") || str_contains($unaNorma->getResumen(),"BOLETIN") || str_contains($unaNorma->getResumen(),"BOLE") || str_contains($unaNorma->getResumen(),"boletin") || str_contains($unaNorma->getResumen(),"boletín") || str_contains($unaNorma->getResumen(),"BOLETÍN") || str_contains($unaNorma->getResumen(),"Boletín")){
            //     $contador++;
            // }
        //}
        //dd($contador);

    }

    /**
     * @Route("/", name="norma_index", methods={"GET"})
     */
    public function index(ReparticionService $reparticionService,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request, PaginatorInterface $paginator, TipoNormaRepository $tipoNorma, EtiquetaRepository $etiquetas, AreaRepository $areaRepository): Response
    {   

        $sesion=$this->get('session');
        $sesion->set('from','todas');
        $idSession=$sesion->get('session_id')*1;
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        //dd($idReparticion);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        $arrayRoles=[];
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }
        
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($roles);
            $rol=$roles[0]['id'];
            //dd($rol);
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
        //dd($arrayRoles);
        return $this->render('norma/indexAdmin.html.twig', [
            'roles'=>$arrayRoles,
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

        if($norma->getPublico() != $b){
            $norma->setPublico($b);
            $entityManager->persist($norma);

            // $entityManager->flush();
            //auditoria
            $session=$this->get('session');
            $usuario=$session->get('username');
            $today=new DateTime();

            $auditoria=new Auditoria();

            $auditoria->setNorma($norma);
            $auditoria->setNombreUsuario($usuario);
            $auditoria->setFecha($today);

            $auditoria->setInstanciaAnterior($norma->getInstancia());
            $auditoria->setInstanciaActual($norma->getInstancia());
            $auditoria->setEstadoAnterior($norma->getEstado());
            $auditoria->setEstadoActual($norma->getEstado());
            $auditoria->setAccion("Cambio Acceso");
            $entityManager->persist($auditoria);
            $norma->addAuditoria($auditoria);
            $entityManager->persist($norma);
            $entityManager->flush();

            return $this->redirectToRoute('norma_showEdit', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }else{
            return $this->redirectToRoute('norma_showEdit', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
        
    }


    /**
     * @Route("/updateInstancia", name="updateInstancia",methods={"POST"})
     */
    public function updateInstancia(SeguridadService $seguridad,EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request)
    {
        if(!empty($_POST['checkbox'])){
            $b=1;
        }else{
            $b=0;
        }
        $listaDeRolesUsuario=[];
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

        //obtener el nombre del usuario logeado;
        $session=$this->get('session');
        $usuario=$session->get('username');

        $auditoria=new Auditoria();

        $auditoria->setNorma($norma);
        $auditoria->setNombreUsuario($usuario);
        $auditoria->setFecha($today);

        if($estadoNorma == "Borrador"){
            if(in_array("DIG_OPERADOR",$listaDeRolesUsuario)){
                $auditoria->setInstanciaAnterior($norma->getInstancia());
                $auditoria->setInstanciaActual($norma->getInstancia()+1);
                $auditoria->setEstadoAnterior($norma->getEstado());
                $norma->setEstado("Lista");
                $norma->setInstancia(2);
                $auditoria->setEstadoActual("Lista");
                $auditoria->setAccion("Revision");
                $entityManager->persist($auditoria);
                $norma->addAuditoria($auditoria);
                $entityManager->persist($norma);
                $entityManager->flush();
                return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->render('general/notRole.html.twig');
            }
        }
        if($estadoNorma=="Lista"){
            if(in_array("DIG_EDITOR",$listaDeRolesUsuario)){
                $auditoria->setInstanciaAnterior($norma->getInstancia());
                $auditoria->setInstanciaActual($norma->getInstancia()+1);
                $auditoria->setEstadoAnterior($norma->getEstado());
                $auditoria->setEstadoActual("Publicada");
                $norma->setEstado("Publicada");
                $norma->setInstancia(3);
                $norma->setEdito(false);
                if(!$norma->getFechaPublicacion()){
                    $norma->setFechaPublicacion($today);
                }
                $auditoria->setAccion("Publicacion");
                $norma->setPublico($b);
                $entityManager->persist($auditoria);
                $entityManager->persist($norma);
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
            if(in_array("DIG_ADMINISTRADOR",$listaDeRolesUsuario) || in_array("DIG_EDITOR",$listaDeRolesUsuario)){
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
            // $cantidadBorrador=$session->get('cantB');
            // $cantidadBorrador++;
            // $session->set('cantB',$cantidadBorrador);
            // $cantidadListas=$session->get('cantL');
            // $cantidadListas--;
            // $session->set('cantL',$cantidadListas);
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
        $listaDeRolesUsuario=[];
        $sesion=$this->get('session');
        $sesion->set('from','listas');
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $listaDeRolesUsuario[]= $unRol["id"];
            }
            //dd($listaDeRolesUsuario);
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
            'roles'=>$listaDeRolesUsuario,
            'normas' => $normasListas,
            'tipoNormas' => $tipoNorma->findAll(),
            'etiquetas' =>$etiquetas->findAll(),
            'normasUsuario' => $normasUsuario,
        ]);
    }

    /**
     * @Route("/borrador", name="borrador", methods={"GET"})
     */
    //este metodo trae un query de las normas que tienen "Borrador" como estado
    public function borrador(ReparticionService $reparticionService,AreaRepository $areaRepository,NormaRepository $normaRepository,SeguridadService $seguridad,Request $request,PaginatorInterface $paginator, TipoNormaRepository $tipoNorma,EtiquetaRepository $etiquetas): Response
    {
        $listaDeRolesUsuario=[];
        $sesion=$this->get('session');
        $sesion->set('from','borrador');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {
                $listaDeRolesUsuario[]= $unRol["id"];
            }
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
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
            'roles'=>$arrayRoles,
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
    public function busquedaRapida(EtiquetaRepository $etiquetaRepository, ReparticionService $reparticionService,AreaRepository $areaRepository,TipoNormaRepository $tipo,NormaRepository $normaRepository,$palabra,Request $request,SeguridadService $seguridad,PaginatorInterface $paginator):Response
    {
        //palabra es la cadena de busqueda
        $arrayP=explode(" ",$palabra);
        $numeros=[];
        $palabraNueva=$palabra;
        // dd($arrayP);
        foreach ($arrayP as $p) {
            if(is_numeric($p)){
                $numeros[]=$p;
                $longitud=strlen($p);//longitud de la palabra p
                // dd($longitud);
                $palabraNueva=str_replace($p,"",$palabraNueva);//substraigo el numero de la palabra
            }
        }
        //$palabraCorta=trim(str_replace("  "," ",$palabraNueva));
        

        $listaDeRolesUsuario=[];
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
        $filtros=[];
        //pregunto si estoy logeado
        if(!$idSession){
            //si no hay nadie logueado, hace la busqueda por la palabra que ingrese(o busca todas si no ingrese palabra(-1))
            if($palabra=="-1"){
                $normasQuery=$normaRepository->findAllQuery();
            }else{
                $palabra=str_replace("§","/",$palabra);
                $filtros[]=$palabra;
                if($numeros != null){
                    $normasQuery=$normaRepository->findUnaPalabraDentroDelTituloConNumero($palabra,$numeros);//ORMQuery
                }else{
                    $normasQuery=$normaRepository->findUnaPalabraDentroDelTitulo($palabra);//ORMQuery
                }
            }
        }else{
            //si hay session, filtra por roles(listaDeRolesUsuario) y reparticion(reparticionUsuario) y la palabra ingresada
            if($palabra=="-1"){
                $normasQuery=$normaRepository->findAllQueryS($reparticionUsuario,$rol);
            }else{
                $palabra=str_replace("§","/",$palabra);
                $filtros[]=$palabra;
                if($numeros != null){
                    $normasQuery=$normaRepository->findUnaPalabraDentroDelTituloSessionConNumero($listaDeRolesUsuario,$reparticionUsuario,$palabra,$numeros);//ORMQuery
                }else{
                    $normasQuery=$normaRepository->findUnaPalabraDentroDelTituloSession($listaDeRolesUsuario,$reparticionUsuario,$palabra);//ORMQuery
                }
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
            'etiquetas' => $etiquetaRepository->findAll(),
            'normas' => $normas,
            'roles'=>$listaDeRolesUsuario,
            'rol' => $rol,
            'tipoNormas' => $tipo->findAll(),
            'normasUsuario' => $normasUsuario,
            'filtros' =>$filtros
        ]);
        
    }

    /**
     * @Route("/{id}/normasAjax", name="normas_ajax", methods={"GET"}, options={"expose"=true})
     */
    public function normasAjax(TipoNormaReparticionRepository $tipoNormaReparticionRepository,AreaRepository $areaRepository,ReparticionService $reparticionService,SeguridadService $seguridad,NormaRepository $normaRepository,ItemRepository $itemRepository,$id): Response
    {
        //normasAjax metodo para buscar normas ligadas a los items
        $item=$itemRepository->find($id);
        $normas=$item->getNormas()->toArray();

        //------------------seccion roles y reparticion-----------------------
        /*
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
        */
        //------------------seccion roles y reparticion-----------------------
        $nH=[];
        
            foreach ($normas as $norma) {
            if($norma->getEstado()=="Publicada" && $norma->getPublico()==true){
                $nH[]=$norma;
            }
        }
        
        $jsonData = array();  
        $idx = 0;  
        
        foreach($nH as $unaNorma) {
            if($unaNorma->getTipoNorma()->getNombre()=='Decreto' && $unaNorma->getNumeroAuxiliar()!=0){
                $numero=$unaNorma->getNumeroAuxiliar().'/'.$unaNorma->getYear();
                
            }else if($unaNorma->getNumeroAuxiliar()!=0){
                $numero=$unaNorma->getNumeroAuxiliar();
            }else{
                $temp = array(
                    'titulo' => $unaNorma->getTitulo(),  
                    'tipo' => $unaNorma->getTipoNorma()->getNombre(),
                    'id' => $unaNorma->getId(),
                    ); 
                    $jsonData[$idx++] = $temp;  
                    return new Response(json_encode($jsonData), 200, array('Content-Type'=>'application/json'));
            }
            $temp = array(
                'numero' => $numero,  
                'titulo' => $unaNorma->getTitulo(),  
                'tipo' => $unaNorma->getTipoNorma()->getNombre(),
                'id' => $unaNorma->getId(),
                );   
                $jsonData[$idx++] = $temp;  
            }

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
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){                    
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }

        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        //normas usuario son el tipo de normas que puede trabajar el usuario logeado
        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }
        $titulo=$request->query->get('titulo');//string
        $tipo=$request->query->get('tipoNorma');//string
        $numero=intval($request->query->get('numero'));//int
        $año=intval($request->query->get('año'));//int
        $etiquetas=$request->query->get('etiquetas');
        
        $texto=$request->query->get('texto');

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
        //obtiene los datos de los campos;
        //dd($titulo);
        // $arrayDeEtiquetas=[];
        //pregunta si hay alguien logeado, si no hay nadie,usa findNormas, si hay alguien logeado, busca findNormasSession y le pasa la reparticion del usuario logeado
        //dentro de los metodos findNormas y findNormasSession, pregunta si alguno de los campos está vacio o no
        if(!$idSession){
            $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas,$texto);
        }else{
            $normas=$normaRepository->findNormasSession($titulo,$numero,$año,$tipo,$arrayDeNormas,$reparticionUsuario,$rol,$texto);
        }
        $filtros=[];
        if($titulo){
            $filtros[]=$titulo;
        }
        if($tipo){
            $filtros[]=$tipoNormaRepository->findOneById($tipo)->getNombre();
            
        }
        if($numero){
            $filtros[]=$numero;
        }
        if($año){
            $filtros[]=$año;
        }
        if($texto){
            $filtros[]=$texto;
        }
        
        $fEti=[];
        if($etiquetas!= null){
            foreach ($etiquetas as $eti) {
                $fEti[]=$etiquetaRepository->findOneById($eti);
            }
        }
        // dd($normas);
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
            'roles'=>$arrayRoles,
            'normasUsuario' => $normasUsuario,
            'filtros' => $filtros,
            'fEtiquetas' =>$fEti, 
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
        return $this->render('busqueda/formBusqueda.html.twig', [
            'etiquetas' => $etiquetaRepository->findAll(),
            'tipoNormas' =>$tipoNormaRepository->findAll(),
            'rol' => $rol,
            'roles'=>$arrayRoles,
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
        $idReparticion = $seguridad->getIdReparticionAction($idSession);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);

        if($idReparticion){
            $reparticionUsuario = $areaRepository->find($idReparticion);
        }


        $texto=$request->query->get('texto');
        //$textos=$normaRepository->findByTexto($texto);
        //dd($textos);
        $titulo=$request->query->get('titulo');
        $tipo=$request->query->get('tipoNorma');//string
        $numero=intval($request->query->get('numero'));//int
        $año=intval($request->query->get('año'));//int
        $etiquetas=$request->query->get('etiquetas');
        // dd($etiquetas);
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
            $normas=$normaRepository->findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas,$texto);
        }else{
            $normas=$normaRepository->findNormasSession($titulo,$numero,$año,$tipo,$arrayDeNormas,$reparticionUsuario,$rol,$texto);
        }
        $filtros=[];
        if($titulo){
            $filtros[]=$titulo;
        }
        if($tipo){
            $filtros[]=$tipoNormaRepository->findOneById($tipo)->getNombre();
            
        }
        if($numero){
            $filtros[]=$numero;
        }
        if($año){
            $filtros[]=$año;
        }
        if($texto){
            $filtros[]=$texto;
        }
        $fEti=[];
        if($etiquetas!= null){
            foreach ($etiquetas as $eti) {
                $fEti[]=$etiquetaRepository->findOneById($eti);
            }
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
            'roles'=>$arrayRoles,
            'normasUsuario' => $normasUsuario,
            'filtros' => $filtros,
            'fEtiquetas' =>$fEti,
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

        $html = $this->renderView('norma/textoPdf.html.twig', [
            //'texto' => $norma->getTexto(),
            'id' => $normaRepository->find($id)
        ]);
        //dd($html);
        //codigo para reemplazar /manager/file y despues del '?' para poder buscar las imagenes

        $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);
        $htmlModificado = str_replace('%2520',' ',$htmlModificado);
        //dd($htmlModificado); 
        // $cabecera=substr($htmlModificado,0,202);
        // $htmlModificado=substr($htmlModificado,202);
        // $cabecera='<img alt="" src="uploads/imagenes/Logomunicipalidad.png" style="height:99px;width:200px;" />';
        // $cabecera2='<img  alt="" src="uploads/imagenes/LogoHcd.png" style="height:70px;width:200px;" />';
        $posicion=strpos($htmlModificado,'?');
        $posicion2=strpos($htmlModificado,'=es');

        if($posicion && $posicion2){
            $cadenaAEliminar=substr($htmlModificado,$posicion,$posicion2-$posicion+3);
            $mod = str_replace($cadenaAEliminar,"",$htmlModificado);
        }
        else{
            $mod=$htmlModificado;
        }
        // $mod=$cabecera.$mod;
        $footerP=strpos($html,'<footer id="footer">');
        $footer=substr($html,$footerP);
        
        $mPdf = $MpdfFactory->createMpdfObject([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' =>5,
            'margin_footer' => 5,
            'margin_top' => 40,
            'margin_bottom' => 15,
            'orientation' => 'P',
            'setAutoTopMargin' => 'strech',
            'autoMarginPadding'=>'15'
            ]);
            
        $mPdf->showImageErrors = true;

        $mPdf->setHTMLHeader('<div class="containgerImg">
            <img alt="" src="build/imagenes/Logomunicipalidad.png" style="height:99px;width:200px;" />
            <img id="logoHcdPdf" alt="" src="build/imagenes/logoHCDNegro.png" />
            </div>
            <hr id="separadorH">'
        );
        
            $mPdf->setHTMLFooter('
            <hr id="separador">
            <footer id="footer">
            
            <pre> www.parana.gob.ar                    Pág. {PAGENO} de {nb}</pre>
            </footer>');
            
            $mPdf->WriteHTML($mod);
            //return $MpdfFactory->createDownloadResponse($mPdf, "file.pdf");
            $mPdf -> Output('','I');
        exit;
    }

    /**
     * @Route("/{id}/{b}/publicar", name="publicar")
     */
    public function publicar($id,$b,SeguridadService $seguridad,EntityManagerInterface $entityManager,NormaRepository $normaRepository,Request $request){
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
        //$id=$_POST['normaId'];
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
        if($estadoNorma=="Lista"){
            if(in_array("DIG_EDITOR",$listaDeRolesUsuario)){
                $auditoria->setInstanciaAnterior($norma->getInstancia());
                $auditoria->setInstanciaActual($norma->getInstancia()+1);
                $auditoria->setEstadoAnterior($norma->getEstado());
                $auditoria->setEstadoActual("Publicada");
                $norma->setEstado("Publicada");
                $norma->setInstancia(3);
                $norma->setEdito(false);

                if(!$norma->getFechaPublicacion()){
                    $norma->setFechaPublicacion($today);
                }
                
                $auditoria->setAccion("Publicacion");
                $norma->setPublico($b);
                $entityManager->persist($auditoria);
                $norma->setTextoAnterior(NULL);
                $entityManager->persist($norma);
                //$entityManager->persist($userObj);
                $entityManager->flush();

                return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->render('general/notRole.html.twig');
            }
        }else if($estadoNorma=="Publicada"){
            return $this->redirectToRoute('norma_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }
    }


    /**
     * @Route("/{id}/generarPDF", name="generar_pdf",methods={"GET","POST"})
     */
    //este metodo genera un pdf del texto de la norma
    public function generarPdf(AuditoriaRepository $auditoriaRepository,EntityManagerInterface $entityManager,NormaRepository $normaRepository,ArchivoRepository $archivoRepository , $id, MpdfFactory $MpdfFactory): Response
    {
        $norma=$normaRepository->find($id);
        if(!$norma->getFechaSancion() || !$norma->getNumeroAuxiliar()){

            $this->addFlash(
                'verifPublicar',
                "No fue posible publicar la norma debido a que no tiene una fecha de sanción y/o un número definido."
            );
            return $this->redirectToRoute('norma_show',['id'=>$id],Response::HTTP_SEE_OTHER);
        }


        if(!empty($_POST['checkbox'])){

            $b=1;
        }else{

            $b=0;
        }
        $var=false;
        
        $auditorias=$auditoriaRepository->findByNormaTexto($norma);
        //dd($auditorias);
        foreach ($auditorias as $unaAuditoria) {
            if($unaAuditoria->getEstadoActual()=="Publicada"){
                $var=true;
            }
        }
        // dd($var,$norma->getTexto(),$norma->getTextoAnterior());
        if($norma->getTextoAnterior()){
            if($var == true && $norma->getTexto() != $norma->getTextoAnterior()){
                
                $normaNombre=$norma->getTitulo();
                $normaNombreLimpio=str_replace("/","-",$normaNombre);//reemplaza / por - asi puede guardarlo
                // $cabecera='<img alt="" src="uploads/imagenes/Logomunicipalidad.png" style="height:99px;width:200px;" />';
                $today = new DateTime();
                $result = $today->format('d-m-Y H-i-s');
                $hoy = $today->format('d-m-Y\\ H:i');

                // Recupere el HTML generado en nuestro archivo twig
                $html = $this->renderView('norma/generarPdf.html.twig', [
                    //'texto' => $norma->getTexto(),
                    'id' => $normaRepository->find($id)
                ]);

                //codigo para reemplazar /manager/file y despues del '?' para poder buscar las imagenes
                $htmlModificado = str_replace('/manager/file','uploads/imagenes',$html);
                $mod = str_replace('?conf=images&amp;module=ckeditor&amp;CKEditor=decreto_texto&amp;CKEditorFuncNum=3&amp;langCode=es',"",$htmlModificado);
                // $mod=$cabecera.$mod;
                $mPdf = $MpdfFactory->createMpdfObject([
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'margin_header' =>5,
                    'margin_footer' => 5,
                    'margin_top' => 40,
                    'margin_bottom' => 15,
                    'orientation' => 'P',
                    'setAutoTopMargin' => 'strech',
                    'autoMarginPadding'=>'15'
                    ]);

                    $mPdf->showImageErrors = true;

                    // $mPdf->setFooter('www.Parana.gob.ar');
                    $mPdf->setHTMLHeader('<div class="containgerImg">
                    <img alt="" src="build/imagenes/Logomunicipalidad.png" style="height:99px;width:200px;" />
                    <img id="logoHcdPdf" alt="" src="build/imagenes/logoHCDNegro.png" />
                </div>
                <hr id="separadorH">
        
                ');
                    $mPdf->setHTMLFooter('
                    <hr id="separador">
                    <footer id="footer">
                    
                    <pre> www.parana.gob.ar                    Pág. {PAGENO} de {nb}</pre>
                    </footer>');
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
                //dd($archi);
                $entityManager->persist($archi);
                $norma->addArchivos($archi);
                $norma->setTextoAnterior(NULL);
                $entityManager->persist($norma);
                $entityManager->flush();
                
                //return true;
                return $this->redirectToRoute('publicar', ['id' =>$id,'b' =>$b], Response::HTTP_SEE_OTHER);
                exit;
            }
            else{
                return $this->redirectToRoute('publicar', ['id' =>$id,'b' =>$b], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('publicar', ['id' =>$id,'b' =>$b], Response::HTTP_SEE_OTHER);
        }
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
    public function new(TipoNormaRepository $tipoNormaRepository,ReparticionService $reparticionService,AreaRepository $areaRepository,SeguridadService $seguridad, 
    Request $request, EntityManagerInterface $entityManager,NormaRepository $normaRepository,$id, SluggerInterface $slugger, ItemRepository $itemRepository): Response
    {
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){

            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            foreach ($roles as $unRol) {

                $arrayRoles[]=$unRol['id'];
            }
            $rol=$roles[0]['id'];
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
        //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma, lo manda a la pagina de error
        if(!in_array($id,$normasU)){
            return $this->redirectToRoute('not_role', [], Response::HTTP_SEE_OTHER); 
        }
        $repository = $this->getDoctrine()->getRepository(TipoNorma::class);
        $idNorma = $repository->find($id);

        $etiquetaRepository= $this->getDoctrine()->getRepository(Etiqueta::class);

        $booleano = false;

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
        foreach($_POST as $datos){
            
            if(isset($datos['items'])){
                if((str_contains($form['items']->getErrors()[0]->getCause()->getMessage(),$datos['items'][0])) && (str_contains($form['items']->getErrors()[0]->getCause()->getMessage(),'do not exist in the choice list.'))){
                    $booleano = true;
                }
            }else{
                $booleano = $form->isValid();
            }
        }

        if ($form->isSubmitted() && $booleano) {
            $today = new DateTime();

            $norma->setEstado("Borrador");

            if($form->get('fechaSancion')->getData() && $form->get('numeroAuxiliar')->getData()){
                $fecha=$form->get('fechaSancion')->getData();
                $fecha=date_format($fecha, "Y");
                $numYAño=$form->get('numeroAuxiliar')->getData().'/'.$fecha;
                $norma->setNumero($numYAño);
            }
            if($form->get('fechaSancion')->getData()){
                $fecha=$form->get('fechaSancion')->getData();
                $fecha=date_format($fecha, "Y");
                $norma->setYear(intval($fecha));
            }

            foreach($_POST as $datosFormulario){
                if(isset($datosFormulario['items'])){
                    foreach($datosFormulario['items'] as $unIdItem){
                        if(is_numeric($unIdItem)){
                            $item = $itemRepository->findOneById($unIdItem);
                        
                            $norma->addItem($item);
                            $item->addNorma($norma); 
                            $entityManager->persist($item);
                        }
                    }
                }
            }

            $entityManager->persist($norma);
            $entityManager->flush();
            
            $brochureFile = $form->get('archivo')->getData();
            //pregunto si se cargo un archivo.
            if ($brochureFile) {
                foreach ($brochureFile as $unArchivo) {
                    $originalFilename = pathinfo($unArchivo->getClientOriginalName(), PATHINFO_FILENAME);
                    //mayor a 10 mb
                    
                    if($unArchivo->getSize() > 1000000){
                        $this->addFlash(
                            'archivoMaxSize',
                            "El archivo que está queriendo cargar es muy grande. No debe superar los 10 Mb."
                        );
                        return $this->redirectToRoute('norma_new',['id'=>$id],Response::HTTP_SEE_OTHER);
                    }
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
                    $newFilename='pdf/'.$newFilename;

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
    public function agregarArchivo(TipoNormaRepository $tipoNormaRepository,AreaRepository $areaRepository,ReparticionService $reparticionService,SeguridadService $seguridad,Request $request, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id): Response
    {
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
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
        // dd($id);
        $idTipoNorma=$norma->getTipoNorma()->getId();
        
        //si el usuario ingresa de forma indebida, es decir, no tiene la misma repartición de la norma lo manda a pantalla de error
        if(!in_array($idTipoNorma,$normasU)){
            return $this->redirectToRoute('not_role', [], Response::HTTP_SEE_OTHER);
        }
        if(($norma->getEstado() == 'Publicada') && (!in_array('DIG_EDITOR',$arrayRoles))){
            return $this->redirectToRoute('not_role', [], Response::HTTP_SEE_OTHER);
        }

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
                    $ruta = $safeFilename.'-'.uniqid().'.'.$unArchivo->guessExtension();
                    $carpeta=$unArchivo->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $unArchivo->move(
                        $this->getParameter('brochures_directory'),$ruta);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $ruta='pdf/'.$ruta;

                    $archi=new Archivo();
                    $archi->setRuta($ruta);
                    $archi->setNorma($norma);

                    if($nombreArchivo){
                        $archi->setNombre($nombreArchivo);
                    }else{
                        $archi->setNombre($originalFilename);
                    }

                    $archi->setTipo($carpeta);

                    $entityManager->persist($archi);
                    $norma->addArchivos($archi);
                    $today = new DateTime();
                    //usuarios
                    //obtener el nombre del usuario logeado;
                    $session=$this->get('session');
                    $usuario=$session->get('username');
                    
                    //crear auditoria pero antes pregunto en que estado esta la norma
                    if($norma->getEstado()=="Borrador"){
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

                    $entityManager->persist($norma);  
                    }
                    if($norma->getEstado()=="Lista"){
                        $auditoria=new Auditoria();
                        $auditoria->setFecha($today);
                        $auditoria->setAccion("Carga archivo");
                        $instancia=$norma->getInstancia();
                        $auditoria->setInstanciaAnterior($instancia);
                        $auditoria->setInstanciaActual(2);
                        $estadoAnt=$norma->getEstado();
                        $auditoria->setEstadoAnterior($estadoAnt);
                        $auditoria->setEstadoActual("Lista");
                        $auditoria->setNombreUsuario($usuario);
                        $auditoria->setNorma($norma);
                        $entityManager->persist($auditoria);
                        $norma->setInstancia(2);
                        $norma->addAuditoria($auditoria);

                        $entityManager->persist($norma);  
                    }
                }
            }
            $entityManager->flush();
            return $this->redirectToRoute('norma_showEdit', ['id'=>$id], Response::HTTP_SEE_OTHER);
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
        
        if(!empty($itemDeNorma=$norma->getItems()->toArray())){
            $item=$itemDeNorma[0];
        }else{
            $item="";
        }

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

        return $this->render('norma/show.html.twig', [
            'item'=>$item,
            'roles'=>$arrayRoles,
            'norma' => $norma,
            'relacion' => $relacion,
            'rol'=>$rol,
            'user' => $unUser,
            'usuarioReparticion' => $usuarioReparticion,
        ]);
    }

    /**
     * @Route("/showEdit/{id}", name="norma_showEdit", methods={"GET"})
     */
    public function showEdit(Norma $norma,$id,Request $request, SeguridadService $seguridad): Response
    {
        
        
        if(!empty($itemDeNorma=$norma->getItems()->toArray())){
            $item=$itemDeNorma[0];
        }else{
            $item="";
        }

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

        return $this->render('norma/showEdit.html.twig', [
            'item'=>$item,
            'roles'=>$arrayRoles,
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
    //este metodo se ejecuta cuando se quiere editar solamente el texto, por lo cual se puede crear un pdf del texto como estaba antes de editarlo o no, dependiendo si
    //alguna vez esa norma estuvo publicada o no
    public function editTexto(NormaRepository $normaRepository,ArchivoRepository $archivoRepository,Request $request, SeguridadService $seguridad, Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id,AuditoriaRepository $auditoriaRepository,MpdfFactory $MpdfFactory): Response
    {   
        $session=$this->get('session');
        $session_id = $session->get('session_id') * 1;
        $idReparticion = $seguridad->getIdReparticionAction($session_id);  //se obtiene la repartición del usuario logueado
        $reparticionesNorma = $norma->getTipoNorma()->getTipoNormaReparticions(); //se obtienen las reparticiones a las que pertenece ese tipo de norma a editar

        $textoAnterior=$norma->getTexto();//se obtiene el texto antes de modificarlo para comprarlo despues si se cambio o no
        foreach($reparticionesNorma as $unaReparticion){
            if($unaReparticion->getReparticionId()->getId() == $idReparticion){ //comparo el id de repartición del tipo de norma con el id de repartición del usuario logueado
                $form = $this->createForm(TextoEditType::class, $norma);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid())
                {
                    if($norma->getTexto() != $textoAnterior){
                        $norma->setEdito(true);
                    }
                    $entityManager->persist($norma);        
                    //usuarios
                    //obtener el nombre del usuario logeado;
                    $session=$this->get('session');
                    $usuario=$session->get('username');
                    $today=new DateTime();
                    if($norma->getEstado()=="Borrador"){
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
                    }
                    if($norma->getEstado()=="Lista"){
                        //crear auditoria
                        $auditoria=new Auditoria();
                        $auditoria->setFecha($today);
                        $auditoria->setAccion("Modificacion texto");
                        $instancia=$norma->getInstancia();
                        $auditoria->setInstanciaAnterior($instancia);
                        $auditoria->setInstanciaActual(2);
                        $estadoAnt=$norma->getEstado();
                        $auditoria->setEstadoAnterior($estadoAnt);
                        $auditoria->setEstadoActual("Lista");
                        $auditoria->setNombreUsuario($usuario);
                        $auditoria->setNorma($norma);
                        $entityManager->persist($auditoria);
                        $norma->addAuditoria($auditoria);

                        //setear instancia=1;
                        $norma->setInstancia(2);
                        $entityManager->persist($norma);
                    }
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

        return $this->redirectToRoute('not_role', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/edit", name="norma_edit", methods={"GET", "POST"})
     */
    public function edit(TipoNormaRepository $tipoNormaRepository,ReparticionService $reparticionService,AreaRepository $areaRepository,SeguridadService $seguridad,Request $request, 
    Norma $norma, EntityManagerInterface $entityManager,SluggerInterface $slugger,$id,ItemRepository $itemRepository): Response
    {
        //seteamos el texto anterior (original) en la variable textoAnterior para despues comparar y generar el pdf o no
        if(!$norma->getTextoAnterior()){
            $norma->setTextoAnterior($norma->getTexto());
            $entityManager->persist($norma);
        }
        $textoPreSubmit=$norma->getTexto();
        //itemPreEdit se usa para saber los items atados a la norma antes de que se edite
        //se compara, y si viene uno que no es igual al que ya tiene, elimina el viejo y añade el nuevo
        $itemsPreEdit=$norma->getItems()->toArray();

        $idTipoNorma=$norma->getTipoNorma()->getId();
        
        $session=$this->get('session');
        

        $session_id = $session->get('session_id') * 1;
        $idReparticion = $seguridad->getIdReparticionAction($session_id);
        $reparticionUsuario = $areaRepository->find($idReparticion);
        $normasUsuario=$reparticionService->obtenerTiposDeNormasUsuario($areaRepository);
        $tipoNormaPermitida=[];

        foreach($normasUsuario as $nU){
            $normasUsuarioObj=$tipoNormaRepository->findByNombre($nU);
            $normasU[]=$normasUsuarioObj[0]->getId();
            $unTipoNormaP=[
                'idTipoNorma'=> $normasUsuarioObj[0]->getId(),
                'nombreTipoNorma'=>$nU,
            ];
            $tipoNormaPermitida[]= $unTipoNormaP;
        }

        if($seguridad->checkSessionActive($session_id)){
            $roles=json_decode($seguridad->getListRolAction($session_id), true);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
        }else {
            $rol="";
        }
        if(!in_array($idTipoNorma,$normasU,true) || ($norma->getEstado()=='Publicada' && (!in_array('DIG_EDITOR',$arrayRoles)))){
            return $this->redirectToRoute('not_role', [], Response::HTTP_SEE_OTHER);
        }
        //se crea una variable en sesion 'urlAnterior' para guardar la url de donde vengo a editar, ya que al submitear el formulario, se pierde 
        //la ultima url
        //urlAnterior solo se guarda la primera vez que entra, por eso pregunta si esta definida, una vez definida no la vuelve a pisar
        
        if(!$session->get('urlAnterior') && $_SERVER['HTTP_REFERER']){
            $session->set('urlAnterior',$_SERVER['HTTP_REFERER']);
        }
        
        $booleano = false;

        switch ($norma->getTipoNorma()->getNombre()){
            case 'Decreto':
                $form = $this->createForm(DecretoTypeEdit::class, $norma,['tipoNormasUsuario' => $tipoNormaPermitida]);
                $form->handleRequest($request);
            break;
            case 'Ordenanza':
                $form = $this->createForm(OrdenanzaTypeEdit::class, $norma,['tipoNormasUsuario' => $tipoNormaPermitida]);
                $form->handleRequest($request);
            break;
            case 'Resolucion':
                $form = $this->createForm(ResolucionTypeEdit::class, $norma,['tipoNormasUsuario' => $tipoNormaPermitida]);
                $form->handleRequest($request);
            break;
            case 'Ley':
                $form = $this->createForm(LeyTypeEdit::class, $norma,['tipoNormasUsuario' => $tipoNormaPermitida]);
                $form->handleRequest($request);
            break;
            default:
                $form = $this->createForm(CircularTypeEdit::class, $norma,['tipoNormasUsuario' => $tipoNormaPermitida,'tipoNorma'=>$norma->getTipoNorma()->getNombre()]);
                $form->handleRequest($request);
            break;
        }

        foreach($_POST as $datos){
            
            if(isset($datos['items'])){

                if((str_contains($form['items']->getErrors()[0]->getCause()->getMessage(),$datos['items'][0])) && (str_contains($form['items']->getErrors()[0]->getCause()->getMessage(),'do not exist in the choice list.'))){
                    $booleano = true;
                }
            }else{
                $booleano = $form->isValid();
            }
        }

        if ($form->isSubmitted() && $booleano)
        {

            $tipoN=$form['tipoDeNorma']->getData();
            if($tipoN){
                $tipoNormaNueva=$tipoNormaRepository->findOneById($tipoN);
                $norma->setTipoNorma($tipoNormaNueva);
            }
            

            $itemsPostEdit=[];
            foreach($_POST as $datosFormulario){
                if(isset($datosFormulario['items'])){
                    foreach($datosFormulario['items'] as $unIdItem){
                        $itemsPostEdit[] = $itemRepository->findOneById($unIdItem);
                    }
                }
            }
            $itemsAgregados = array_diff($itemsPostEdit,$itemsPreEdit);
            $itemsEliminados = array_diff($itemsPreEdit,$itemsPostEdit);
            // dd($itemsPostEdit,$itemsPreEdit,$itemsAgregados,$itemsEliminados);
            if($form->get('fechaSancion')->getData() && $form->get('numeroAuxiliar')->getData()){
                $fecha=$form->get('fechaSancion')->getData();
                $fecha=date_format($fecha, "Y");
                $numYAño=$form->get('numeroAuxiliar')->getData().'/'.$fecha;
                $norma->setNumero($numYAño);
            }
            if($form->get('fechaSancion')->getData()){
                $fecha=$form->get('fechaSancion')->getData();
                $fecha=date_format($fecha, "Y");
                $norma->setYear(intval($fecha));
            }
            
            if($form['texto']->getData() != $textoPreSubmit){
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
                $auditoria->setInstanciaActual($instancia);
                $estadoAnt=$norma->getEstado();
                $auditoria->setEstadoAnterior($estadoAnt);
                $auditoria->setEstadoActual($estadoAnt);
                $auditoria->setNombreUsuario($usuario);
                $auditoria->setNorma($norma);
                $entityManager->persist($auditoria);
                $norma->addAuditoria($auditoria);
                
                $norma->setInstancia($instancia);
                $entityManager->persist($norma);
            }

            if($itemsAgregados){
                foreach ($itemsAgregados as $unItem) {
                        // $newItem= new Item();
                        // $newItem=$unItem;
                        $norma->addItem($unItem);
                        $unItem->addNorma($norma); 
                        $entityManager->persist($unItem);
                }
                $entityManager->persist($norma);
            }
            if($itemsEliminados){
                foreach($itemsEliminados as $unItemEli){
                    $norma->removeItem($unItemEli);
                    $unItemEli->removeNorma($norma);
                    $entityManager->persist($unItemEli);
                }
                $entityManager->persist($norma);
            }

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

            if($norma->getEstado()=="Borrador"){
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
            }
            if($norma->getEstado()=="Lista"){
                //crear auditoria
                $auditoria=new Auditoria();
                $auditoria->setFecha($today);
                $auditoria->setAccion("Modificacion");
                $instancia=$norma->getInstancia();
                $auditoria->setInstanciaAnterior($instancia);
                $auditoria->setInstanciaActual(2);
                $estadoAnt=$norma->getEstado();
                $auditoria->setEstadoAnterior($estadoAnt);
                $auditoria->setEstadoActual("Lista");
                $auditoria->setNombreUsuario($usuario);
                $auditoria->setNorma($norma);
                $entityManager->persist($auditoria);
                $norma->addAuditoria($auditoria);

                //setear instancia=1;
                $norma->setInstancia(2);
                $entityManager->persist($norma);
            }
            if($norma->getEstado()=="Publicada"){
                //crear auditoria
                $auditoria=new Auditoria();
                $auditoria->setFecha($today);
                $auditoria->setAccion("Modificacion");
                $instancia=$norma->getInstancia();
                $auditoria->setInstanciaAnterior($instancia);
                $auditoria->setInstanciaActual($instancia);
                $estadoAnt=$norma->getEstado();
                $auditoria->setEstadoAnterior($estadoAnt);
                $auditoria->setEstadoActual($estadoAnt);
                $auditoria->setNombreUsuario($usuario);
                $auditoria->setNorma($norma);
                $entityManager->persist($auditoria);
                $norma->addAuditoria($auditoria);

                //setear instancia=1;
                $norma->setInstancia($instancia);
                $entityManager->persist($norma);
                $entityManager->flush();
                //como la norma ya esta publicada, redirecciona a generar pdf
                $session->remove('urlAnterior');
                return $this->redirectToRoute('generar_pdf', ['id'=>$norma->getId()], Response::HTTP_SEE_OTHER);
            }
            
            $entityManager->flush();
            //dependiendo de cual es la urlAnterior redirecciona a ciertas vistas
            // if(str_contains($session->get('urlAnterior'),'borrador')){
            //     $session->remove('urlAnterior');
            //     return $this->redirectToRoute('borrador', [], Response::HTTP_SEE_OTHER);
            // }
            // else if(str_contains($session->get('urlAnterior'),'listas')){
            //     $session->remove('urlAnterior');
            //     return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
            // }
            // else if(str_contains($session->get('urlAnterior'),'norma/'.$norma->getId())){
            //     $session->remove('urlAnterior');
            //     return $this->redirectToRoute('norma_show', ['id'=>$norma->getId()], Response::HTTP_SEE_OTHER);
            // }
            // else{
            //     $session->remove('urlAnterior');
            //     return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
            // }
            return $this->redirectToRoute('norma_showEdit', ['id'=>$norma->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('norma/edit.html.twig', [
            'norma' => $norma,
            'form' => $form,
            'idT' => $itemsPreEdit,
        ]);
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
            $auditoria->setNombreUsuario($usuario);
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
            $norma->setEstado("Eliminada");
            $norma->setPublico(0);
            $entityManager->persist($norma);

            //$entityManager->remove($norma);
            $entityManager->flush();
        }

        if(str_contains($_SERVER['HTTP_REFERER'],'borrador')){
            return $this->redirectToRoute('borrador', [], Response::HTTP_SEE_OTHER);
        }else if(str_contains($_SERVER['HTTP_REFERER'],'listas')){
            return $this->redirectToRoute('listas', [], Response::HTTP_SEE_OTHER);
        }else{
            return $this->redirectToRoute('norma_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
