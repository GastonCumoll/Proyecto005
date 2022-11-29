<?php

namespace App\Controller;

use App\Entity\Consulta;
use App\Form\ConsultaType;
use App\Entity\TipoConsulta;
use App\Form\TipoConsultaType;
use App\Form\ChangePasswordType;
use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use App\Repository\NormaRepository;
use App\Repository\ConsultaRepository;
use App\Repository\TipoConsultaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        if ($request->getSession()->get('active') == 1)
            return $this->redirectToRoute('inicio_admin');
        else
            return $this->render('general/login.html.twig');
    }

    /**
     * @Route("inicio", name="inicio")
     */
    public function inicio(Request $request,TipoConsultaRepository $tipoConsultaRepository,ConsultaRepository $consulta): Response
    {
        $tiposConsultas = $tipoConsultaRepository->findAll();
        return $this->renderForm('general/inicio.html.twig',[
            'tiposConsultas' => $tiposConsultas,
        ]);
    }

    /**
     * @Route("/admin/inicio", name="inicio_admin")
     */
    public function inicioAdministracion(Request $request,SeguridadService $seguridad): Response
    {
        $session = $this->get('session');
        $session_id = $session->get('session_id') * 1;
        $usuario = $session->get('username');
        
        
        return $this->render('general/inicioAdmin.html.twig',[
            'user' => $usuario
        ]);

    }
    
    /**
     * @Route("/notRole", name="not_role")
     */
    public function notRole(): Response
    {
        return $this->render('general/notRole.html.twig');
    }
    /**
     * @Route("/notRepa", name="not_repa")
     */
    public function notRepa(): Response
    {
        return $this->render('general/notRepa.html.twig');
    }

    /**
     * @Route("/404", name="404")
     */
    public function error404(): Response
    {
        return $this->render('general/404.html.twig');
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->render('general/error.html.twig');
    }

    // /**
    //  * @Route("/datosUser", name="datos_usuario")
    //  */
    // public function datos(): Response
    // {

    //     return $this->render('general/datos.html.twig');
    // }

    /**
     * @Route("/autenticar", name="autenticar")
     */
    public function autenticarAction(Request $request, SeguridadService $seguridad,AreaRepository $areas,NormaRepository $normaRepo)
    {
        $bandera=0;
        //$seguridad = new SeguridadService();  // ESTO ESTÁ MUY MAL, MUY PERO MUY MAL. NO HACER NUNCA
        $session_id = $seguridad->loginAction($request->get('username'), $request->get('password'), $this->get('session'));
        //dd($session_id);
        if($session_id==0){
            $bandera=2;
            return $this->redirectToRoute('logout',['bandera'=>$bandera],Response::HTTP_SEE_OTHER);
        }
        
        //Script para testear
        /*dump("Lista de roles:\n".$seguridad->getListaRolesDeSistemaAction(157));
        dump("Lista de usuarios:\n".$seguridad->getListUserAction($session_id, 157));

        $id1 = $seguridad->loginAction('usuario1', 'usr001', $this->get('session'));
        dump("Login usuario1:\n".$id1);
        dump("Roles usuario1:\n".$seguridad->getListRolAction($id1));
        $seguridad->logoutAction($id1);

        $id2 = $seguridad->loginAction('usuario2', 'usr002', $this->get('session'));
        dump("Login usuario2:\n".$id2);
        dump("Roles usuario2:\n"."Al querer dumpear los roles de este user rebota");
        $seguridad->logoutAction($id2);

        $id3 = $seguridad->loginAction('usuario3', 'usr003', $this->get('session'));
        dump("Login usuario3:\n".$id3);
        dump("Roles usuario3:\n".$seguridad->getListRolAction($id3));
        $seguridad->logoutAction($id3);

        die;*/
        //Fin de script
        

        if ($session_id > 0) {
            $session = new Session();
            $session = $this->get('session');
            $session->set('active', $seguridad->checkSessionActive($session_id));
            $session->set('session_id', $session_id);
            $session->set('userId', $seguridad->getUserIdAction($session_id));
            $session->set('username', $request->get('username'));
            
            // Para dumpear lista de roles en el sistema
            //dd($seguridad->getListaRolesDeSistemaAction(114));

            // Para dumpear los roles del usuario logueado
            //dd($seguridad->getListRolAction($session_id));

            // Para dumpear usuarios en el sistema
            //dd($seguridad->getListUserAction($session_id, 114));
            // Obtener el ID de la repartición del usuario logueado
            $idReparticion = $seguridad->getIdReparticionAction($session_id);
            //dd($idReparticion);
            $reparticiones=$areas->findAll();
            foreach ($reparticiones as $repa) {
                if($repa->getId()==$idReparticion){
                    $session->set('repa', $repa->getNombre());
                    $session->set('repaid', $repa->getId());
                }
            }
            $session->set('rolId', $idReparticion);
            $arrayRoles=[];
            $autorizo=false;
            // Autorización
            if ($seguridad->checkAccessAction($session_id, 'DIG_OPERADOR', $this->get('session'), false) == 1){
                $arrayRoles[]='DIG_OPERADOR';
                $session->set('roles',$arrayRoles);
                // $borradores=$normaRepo->findBorradoresCont('DIG_OPERADOR',$idReparticion);
                // $session->set('cantB',count($borradores));
                $autorizo=true;
            }
            if ($seguridad->checkAccessAction($session_id, 'DIG_ADMINISTRADOR', $this->get('session'), false) == 1){
                $arrayRoles[]='DIG_ADMINISTRADOR';
                $session->set('roles',$arrayRoles);
                $autorizo=true;
            }
            if ($seguridad->checkAccessAction($session_id, 'DIG_CONSULTOR', $this->get('session'), false) == 1){
                $arrayRoles[]='DIG_CONSULTOR';
                $session->set('roles',$arrayRoles);
                $autorizo=true;
            }
            if ($seguridad->checkAccessAction($session_id, 'DIG_EDITOR', $this->get('session'), false) == 1){
                $arrayRoles[]='DIG_EDITOR';
                $session->set('roles',$arrayRoles);
                // $listas=$normaRepo->findListasCont('DIG_EDITOR',$idReparticion);
                // $session->set('cantL',count($listas));
                $autorizo=true;
            }
            // No pude autorizar, por ende me deslogueo
            if(!$autorizo){
                $bandera=1;
                return $this->redirectToRoute('logout',['bandera'=>$bandera],Response::HTTP_SEE_OTHER);
            }
            //dd($session->all());
            /*
            // Setear el Tipo
            if ($seguridad->checkAccessAction($session_id, 'FP_PRESUPUESTO', $this->get('session'), false) == 1)
                $session->set('rolType', '-1');  // -1 = Presupuesto
            else if ($seguridad->checkAccessAction($session_id, 'FP_SECRETARIA', $this->get('session'), false) == 1)
                $session->set('rolType', '0');  // 0 = Secretaría
            else if ($seguridad->checkAccessAction($session_id, 'FP_REPARTICION', $this->get('session'), false) == 1)
                $session->set('rolType', '1');  // 1 = Repartición
            // No pude autenticar, por ende me deslogueo
            else return $this->redirectToRoute('logout'); 
            */
            $rolesSistema=($seguridad->getListRolAction($session_id));
            $rolesSistema=substr($rolesSistema,8);
            $rolesSistema=substr($rolesSistema,0,12);
            //dd($rolesSistema);

            // Nuevo código
            // Obtener roles
            $roles = json_decode($seguridad->getListRolAction($session_id), true);
            //dd($roles);

            // Trabajar string del primer rol (se presume que el usuario sólo tiene un rol)
            //$rol = explode('_', $roles[0]['id']);
            //dd($roles[0]);
            // if(!empty($roles)){
            //     if($rolesSistema==$roles[0]['id']){
            //     $rol = explode('_', $roles[0]['id']);
            // }else{
            //     $seguridad->logoutAction($session_id);
            // }
            // }else{
            //     $seguridad->logoutAction($session_id);
            // }
            
            // Sintaxis: FP_[autoridad]_[cargo]
            // if ($rol[1] != 'ADMIN') {
            //     // Actualización: se muestra el rol del usuario en pantalla
            //     $cargo = NULL;
            //     $nombreRol = NULL;

            //     if ($rol[1] == 'OPE') {
            //         $session->set('esOperador', '1');
            //         $cargo = 'Operador de ';
            //     }
            //     else {
            //         $session->set('esOperador', '0');
            //         $cargo = 'Director de ';
            //     }

            //     switch ($rol[2]) {
            //         case 'PRESU':
            //             $session->set('rolType', '-1'); // -1 = Presupuesto
            //             $nombreRol = 'Presupuesto';
            //             break;
            //         case 'SECRE':
            //             $session->set('rolType', '0'); // 0 = Secretaría
            //             $nombreRol = 'Secretaría';
            //             break;
            //         case 'REPAR':
            //             $session->set('rolType', '1'); // 1 = Repartición
            //             $nombreRol = 'Repartición';
            //             break;
            //     }

            //     // Verifico el caso excepcional del rol de Secretaría
            //     if ($nombreRol == 'Secretaría' && $cargo == 'Director de ')
            //         $cargo = 'Secretario de ';
                
            //     // Concatenación
            //     $session->set('rolName', $cargo . $nombreRol);
            // }
            // else {
            //     $session->set('rolType', '9'); // 9 = Administrador
            //     $session->set('esOperador', '0');
            //     // Actualización: se muestra el rol del usuario en pantalla
            //     $session->set('rolName', 'Administrador');
            // }

            // Setear el ID
            $session->set('rolNombre',$roles[0]['id']);
            /*
            // Código para pruebas
            // Setear id de repartición 40 para los usuarios que tengan un rol relacionado a Presupuesto
            if ($session->get('rolType') == -1)
                $session->set('rolId', 40);
            */

            // Solicitud a la API del Municipio en busca de la repartición y su secretaría
            //$remote_url = $this->getParameter('ws_rrhh')."/api/1.0/reparticiones/".$idReparticion;
            // Este array es por un error surgido cuando se cambió el ws de rrhh
            // $arrContextOptions=array(
            //     "ssl"=>array(
            //         "verify_peer"=>false,
            //         "verify_peer_name"=>false,
            //     ),
            // ); 
            // $ws = file_get_contents($remote_url, false, stream_context_create($arrContextOptions));
            // $reparticion = json_decode($ws, true);  // El segundo parámetro le indica a la función que devuelva un array

            // // Actualización: se muestra la repartición del usuario en pantalla
            // $session->set('nombreReparticion', $reparticion[0]['nombre']);

            // // Verificar el caso de Secretaría, para el cual también necesito el ID de la misma
            // if ($session->get('rolType') == 0) {
            //     // Setear el ID de Secretaría
            //     $session->set('rolIdSecretaria', $reparticion[0]['secretaria']['idSecretaria']);
            //     /*
            //     $session->set('rolIdSecretaria', 110104);
            //     */
            // }

            return $this->redirectToRoute('inicio_admin');
        }
        else return $this->redirectToRoute('login');
    }


    /**
     * @Route("/changePass", name="change_password")
     */
    public function cambiarContra(SeguridadService $seguridad,Request $request){

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        $mensaje="";
        $session = $this->get('session');
        $session_id = $session->get('session_id') * 1;

        if ($form->isSubmitted() && $form->isValid()) {
            $vieja=$form->get('contrasenia')->getData();
            $nueva=$form->get('contraseniaNueva')->getData();
            $confirmacion=$form->get('contraseniaNuevaConfir')->getData();
            if($nueva==$confirmacion){
                if($seguridad->changePassword($session_id,$vieja,$nueva) == 1){
                    $mensaje.="La contraseña fue modificada correctamente.";
                    $this->addFlash('notice',$mensaje);
                    return $this->redirectToRoute('inicio_admin', [], Response::HTTP_SEE_OTHER);
                }else{
                    $mensaje.="Error al cambiar la contraseña.";
                }
            }else{
                $mensaje.="Las contraseñas no coinciden.";
            }
        }
        if($mensaje != ""){
            $this->addFlash('notice',$mensaje);
        }
        return $this->renderForm('general/changePass.html.twig', [
            'form'=>$form,
        ]);

    }

    /**
     * @Route("/logout/{bandera}", name="logout")
     */
    public function logout(SeguridadService $seguridad,$bandera)
    {   
        
        //dd($bandera);
        $session = $this->get('session');
        $session_id = $session->get('session_id') * 1;
        $seguridad->logoutAction($session_id);
        $session->clear();
        if($bandera==1){
        $this->addFlash(
                    'notice',
                    'NO POSEE LOS ROLES NECESARIOS PARA INGRESAR AL SISTEMA'
                );
        }
        if($bandera==2){
            $this->addFlash(
                'notice',
                'USUARIO Y/O CONTRASEÑA INCORRECTOS'
            );
        }
        if($bandera==3){
            $this->addFlash(
                'notice',
                'NO POSEE LOS PERMISOS PARA REALIZAR ESTA ACCIÓN'
            );
        }
        if($bandera==3){
            $this->addFlash(
                'notice',
                'Error: la sesión ha expirado, por favor, vuelva a autenticarse.'
            );
        }
        if($bandera==0){
            return $this->redirect($this->generateUrl('inicio'));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
}
