<?php

namespace App\Controller;

use App\Entity\TipoRelacion;
use App\Form\TipoRelacionType;
use App\Service\SeguridadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoRelacionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/relacion")
 */
class TipoRelacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_relacion_index", methods={"GET"})
     */
    public function index(SeguridadService $seguridad,TipoRelacionRepository $tipoRelacionRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $sesion=$this->get('session');
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
        // Encuentre todos los datos en la tabla de Citas, filtre su consulta como necesite
        $todosTipos = $tipoRelacionRepository->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $tiposRelacion = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todosTipos,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        
        return $this->render('tipo_relacion/index.html.twig', [
            'tipo_relacions' => $tiposRelacion,
            'rol' => $rol,
        ]);
    }

    /**
     * @Route("/new", name="tipo_relacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoRelacionRepository $tipoRelacionRepository): Response
    {
        $inversoBase=$tipoRelacionRepository->findOneById(0);
        //dd($inversoBase);
        $tipoRelacion = new TipoRelacion();
        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $inverso =$form['inverso']->getData();
            if($inverso!=null){
                $inverso->setInverso($tipoRelacion);
                $entityManager->persist($tipoRelacion);
                $entityManager->persist($inverso);
            }else{
                $tipoRelacion->setPrioridad(1);
                $tipoRelacion->setInverso($inversoBase);
            }

            $entityManager->persist($tipoRelacion);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_relacion/new.html.twig', [
            'tipo_relacion' => $tipoRelacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_relacion_show", methods={"GET"})
     */
    public function show(TipoRelacion $tipoRelacion): Response
    {
        return $this->render('tipo_relacion/show.html.twig', [
            'tipo_relacion' => $tipoRelacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_relacion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoRelacion $tipoRelacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_relacion/edit.html.twig', [
            'tipo_relacion' => $tipoRelacion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_relacion_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoRelacion $tipoRelacion, EntityManagerInterface $entityManager): Response
    {
        //$tipoRelacion=$tipoRelacionRepository->findOneById($id);
        $inverso=$tipoRelacion->getInverso();
        $tipoRelacion->setInverso(NULL);
        $inverso->setInverso(NULL);
        $entityManager->persist($tipoRelacion);
        $entityManager->persist($inverso);
        $entityManager->flush();
        // dd($inverso->getInverso());
        if ($this->isCsrfTokenValid('delete'.$tipoRelacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoRelacion);
            $entityManager->remove($inverso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
