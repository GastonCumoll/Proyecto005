<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FindReparticionService{

    private $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function DatosReparticiones(): array
    {
        $remote_url = $this->params->get('ws_reparticiones');
        $opts = array(// Create a stream
            'http' => array(
                'method' => "GET", 
            ),
            'ssl' => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        $context = stream_context_create($opts);
        $reparticiones = json_decode(file_get_contents($remote_url, false, $context),true);
        return  $reparticiones;
        
        $datos = array();
        if(!is_null($reparticiones )){
                foreach ($reparticiones as $value) {
                        array_push($datos,[$value->idReparticion, $value->nombre]);
                }   
        }
        
        $datos = $this->super_unique($datos,0);
        
        
        return $datos;
    }
    
    function super_unique($array,$key)
    {
        $temp_array = [];
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
            $temp_array[$v[$key]] =& $v;
        }
        $array = array_values($temp_array);
        return $array;

    }

    public function getReparticionNombre($id){
        $remote_url = $this->params->get('ws_reparticiones').'/'.$id;
        $opts = array(// Create a stream
            'http' => array(
                'method' => "GET", 
            ),
            'ssl' => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        $context = stream_context_create($opts);
        $reparticiones = json_decode(file_get_contents($remote_url, false, $context),true);
        if($reparticiones){
            $r = $reparticiones[0];
            $id = str_pad($r['idReparticion'], 3, "0", STR_PAD_LEFT);
            return $id." - ".$r['nombre'];
        }
        
        return  $id;
    }

    public function getNombreReparticion($id){
        $remote_url = $this->params->get('ws_reparticiones').'/'.$id;
        $opts = array(// Create a stream
            'http' => array(
                'method' => "GET", 
            ),
            'ssl' => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        $context = stream_context_create($opts);
        $reparticiones = json_decode(file_get_contents($remote_url, false, $context),true);
        if($reparticiones){
            $r = $reparticiones[0]['nombre'];
        }
        
        return  $r;
    }
}