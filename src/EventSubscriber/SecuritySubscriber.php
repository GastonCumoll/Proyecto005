<?php

namespace App\EventSubscriber;

//use Twig\Environment;
use InvalidArgumentException;
use App\Service\SeguridadService;
use App\Controller\GeneralController;
use App\Controller\NormaController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecuritySubscriber implements EventSubscriberInterface
{
    private $seguridad;
    //private $_engine;
    
    public function __construct(SeguridadService $seguridad, /*Environment $engine,*/ SessionInterface $session) {
        $this->seguridad = $seguridad;
        //$this->_engine = $engine;
        $this->session = $session;
    }

    public function onRequestEvent(RequestEvent $event) {
        // Verificación de sesión expirada
        
        //  if (!$this->seguridad->checkSessionActive($this->session->get('session_id'))) {
        //      $mensaje = array('message' => 'Error: la sesión ha expirado, por favor, vuelva a autenticarse.');
        //      $this->session->getFlashBag()->set('alert alert-danger', $mensaje);
        //      $event->setResponse(new RedirectResponse('/logout'));
        // }
        // else {
            $request = $event->getRequest();
            //dd($request);
            $routeName = $request->attributes->get('_route');
            //dd($this->session->get('session_id'));
            //dd($this->session);
            //$routeParameters = $request->attributes->get('_route_params');
            // dd($routeParameters);
            // Sintaxis: nombreRuta => [roles]
            // Lista de roles: FP_ADMIN(-1), FP_DIR_PRESU(-1), FP_OPE_PRESU(-1), FP_SEC_SECRE(0), FP_OPE_SECRE(0), FP_DIR_REPAR(1), FP_OPE_REPAR(1)
            $routeList = [

                //Norma
                'norma_index'=>[],
                'norma_show'=>[],
                'norma_edit'=>['DIG_OPERADOR','DIG_EDITOR'],
                'texto_edit'=>['DIG_OPERADOR','DIG_EDITOR'],
                'normas_ajax'=>[],
                'mostrar_pdf'=>[],
                'generar_pdf'=>['DIG_OPERADOR','DIG_EDITOR'],
                'norma_new'=>['DIG_OPERADOR','DIG_ADMINISTRADOR'],
                'mostrar_texto'=>[],
                'norma_delete'=>['DIG_ADMINISTRADOR'],
                'busqueda_avanzada' =>[],
                'formulario_busqueda_result'=>[],
                'formulario_busqueda'=>[],
                'busqueda_filtro'=>[],
                'busqueda_param'=>[],
                'busqueda_rapida'=>[],
                'updateInstancia'=>['DIG_OPERADOR','DIG_ADMINISTRADOR','DIG_EDITOR'],
                'listas'=>['DIG_OPERADOR','DIG_ADMINISTRADOR','DIG_EDITOR'],
                'borrador' =>['DIG_OPERADOR','DIG_ADMINISTRADOR','DIG_EDITOR'],
                'agregar_archivo'=>['DIG_OPERADOR','DIG_EDITOR'],
                'trayecto_norma' => ['DIG_OPERADOR','DIG_ADMINISTRADOR','DIG_EDITOR'],
                'back_borrador'=>['DIG_EDITOR'],
                'acceso'=>['DIG_ADMINISTRADOR'],


                //Relacion
                'relacion_index'=>['DIG_OPERADOR','DIG_EDITOR'],
                'relacion_delete'=>['DIG_OPERADOR','DIG_EDITOR'],
                'relacion_edit'=>['DIG_OPERADOR','DIG_EDITOR'],
                'relacion_new'=>['DIG_OPERADOR','DIG_EDITOR'],
                'form_rela_edit'=>['DIG_OPERADOR','DIG_EDITOR'],
                'relacion_show'=>['DIG_OPERADOR','DIG_EDITOR'],

                //Tipo Relacion
                'tipo_relacion_index'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'tipo_relacion_new'=>['DIG_ADMINISTRADOR'],
                'tipo_relacion_show'=>['DIG_ADMINISTRADOR'],
                'tipo_relacion_delete'=>['DIG_ADMINISTRADOR'],
                'tipo_relacion_edit'=>['DIG_ADMINISTRADOR'],
                
                //Tipo Norma
                'tipo_norma_index'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'norma_nueva'=>['DIG_OPERADOR','DIG_ADMINISTRADOR'],
                'tipo_norma_new'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_show'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_edit'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_delete'=>['DIG_ADMINISTRADOR'],

                //Item
                'item_index'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'item_new'=>['DIG_ADMINISTRADOR'],
                'item_show'=>['DIG_ADMINISTRADOR'],
                'item_edit'=>['DIG_ADMINISTRADOR'],
                'item_delete'=>['DIG_ADMINISTRADOR'],
                'busqueda_param_item'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],


                //indice 
                'indice_vigente'=>[],
                'indice_no_vigente'=>[],
                'pagina_principal'=>[],

                //General
                'login'=>[],
                '404'=>[],
                'error'=>[],
                'autenticar'=>[],
                'logout'=>[],//ojo con este
                'inicio' =>[],
                'not_role'=>[],
                'inicio_admin'=>['DIG_OPERADOR','DIG_ADMINISTRADOR','DIG_CONSULTOR','DIG_EDITOR'],

                //Etiqueta
                'etiqueta_index'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'etiqueta_new'=>['DIG_ADMINISTRADOR'],
                'etiqueta_show'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'etiqueta_edit'=>['DIG_ADMINISTRADOR'],
                'etiqueta_delete'=>['DIG_ADMINISTRADOR'],
                'busqueda_param_etiqueta'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'busqueda_id_etiqueta'=>[],


                // //Area
                'area_index'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'area_new'=>['DIG_ADMINISTRADOR'],
                'area_show'=>['DIG_OPERADOR','DIG_EDITOR','DIG_ADMINISTRADOR'],
                'area_edit'=>['DIG_ADMINISTRADOR'],
                'area_delete'=>['DIG_ADMINISTRADOR'],

                // Auditoria
                'auditoria_index'=>['DIG_ADMINISTRADOR','DIG_OPERADOR','DIG_EDITOR'],
                'auditoria_new'=>['DIG_ADMINISTRADOR'],
                'auditoria_show'=>['DIG_ADMINISTRADOR'],
                'auditoria_edit'=>['DIG_ADMINISTRADOR'],
                'auditoria_delete'=>['DIG_ADMINISTRADOR'],

                // Consulta
                'consultaMensaje'=>[],
                'consulta_index'=>['DIG_ADMINISTRADOR'],
                'consulta'=>[],
                'consulta_show'=>['DIG_ADMINISTRADOR'],
                'consulta_edit'=>['DIG_ADMINISTRADOR'],
                'consulta_delete'=>['DIG_ADMINISTRADOR'],

                // Tipo Consulta
                'tipo_consulta_index'=>['DIG_ADMINISTRADOR'],
                'tipo_consulta_new'=>['DIG_ADMINISTRADOR'],
                'tipo_consulta_show'=>['DIG_ADMINISTRADOR'],
                'tipo_consulta_edit'=>['DIG_ADMINISTRADOR'],
                'tipo_consulta_delete'=>['DIG_ADMINISTRADOR'],

                // Usuario
                'usuario_index'=>['DIG_OPERADOR'],
                'usuario_new'=>['DIG_OPERADOR'],
                'usuario_show'=>['DIG_OPERADOR'],
                'usuario_edit'=>['DIG_OPERADOR'],
                'usuario_delete'=>['DIG_OPERADOR'],

                // Tipo Norma Reparticion
                'tipo_norma_reparticion_index'=>['DIG_ADMINISTRADOR','DIG_OPERADOR','DIG_EDITOR'],
                'tipo_norma_reparticion_new'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_reparticion_show'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_reparticion_edit'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_reparticion_delete'=>['DIG_ADMINISTRADOR'],
                'reparticion_norma'=>['DIG_ADMINISTRADOR'],

                //Tipo Norma Rol
                'tipo_norma_rol_index'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_rol_new'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_rol_show'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_rol_edit'=>['DIG_ADMINISTRADOR'],
                'tipo_norma_rol_delete'=>['DIG_ADMINISTRADOR'],
                'rol_tipo_norma'=>['DIG_ADMINISTRADOR'],

                // // Entidades

                // // Plan
                // //'plan_index' => [], // Visible para todos
                // 'plan_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_habilitar' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_deshabilitar' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_postergar' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_ver_proyecto' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_imprimir_proyecto' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'plan_imprimir_obras' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],

            ];
            
            // Verifico que la ruta esté en la lista, de no estarlo se entiende que no se quiere aplicar una restricción de seguridad a la misma
            if (array_key_exists($routeName, $routeList)) {
                $roles = $routeList[$routeName];
                
                $status = 0;
                //dd($roles);
                // Recorro la lista de roles asignados a la ruta
                foreach ($roles as $rol) {
                    //si no hay nadie logeado=$this->session->get('sesion_id')=0;
                    //si hay alguien logeado=$this->session->get('sesion_id')=float;
                    //$rol es un rol del array de arriba
                    //this->session= a una sesion
                    $status = $this->seguridad->checkAccessAction($this->session->get('session_id'), $rol, $this->session, false);
                    // Posibles valores:
                    // 0 = no posee este rol
                    // 1 = posee el rol, no sigo iterando
                    // 2 = sesión caducada, no sigo iterando
                    
                    if ($status != 0) break;
                }
                if($this->session->get('session_id')==null && empty($roles)){
                    $status=1;
                }
                if($this->session->get('session_id')!=null && empty($roles)){
                    $status=1;
                }
                switch ($status) {
                    case 0:
                        // Si no tengo ninguno de los roles admitidos, redirijo a 404 Not Found
                        $event->setResponse(new RedirectResponse('/notRole'));
                        break;
                    case 1:
                        // No hago nada, ya que procedo con normalidad
                        break;
                    case 2:
                        // Si la sesión expiró, redirijo a Logout
                        $msj = array('message' => 'Error: la sesión ha expirado, por favor, vuelva a autenticarse.');
                        $this->session->getFlashBag()->set('alert alert-danger', $msj);
                        $event->setResponse(new RedirectResponse('/logout'));
                        break;
                }
            }
            // Se valida la sesión en el caso excepcional del acceso a rutas que no tienen configurada una restricción de roles
            else {
                if (
                    //($this->seguridad->checkAccessAction($this->session->get('session_id'), 'FP_ADMIN', $this->session, false) == 2)
                    ($this->seguridad->checkAccessAction($this->session->get('session_id'), 'DIG_OPERADOR', $this->session, false) == 2)
                    && ($routeName != 'logout') // Esta línea de código es extremadamente importante, evita un loop infinito cuando expira la sesión
                ) {
                    //entra si existe sesion_id (alguien se logueó) y la sesion expiro y no esta en logout y la ruta en la que se encuentra no esta en routeList
                    $mensaje = array('message' => 'Error: la sesión ha expirado, por favor, vuelva a autenticarse.');
                    $this->session->getFlashBag()->set('alert alert-danger', $mensaje);
                    $event->setResponse(new RedirectResponse('/logout'));
                }else{
                    //$event->setResponse(new RedirectResponse('/404'));
                }
            }
        // }
    }
    
    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
