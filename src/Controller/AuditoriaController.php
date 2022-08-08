<?php

namespace App\Controller;

use App\Entity\Auditoria;
use App\Form\AuditoriaType;
use App\Service\SeguridadService;
use App\Repository\AuditoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/auditoria")
 */
class AuditoriaController extends AbstractController
{
    /**
     * @Route("/", name="auditoria_index", methods={"GET"})
     */
    public function index(SeguridadService $seguridad,AuditoriaRepository $auditoriaRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $sesion=$this->get('session');
        //dd($sesion);
        $idSession=$sesion->get('session_id')*1;
        if($seguridad->checkSessionActive($idSession)){
            // dd($idSession);
            $roles=json_decode($seguridad->getListRolAction($idSession), true);
            // dd($roles);
            $rol=$roles[0]['id'];
            // dd($rol);
        }else {
            $rol="";
        }

        $auditoriasT=$auditoriaRepository->createQueryBuilder('p')->orderBy('p.id','DESC')->getQuery();

        $auditorias = $paginator->paginate(
            
            // Consulta Doctrine, no resultados
            $auditoriasT,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $auditorias->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('auditoria/index.html.twig', [
            'auditorias' =>$auditorias,
            'rol' =>$rol,
        ]);
    }

    /**
     * @Route("/new", name="auditoria_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auditorium = new Auditoria();
        $form = $this->createForm(AuditoriaType::class, $auditorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auditorium);
            $entityManager->flush();

            return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auditoria/new.html.twig', [
            'auditorium' => $auditorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auditoria_show", methods={"GET"})
     */
    public function show(Auditoria $auditorium): Response
    {
        return $this->render('auditoria/show.html.twig', [
            'auditorium' => $auditorium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auditoria_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Auditoria $auditorium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuditoriaType::class, $auditorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auditoria/edit.html.twig', [
            'auditorium' => $auditorium,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="auditoria_delete", methods={"POST"})
     */
    public function delete(Request $request, Auditoria $auditorium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auditorium->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auditorium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auditoria_index', [], Response::HTTP_SEE_OTHER);
    }
}
