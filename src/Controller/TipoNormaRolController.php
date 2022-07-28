<?php

namespace App\Controller;

use App\Entity\TipoNormaRol;
use App\Form\TipoNormaRolType;
// use App\Form\TipoNormaRol1Type;
use App\Service\SeguridadService;
use App\Repository\AreaRepository;
use App\Repository\TipoNormaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TipoNormaRolRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TipoNormaReparticionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tipo/norma/rol")
 */
class TipoNormaRolController extends AbstractController
{
    /**
     * @Route("/", name="tipo_norma_rol_index", methods={"GET"})
     */
    public function index(TipoNormaRolRepository $tipoNormaRolRepository): Response
    {
        return $this->render('tipo_norma_rol/index.html.twig', [
            'tipo_norma_rols' => $tipoNormaRolRepository->findAll(),
        ]);
    }

    /**
     * @Route("/rolTipoNorma/{id}", name="rol_tipo_norma", methods={"GET"})
     */
    public function rolTipoNorma(TipoNormaReparticionRepository $tipoNormaReparticionRepository,TipoNormaRepository $tipoNormaRepository,$id,AreaRepository $areaRepository,SeguridadService $seguridad): Response
    {
        $rolesTipo=[];
        $tipoNorma=$tipoNormaRepository->findById($id);
        //$tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $rolDeTipo=$tipoNorma[0]->getTipoNormaRoles();
        // dd($rolDeTipo);
        foreach ($rolDeTipo as $r) {
            $rolesTipo[]=$r;
        }
        // if(!$rolesTipo){
        //     $rolesTipo[]="";
        // }

        

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
            $idReparticion = $seguridad->getIdReparticionAction($idSession);

            $reparticionUsuario = $areaRepository->find($idReparticion);
            $normasUsuario = [];
            //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
            foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
                $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getId();
            }
        //dd($rolesTipo);
        //dd($reparticionesDeNorma);
        return $this->render('tipo_norma_rol/index.html.twig', [
            'tipoNorma' => $tipo,
            'rol'=>$rol,
            'roles'=>$rolesTipo
        ]);
    }

    /**
     * @Route("/new/{id}", name="tipo_norma_rol_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipo=$tipoNormaRepository->findById($id);
        
        $tipoNormaRol = new TipoNormaRol();
        $tipoNormaRol->setTipoNorma($tipo[0]);
        $form = $this->createForm(TipoNormaRolType::class, $tipoNormaRol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoNormaRol);
            $entityManager->flush();

            return $this->redirectToRoute('rol_tipo_norma', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_rol/new.html.twig', [
            'tipo_norma_rol' => $tipoNormaRol,
            'form' => $form,
            'tipoNorma' => $tipo[0]
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_rol_show", methods={"GET"})
     */
    public function show(TipoNormaRol $tipoNormaRol): Response
    {
        return $this->render('tipo_norma_rol/show.html.twig', [
            'tipo_norma_rol' => $tipoNormaRol,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_norma_rol_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoNormaRol $tipoNormaRol, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipoNorma=$tipoNormaRepository->findById($id);

        $form = $this->createForm(TipoNormaRolType::class, $tipoNormaRol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rol_tipo_norma', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_rol/edit.html.twig', [
            'tipo_norma_rol' => $tipoNormaRol,
            'form' => $form,
            'tipoNorma'=>$tipoNorma[0],
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_rol_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoNormaRol $tipoNormaRol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoNormaRol->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNormaRol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_norma_rol_index', [], Response::HTTP_SEE_OTHER);
    }
}
