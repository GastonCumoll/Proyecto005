<?php

namespace App\Controller;

use App\Entity\TipoConsulta;
use App\Form\TipoConsultaType;
use App\Service\SeguridadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoConsultaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/consulta")
 */
class TipoConsultaController extends AbstractController
{
    /**
     * @Route("/", name="tipo_consulta_index", methods={"GET"})
     */
    public function index(TipoConsultaRepository $tipoConsultaRepository,SeguridadService $seguridad): Response
    {

        $sesion=$this->get('session');
        $idSession=$sesion->get('session_id')*1;
        $arrayRoles=[];
        if($seguridad->checkSessionActive($idSession)){
            
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            foreach ($roles as $unRol) {
                $arrayRoles[]=$unRol['id'];
            }
            // dd($rol);
        }else {
            $rol="";
        }

        return $this->render('tipo_consulta/index.html.twig', [
            'tipo_consultas' => $tipoConsultaRepository->findAll(),
            'rol'=>$rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/new", name="tipo_consulta_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoConsultum = new TipoConsulta();
        $form = $this->createForm(TipoConsultaType::class, $tipoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoConsultum);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_consulta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_consulta/new.html.twig', [
            'tipo_consultum' => $tipoConsultum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_consulta_show", methods={"GET"})
     */
    public function show(TipoConsulta $tipoConsultum): Response
    {
        return $this->render('tipo_consulta/show.html.twig', [
            'tipo_consultum' => $tipoConsultum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_consulta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoConsulta $tipoConsultum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoConsultaType::class, $tipoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_consulta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_consulta/edit.html.twig', [
            'tipo_consultum' => $tipoConsultum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_consulta_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoConsulta $tipoConsultum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoConsultum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoConsultum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_consulta_index', [], Response::HTTP_SEE_OTHER);
    }
}
