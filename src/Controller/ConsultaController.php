<?php

namespace App\Controller;

use DateTime;
use App\Entity\Consulta;
use App\Form\ConsultaType;
use App\Repository\ConsultaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoConsultaRepository;
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
    public function index(ConsultaRepository $consultaRepository): Response
    {
        return $this->render('consulta/index.html.twig', [
            'consultas' => $consultaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/consultaMensaje/{bandera}", name="consultaMensaje", methods={"GET", "POST"})
     */
    public function consultaMensaje(Request $request, $bandera, EntityManagerInterface $entityManager,TipoConsultaRepository $tipoConsultaRepository): Response
    {
        return $this->render('consulta/consultaEnviada.html.twig',[
            'bandera' => $bandera
        ]);
    }

    /**
     * @Route("/consulta", name="consulta", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoConsultaRepository $tipoConsultaRepository, ConsultaRepository $consultaRepository ): Response
    {
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
        
        if ($datos['success'] == false or $datos['score'] < 0.5) {
            dd($datos);
        } else {
            $nombre=$request->get('nombre');
            $correo=$request->get('correo');//string
            $tema=$request->get('tema');//string
            $telefono=$request->get('telefono');//string
            //if(!$request->request->get('etiquetas')){
            $texto=$request->get('consulta');
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
