<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SeguridadService;
use Symfony\Component\HttpFoundation\Session\Session;

class GeneralController extends AbstractController


{

        /**
     * @Route("/prueba", name="prueba")
     */
    public function prueba(){
        return $this->render('prueba.html.twig');
    }
    /**
     * @Route("/", name="login")
     */
    public function index(Request $request): Response
    {
        if ($request->getSession()->get('active') == 1)
            return $this->redirectToRoute('plan_index');
        else
            return $this->render('login/login.html.twig');
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

    /**
     * @Route("/autenticar", name="autenticar")
     */
    public function autenticarAction(Request $request, SeguridadService $seguridad)
    {
        //$seguridad = new SeguridadService();  // ESTO ESTÁ MUY MAL, MUY PERO MUY MAL. NO HACER NUNCA
        $session_id = $seguridad->loginAction($request->get('username'), $request->get('password'), $this->get('session'));

        
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
            //dd($seguridad->getListaRolesDeSistemaAction(157));

            // Para dumpear los roles del usuario logueado
            //dd($seguridad->getListRolAction($session_id));

            // Para dumpear usuarios en el sistema
            //dd($seguridad->getListUserAction($session_id, 157));

            /*
            // Autorización
            if ($seguridad->checkAccessAction($session_id, 'FP_ADMIN', $this->get('session'), false) == 1)
                $session->set('rolAuth', '1'); // 1 = ADMIN
            else if ($seguridad->checkAccessAction($session_id, 'FP_OPERADOR', $this->get('session'), false) == 1)
                $session->set('rolAuth', '0'); // 0 = NO ADMIN
            // No pude autorizar, por ende me deslogueo
            else return $this->redirectToRoute('logout'); 
            
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

            // Nuevo código
            // Obtener roles
            $roles = json_decode($seguridad->getListRolAction($session_id), true);

            // Trabajar string del primer rol (se presume que el usuario sólo tiene un rol)
            $rol = explode('_', $roles[0]['id']);

            // Sintaxis: FP_[autoridad]_[cargo]
            if ($rol[1] != 'ADMIN') {
                // Actualización: se muestra el rol del usuario en pantalla
                $cargo = NULL;
                $nombreRol = NULL;

                if ($rol[1] == 'OPE') {
                    $session->set('esOperador', '1');
                    $cargo = 'Operador de ';
                }
                else {
                    $session->set('esOperador', '0');
                    $cargo = 'Director de ';
                }

                switch ($rol[2]) {
                    case 'PRESU':
                        $session->set('rolType', '-1'); // -1 = Presupuesto
                        $nombreRol = 'Presupuesto';
                        break;
                    case 'SECRE':
                        $session->set('rolType', '0'); // 0 = Secretaría
                        $nombreRol = 'Secretaría';
                        break;
                    case 'REPAR':
                        $session->set('rolType', '1'); // 1 = Repartición
                        $nombreRol = 'Repartición';
                        break;
                }

                // Verifico el caso excepcional del rol de Secretaría
                if ($nombreRol == 'Secretaría' && $cargo == 'Director de ')
                    $cargo = 'Secretario de ';
                
                // Concatenación
                $session->set('rolName', $cargo . $nombreRol);
            }
            else {
                $session->set('rolType', '9'); // 9 = Administrador
                $session->set('esOperador', '0');
                // Actualización: se muestra el rol del usuario en pantalla
                $session->set('rolName', 'Administrador');
            }

            // Setear el ID
            // Obtener el ID de la repartición del usuario logueado
            $idReparticion = $seguridad->getIdReparticionAction($session_id);
            $session->set('rolId', $idReparticion);
            /*
            // Código para pruebas
            // Setear id de repartición 40 para los usuarios que tengan un rol relacionado a Presupuesto
            if ($session->get('rolType') == -1)
                $session->set('rolId', 40);
            */

            // Solicitud a la API del Municipio en busca de la repartición y su secretaría
            $remote_url = $this->getParameter('ws_rrhh')."/api/1.0/reparticiones/".$idReparticion;
            // Este array es por un error surgido cuando se cambió el ws de rrhh
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            ); 
            $ws = file_get_contents($remote_url, false, stream_context_create($arrContextOptions));
            $reparticion = json_decode($ws, true);  // El segundo parámetro le indica a la función que devuelva un array

            // Actualización: se muestra la repartición del usuario en pantalla
            $session->set('nombreReparticion', $reparticion[0]['nombre']);

            // Verificar el caso de Secretaría, para el cual también necesito el ID de la misma
            if ($session->get('rolType') == 0) {
                // Setear el ID de Secretaría
                $session->set('rolIdSecretaria', $reparticion[0]['secretaria']['idSecretaria']);
                /*
                $session->set('rolIdSecretaria', 110104);
                */
            }

            return $this->redirectToRoute('plan_index');
        }
        else return $this->redirectToRoute('login');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(SeguridadService $seguridad)
    {
        $session = $this->get('session');
        $session_id = $session->get('session_id') * 1;
        $seguridad->logoutAction($session_id);
        $session->clear();
        return $this->redirect($this->generateUrl('login'));
    }
}
