<?php

namespace App\Controller;

use DateTime;
use App\Entity\Consulta;
use App\Form\ConsultaType;
use App\Repository\ConsultaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoConsultaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/consulta")
 */
class ConsultaController extends AbstractController
{
    /**
     * @Route("/", name="consulta_index", methods={"GET"})
     */
    public function index(ConsultaRepository $consultaRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $todasConsultas=$consultaRepository->createQueryBuilder('p')->getQuery();
        $consultas = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $todasConsultas,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $consultas->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('consulta/index.html.twig', [
            'consultas' => $consultas,
        ]);
    }

    /**
     * @Route("/consultaMensaje/{bandera}", name="consultaMensaje", methods={"GET", "POST"})
     */
    public function consultaMensaje(Request $request, $bandera, EntityManagerInterface $entityManager,TipoConsultaRepository $tipoConsultaRepository): Response
    {
        //plantilla para saber si el mensaje fue enviado correctamente o no, dependiendo de la bandera;
        return $this->render('consulta/consultaEnviada.html.twig',[
            'bandera' => $bandera
        ]);
    }

    /**
     * @Route("/consulta", name="consulta", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoConsultaRepository $tipoConsultaRepository, ConsultaRepository $consultaRepository ): Response
    {
        //token un valor q pasa el recaptcha;
        $token = $_POST['token'];
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($cu, CURLOPT_POST, 1); //Indica el tipo de envio POST
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(
            [
                'secret' => '6LedpdAgAAAAAOtvcORbWBIy9OXpZTfccBKC5JCT',
                'response' => $token,
            ]
        ));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cu);
        curl_close($cu);
        $datos = json_decode($response, true);
        //lo anterior son funciones para saber si el recaptcha fue successfull o no;
        $mensaje="";
        //recuperamos los datos de la consulta, y si falta alguno, construimos el mensaje q falta dicho campo;
        if ($datos['success'] == false or $datos['score'] < 0.5) {
            dd($datos);
        } else {
            if($request->get('nombre') != ""){
                $nombre=$request->get('nombre');
            }
            else{
                $mensaje .= "Debe ingresar un nombre. \n";
            }
            if(($request->get('correo') != "") && (str_contains($request->get('correo'), '@'))){
                $correo=$request->get('correo');//string
            }
            else{
                $mensaje .= "El correo debe contener '@'. \n";
            }
            if(($request->get('telefono') != "")){
                $telefono=$request->get('telefono');//string
            }
            else{
                $mensaje .= "Debe ingresar un número telefonico. \n";
            }
            if(($request->get('consulta') != "")){
                $texto=$request->get('consulta');
            }
            else{
                $mensaje .= "Debe ingresar una consulta. \n";
            }

            if($mensaje != ""){
                $this->addFlash(
                    'notice',
                    $mensaje
            );
                return $this->redirectToRoute('inicio',[],Response::HTTP_SEE_OTHER);
            }
            $tema=$request->get('tema');//string
            //una vez que el captcha fue successfull, creamos la consulta, pero antes checkeamos que esa persona no haya hecho la misma consulta el mismo dia;
            $consultas = $consultaRepository->findByEmail($correo);
            //dd($consultas);
            $today = new DateTime();
            $todayFormato = $today->format("Y-m-d");
            foreach($consultas as $unaConsulta){
                $fechaConsulta = $unaConsulta->getFechaYHora()->format("Y-m-d");
                if(($unaConsulta->getTexto() == $texto) && ($todayFormato == $fechaConsulta)){
                    $bandera = true;
                    return $this->redirectToRoute('consultaMensaje',['bandera' => 1],Response::HTTP_SEE_OTHER);
                }   
            }
            $tipo=$tipoConsultaRepository->findByNombre($tema);
            $tipoConsulta=$tipo[0];
            
            $consulta = new Consulta();
            $consulta->setNombre($nombre);
            $consulta->setEmail($correo);
            $consulta->setTipoConsulta($tipoConsulta);
            $consulta->setNumeroTel($telefono);
            $consulta->setTexto($texto);
            $consulta->setFechaYHora($today);
            $entityManager->persist($consulta);
            $entityManager->flush();

            return $this->redirectToRoute('consultaMensaje',['bandera' => 0],Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="consulta_show", methods={"GET"})
     */
    public function show(Consulta $consultum): Response
    {
        return $this->render('consulta/show.html.twig', [
            'consultum' => $consultum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="consulta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Consulta $consultum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultaType::class, $consultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('consulta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consulta/edit.html.twig', [
            'consultum' => $consultum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="consulta_delete", methods={"POST"})
     */
    public function delete(Request $request, Consulta $consultum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consulta_index', [], Response::HTTP_SEE_OTHER);
    }
}
