<?php
namespace App\Service;

use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * SeguridadService controller.
 */
class ReparticionService extends AbstractController {
    private $seguridad;
    //private $_engine;
    public function __construct(SeguridadService $seguridad, SessionInterface $session) {
        $this->seguridad = $seguridad;
        //$this->_engine = $engine;
        $this->session = $session;
    }

    public function obtenerTiposDeNormasUsuario(AreaRepository $areaRepository){
        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        //dd($idSession);
        $normasUsuario=[];
        $idReparticion = $this->seguridad->getIdReparticionAction($idSession);
        //dd($idReparticion);
        if($idReparticion == 0)
        {
            $normasUsuario = [];
        }
        else
        {
            $reparticionUsuario = $areaRepository->find($idReparticion);
            if($reparticionUsuario){
                //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
                foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                    $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getNombre();
                }
            }
        }    
        return $normasUsuario;
    }

}