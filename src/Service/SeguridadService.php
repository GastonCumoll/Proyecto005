<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use SoapClient;

/**
 * SeguridadService controller.
 */
class SeguridadService extends AbstractController {

    //private $wsdl = 'http://172.16.0.41/usrmanager/index.php?r=ws/service';
    //private $wsdl;
    private $clienteSoap;
    private $codSistema;

    function __construct(string $ws_seguridad) {
        $this->codSistema = 114;
        //$this->wsdl = $ws_seguridad;
        //$this->clienteSoap = new SoapClient($this->wsdl);
        $this->clienteSoap = new SoapClient($ws_seguridad);
    }

    public function loginAction($username, $password, $session = null, $usaMensaje = true) {
        $session_id = $this->clienteSoap->__soapCall('WsController.login', array($username, $password));
        if ($usaMensaje) {
            $mensaje = null;
            if ($session_id <= 0) {
                switch ($session_id) {
                    case -2:
                        $mensaje = array('message' => 'Usuario deshabilitado. Comuníquese con el administrador del sistema.');
                        break;
                    case -1:
                        $mensaje = array('message' => 'Acceso denegado. La contraseña es incorrecta.');
                        break;
                    case 0:
                        $mensaje = array('message' => 'Acceso denegado. Usuario incorrecto.');
                        break;
                }
                $session->getFlashBag()->set('alert alert-danger', $mensaje);
            }
        }
        return $session_id;
    }

    public function checkAccessAction($session_id, $permiso, $session = null, $usaMensaje = true) { 
        
        if (!$session_id) $respuesta = 0;//si no hay sesion return 0

        else $respuesta = $this->clienteSoap->__soapCall('WsController.checkAccess', array($session_id, $permiso, $this->codSistema));
        //WsController.checkAccess  =0: acceso denegado
        //"                         =1:acceso concedido
        //"                         =2:sesion expirada
        if ($usaMensaje) {
            $mensaje = NULL;
            switch ($respuesta) {
                case 0:
                    $mensaje = array('message' => 'Acceso denegado. El usuario no posee autorización para realizar esta tarea.');
                    break;
                case -1:
                    $mensaje = array('message' => 'Acceso denegado. La sesión ha caducado. Vuelva a ingresar al sistema.');
                    break;
                case -2:
                    $mensaje = array('message' => 'Acceso denegado. No se encontró tarea con identificador "' . $permiso . '".');
                    break;
            }
            if ($respuesta != 1) $session->getFlashBag()->set('alert alert-danger', $mensaje);
        }
        return $respuesta;
    }

    public function logoutAction($session_id, $session = null, $usaMensaje = true) {
        if(!$session_id) $respuesta = 0;
        else $respuesta = $this->clienteSoap->__soapCall('WsController.logout', array($session_id));
        //$mensaje = null;
        if ($usaMensaje && $respuesta > 0) {
            $mensaje = array('title' => 'Ha salido del sistema.', 'message' => '');
            $session->getFlashBag()->set('alert alert-success', $mensaje);
        }
        return $respuesta;
    }
    

    public function checkSessionActive($session_id) {
        $sessionActive = $this->clienteSoap->__soapCall('WsController.checkSessionActive', array($session_id));
        return $sessionActive;
    }

    public function getUserIdAction($session_id) {
        $userId = $this->clienteSoap->__soapCall('WsController.getUserId', array($session_id));
        return $userId;
    }

    public function getUsernameAction($session_id) {
        $username = $this->clienteSoap->__soapCall('WsController.getUsername', array($session_id));
        return $username;
    }

    public function getUserLegajoAction($session_id) {
        $legajo = $this->clienteSoap->__soapCall('WsController.getLegajo', array($session_id));
        return $legajo;
    }

    public function getIdReparticionAction($session_id) {
        $reparticion = $this->clienteSoap->__soapCall('WsController.getReparticion', array($session_id));
        return $reparticion;
    }

    public function getListUserAction($session_id, $sistema) {
        $listaUsuarios = $this->clienteSoap->__soapCall('WsController.getListUser', array($session_id, $sistema));
        return $listaUsuarios;
    }

    public function getUsernamePorId($session_id, $usuarioId) {
        $listaUsuarios = json_decode($this->clienteSoap->__soapCall('WsController.getListUser', array($session_id, $this->codSistema)));
        foreach ($listaUsuarios as $usuario) {
            if ($usuario->id == $usuarioId) {
                return $usuario->username;
            }
        }
        return '';
    }

    function getListRolAction($session_id) {
        $listaRoles = $this->clienteSoap->__soapCall('WsController.getListRol', array($session_id, $this->codSistema));
        return $listaRoles;
    }

    public function getListaRolesDeSistemaAction($sistema) {
        //$listaRoles = $this->clienteSoap->__soapCall('WsController.getListaRolesDeSistema', array($sistema));
        $listaRoles = $this->clienteSoap->__soapCall('WsController.getListaRolesDeSistema', array($this->codSistema));
        return $listaRoles;
    }
}
