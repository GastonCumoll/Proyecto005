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
     * @Route("/Consul", name="coaaaA", methods={"GET","POST"})
     */
    public function consulta(Request $request){
        $nombre=$request->query->get('nombre');
        $correo=$request->query->get('correo');//string
        $tema=$request->query->get('tema');//string
        $telefono=$request->query->get('telefono');//string
        //if(!$request->request->get('etiquetas')){
        $consulta=$request->query->get('consulta');
        dump($nombre,$correo,$tema,$telefono);
        dd($consulta);
    }

    /**
     * @Route("/consulta", name="consulta", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoConsultaRepository $tipoConsultaRepository): Response
    {
        $nombre=$request->query->get('nombre');
        $correo=$request->query->get('correo');//string
        $tema=$request->query->get('tema');//string
        $telefono=$request->query->get('telefono');//string
        //if(!$request->request->get('etiquetas')){
        $texto=$request->query->get('consulta');
        
        $tipo=$tipoConsultaRepository->findByNombre($tema);
        $tipoConsulta=$tipo[0];
        
        $today=new DateTime();

        $consulta = new Consulta();
        $consulta->setNombre($nombre);
        $consulta->setEmail($correo);
        $consulta->setTipoConsulta($tipoConsulta);
        $consulta->setNumeroTel($telefono);
        $consulta->setTexto($texto);
        $consulta->setFechaYHora($today);
        $entityManager->persist($consulta);
        $entityManager->flush();

            return $this->redirectToRoute('consulta_index', [], Response::HTTP_SEE_OTHER);
        //}

        return $this->renderForm('consulta/new.html.twig', [
            'consultum' => $consultum,
            'form' => $form,
        ]);
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
