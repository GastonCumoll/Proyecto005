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
        // if (!$this->seguridad->checkSessionActive($this->session->get('session_id'))) {
        //     $mensaje = array('message' => 'Error: la sesión ha expirado, por favor, vuelva a autenticarse.');
        //     $this->session->getFlashBag()->set('alert alert-danger', $mensaje);
        //     $event->setResponse(new RedirectResponse('/logout'));
        // }
        // else {
            $request = $event->getRequest();
            $routeName = $request->attributes->get('_route');
            //$routeParameters = $request->attributes->get('_route_params');

            // Sintaxis: nombreRuta => [roles]
            // Lista de roles: FP_ADMIN(-1), FP_DIR_PRESU(-1), FP_OPE_PRESU(-1), FP_SEC_SECRE(0), FP_OPE_SECRE(0), FP_DIR_REPAR(1), FP_OPE_REPAR(1)
            $routeList = [

                //Norma
                'norma_index'=>['DIG_OPERADOR'],
                'norma_show'=>['DIG_OPERADOR'],
                'norma_edit'=>['DIG_OPERADOR'],
                'texto_edit'=>['DIG_OPERADOR'],
                //'normas_ajax'=>['DIG_OPERADOR'],
                'mostrar_pdf'=>['DIG_OPERADOR'],
                'generar_pdf'=>['DIG_OPERADOR'],
                'norma_new'=>['DIG_OPERADOR'],
                'mostrar_texto'=>['DIG_OPERADOR'],
                'norma_delete'=>['DIG_OPERADOR'],

                //Relacion
                ''=>[''],

                //Tipo Norma
                ''=>[''],

                //Tipo Relacion
                ''=>[''],

                //Item
                ''=>[''],

                //Inicio 
                ''=>[''],

                //General
                ''=>[''],

                //Etiqueta
                ''=>[''],

                //Area
                ''=>[''],



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

                // // SecretariaHabilitada
                // 'secretaria_habilitada_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'secretaria_habilitada_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'secretaria_habilitada_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'secretaria_habilitada_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'secretaria_habilitada_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],

                // // ReparticionHabilitada
                // 'reparticion_habilitada_por_secretaria_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'reparticion_habilitada_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // //'reparticion_habilitada_show' => [],  // En desuso
                // //'reparticion_habilitada_edit' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],    // En desuso
                // 'reparticion_habilitada_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // TopeGasto
                // 'tope_gasto_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'tope_gasto_edit' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'tope_gasto_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // Programa
                // 'programa_index' => [],
                // 'programa_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'programa_show' => [],
                // //'programa_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'programa_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'secretariasHabilitadas_programas_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // Formulacion
                // //'formulacion_index' => [], // Visible para todos
                // //'formulacion_rejecteds' => [], // Visible para todos
                // 'formulacion_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // //'formulacion_show' => [],  // Visible para todos
                // 'formulacion_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'formulacion_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'formulacion_save' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'formulacion_send' => ['FP_ADMIN', 'FP_DIR_REPAR'],
                // 'formulacion_rectify' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],

                // // Partida
                // //'partida_index' => [], // Visible para todos
                // 'partida_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'partida_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'partida_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],

                // // Observacion
                // //'observacion_index' => [],    // Visible para todos
                // //'observacion_new' => [],  // Visible para todos
                // //'observacion_edit' => [], // Visible para todos
                // //'observacion_delete' => [],   // Visible para todos

                // // AReparticion
                // //'a_reparticion_index' => [], // Visible para todos
                // 'a_reparticion_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // //'a_reparticion_new_action' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'], // A definir
                // //'a_reparticion_show' => [], // Visible para todos
                // //'a_reparticion_edit' => [], // Deshabilitado por el momento
                // 'a_reparticion_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'a_reparticion_send' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_DIR_REPAR'],
                // 'a_reparticion_accept' => ['FP_ADMIN', 'FP_SEC_SECRE'],
                // 'a_reparticion_reject' => ['FP_ADMIN', 'FP_SEC_SECRE'],
                // 'a_reparticion_rectify' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // 'a_reparticion_excel_action' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_DIR_REPAR', 'FP_OPE_REPAR'],
                // //'a_reparticion_excel_download_action' => [],  // Visible para todos

                // // ASecretaria
                // //'a_secretaria_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'], // En desuso
                // 'a_secretaria_index_p' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'a_secretaria_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // //'a_secretaria_new_action' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'], // A definir
                // //'a_secretaria_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'a_secretaria_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'a_secretaria_send' => ['FP_ADMIN', 'FP_SEC_SECRE'],
                // 'a_secretaria_accept' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // //'a_secretaria_reject' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],    // En desuso

                // // GastoComun
                // 'gasto_comun_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_comun_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_comun_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_comun_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],

                // // GastoPlan
                // 'gasto_plan_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_plan_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_plan_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'gasto_plan_delete' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],

                // // DetalleAnteproyecto
                // 'detalle_anteproyecto_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'detalle_anteproyecto_new' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'detalle_anteproyecto_edit' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'detalle_imprimir_anteproyecto' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // ObraPublica
                // 'obra_publica_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'obra_publica_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'obra_publica_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'obra_publica_edit' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'obra_publica_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // Avance
                // 'avance_index' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'avance_new' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'avance_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'avance_edit' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],
                // 'avance_delete' => ['FP_ADMIN', 'FP_SEC_SECRE', 'FP_OPE_SECRE'],

                // // Parámetros

                // // Cuenta
                // 'cuenta_load' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // //'cuenta_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // //'cuenta_edit' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // //'cuenta_delete' => ['FP_ADMIN', 'FP_DIR_PRESU'],

                // // Estado
                // 'estado_new' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // //'estado_show' => ['FP_ADMIN', 'FP_DIR_PRESU', 'FP_OPE_PRESU'],
                // 'estado_edit' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // 'estado_delete' => ['FP_ADMIN', 'FP_DIR_PRESU'],

                // // TipoGasto
                // 'tipo_gasto_new' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // 'tipo_gasto_edit' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // 'tipo_gasto_delete' => ['FP_ADMIN', 'FP_DIR_PRESU'],

                // // GastoComun
                // 'gasto_comun_new' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // 'gasto_comun_edit' => ['FP_ADMIN', 'FP_DIR_PRESU'],
                // 'gasto_comun_delete' => ['FP_ADMIN', 'FP_DIR_PRESU'],
            ];
            
            // Verifico que la ruta esté en la lista, de no estarlo se entiende que no se quiere aplicar una restricción de seguridad a la misma
            if (array_key_exists($routeName, $routeList)) {
                $roles = $routeList[$routeName];
                $status = 0;

                // Recorro la lista de roles asignados a la ruta
                foreach ($roles as $rol) {
                    $status = $this->seguridad->checkAccessAction($this->session->get('session_id'), $rol, $this->session, false);

                    // Posibles valores:
                    // 0 = no posee este rol
                    // 1 = posee el rol, no sigo iterando
                    // 2 = sesión caducada, no sigo iterando
                    if ($status != 0) break;
                }

                switch ($status) {
                    case 0:
                        // Si no tengo ninguno de los roles admitidos, redirijo a 404 Not Found
                        $event->setResponse(new RedirectResponse('/404'));
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
                    $mensaje = array('message' => 'Error: la sesión ha expirado, por favor, vuelva a autenticarse.');
                    $this->session->getFlashBag()->set('alert alert-danger', $mensaje);
                    $event->setResponse(new RedirectResponse('/logout'));
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
