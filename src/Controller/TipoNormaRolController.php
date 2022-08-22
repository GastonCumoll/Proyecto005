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
    //este metodo muestra los roles que pueden acceder a un tipo de norma en especifico,el parametro id= id del tipo de norma
    public function rolTipoNorma(TipoNormaReparticionRepository $tipoNormaReparticionRepository,TipoNormaRepository $tipoNormaRepository,$id,AreaRepository $areaRepository,SeguridadService $seguridad): Response
    {
        $rolesTipo=[];
        $tipoNorma=$tipoNormaRepository->findById($id);
        //$tipoNorma=$tipoNormaRepository->findById($id);
        $tipo=$tipoNorma[0];
        $rolDeTipo=$tipoNorma[0]->getTipoNormaRoles();
        foreach ($rolDeTipo as $r) {
            $rolesTipo[]=$r;
        }

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
        $idReparticion = $seguridad->getIdReparticionAction($idSession);

        $reparticionUsuario = $areaRepository->find($idReparticion);
        $normasUsuario = [];
        //obtengo la reparticion del usuario para poder deshabilitar los botones edit de los registros de la tabla que no sean de la repartición del mismo
        foreach($reparticionUsuario->getTipoNormaReparticions() as $unTipoNorma){
            $normasUsuario[] = $unTipoNorma->getTipoNormaId()->getId();
        }

        return $this->render('tipo_norma_rol/index.html.twig', [
            'tipoNorma' => $tipo,
            'rol'=>$rol,
            'rolesTipo'=>$rolesTipo,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/new/{id}", name="tipo_norma_rol_new", methods={"GET", "POST"})
     */
    //este metodo le agrega un rol a un tipo de norma, su id es enviado por el parametro id.
    public function new(Request $request, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        $tipo=$tipoNormaRepository->findById($id);
        
        $tipoNormaRol = new TipoNormaRol();
        $tipoNormaRol->setTipoNorma($tipo[0]);
        $form = $this->createForm(TipoNormaRolType::class, $tipoNormaRol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arreglo=[];
            //dd($tipoNorma[0]->getTipoNormaReparticions()->toArray());
            foreach ($tipo[0]->getTipoNormaRoles()->toArray() as $r) {
                $arreglo[]=$r->getNombreRol();
            }
            
            if(in_array($form['nombreRol']->getData(),$arreglo)){
                $this->addFlash(
                    'notice',
                    "Este rol ya existe en el tipo de norma ".$tipo[0]->getNombre()
                );
                return $this->renderForm('tipo_norma_rol/new.html.twig', [
                    'tipo_norma_rol' => $tipoNormaRol,
                    'form' => $form,
                    'tipoNorma' => $tipo[0]
                ]);
            }
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
     * @Route("/{id}/edit/{t}", name="tipo_norma_rol_edit", methods={"GET", "POST"})
     */
    //este metodo se usa para editar el rol de un tipo de norma, el parametro "id" se refiere al id de la relacion entre un tipo de norma y un rol,
    //y t se refiere al id de tipo de norma
    public function edit($t,Request $request, TipoNormaRolRepository $tipoNormaRolRepository, EntityManagerInterface $entityManager,$id,TipoNormaRepository $tipoNormaRepository): Response
    {
        //busca el tipo de norma y la relacion entre un tipo de norma y un rol, que se encuentra en la tabla de $tipoNormaRolRepository;
        $tipoNorma=$tipoNormaRepository->findOneById($t);
        $tipoN=$tipoNormaRolRepository->findOneById($id);

        $form = $this->createForm(TipoNormaRolType::class, $tipoN);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $rol=$form->get('nombreRol')->getData();
            $tipoN->setNombreRol($rol);
            $entityManager->persist($tipoN);
            $entityManager->flush();

            return $this->redirectToRoute('rol_tipo_norma', ['id'=>$t], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_norma_rol/edit.html.twig', [
            'tipo_norma_rol' => $tipoN,
            'form' => $form,
            'tipoNorma'=>$tipoNorma,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_norma_rol_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoNormaRol $tipoNormaRol, EntityManagerInterface $entityManager): Response
    {
        $tipo=$tipoNormaRol->getTipoNorma()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$tipoNormaRol->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoNormaRol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rol_tipo_norma', ['id'=>$tipo], Response::HTTP_SEE_OTHER);
    }
}
