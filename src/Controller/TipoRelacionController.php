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
        $arrayRoles=[];
        $idSession=$sesion->get('session_id')*1;
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
        $todosTipos = $tipoRelacionRepository->createQueryBuilder('p')
            ->getQuery();
        
        // Paginar los resultados de la consulta
        $tiposRelacion = $paginator->paginate(
            // Consulta Doctrine, no resultados
            $todosTipos,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            11
        );
        $tiposRelacion->setCustomParameters([
            'align' => 'center',
        ]);
        
        return $this->render('tipo_relacion/index.html.twig', [
            'tipo_relacions' => $tiposRelacion,
            'rol' => $rol,
            'roles'=>$arrayRoles,
        ]);
    }

    /**
     * @Route("/new", name="tipo_relacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TipoRelacionRepository $tipoRelacionRepository): Response
    {
        //inversoBase se creó para setearle un inverso "generico" cada vez que se cargue un tipo de relacion.
        //Esto se implemento para facilitar la carga de tipos de relaciones y su vinculacion con el inverso de verdad
        //Entonces, cada vez que se crea un tipo, se setea su inverso a inversoBase, y luego cuando se esta creando el inverso del tipo se que se acaba de crear,
        //se busca el unico que el inverso es $inversoBase y se vinculan ellos dos
        $inversoBase=$tipoRelacionRepository->findOneByNombre('Base');
        $idBase=$inversoBase->getId();
        $inv=$tipoRelacionRepository->findOneByInverso($idBase);
        $tipoRelacion = new TipoRelacion();

        $form = $this->createForm(TipoRelacionType::class, $tipoRelacion,['inverso'=>$inv]);
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
    public function delete(TipoRelacionRepository $tipoRelacionRepository,Request $request, TipoRelacion $tipoRelacion, EntityManagerInterface $entityManager): Response
    {
        // dd($tipoRelacion->getRela()->toArray());
        if(!empty($tipoRelacion->getRela()->toArray())){
            $this->addFlash(
                'errorDeleteTipoRela',
                "No se pudo eliminar este tipo de relación ya que hay normas que la están usando."
            );
            return $this->redirectToRoute('tipo_relacion_index',[],Response::HTTP_SEE_OTHER);
        }
        

        //$tipoRelacion=$tipoRelacionRepository->findOneById($id);
        $inverso=$tipoRelacion->getInverso();
        if($inverso){
            $inv=$tipoRelacionRepository->findOneById($inverso->getId());
            
            $tipoRelacion->setInverso(NULL);
            $inv->setInverso(NULL);
            
            $entityManager->persist($tipoRelacion);
            $entityManager->persist($inv);
            $entityManager->flush();
            // dd($inverso->getInverso());
            if ($this->isCsrfTokenValid('delete'.$tipoRelacion->getId(), $request->request->get('_token'))) {
                $entityManager->remove($tipoRelacion);
                $entityManager->remove($inverso);
                $entityManager->flush();
            }
        }else{
            $tipoRelacion->setInverso(NULL);
            $entityManager->persist($tipoRelacion);
            if ($this->isCsrfTokenValid('delete'.$tipoRelacion->getId(), $request->request->get('_token'))) {
                $entityManager->remove($tipoRelacion);
                
                $entityManager->flush();
        }
    }
        

        return $this->redirectToRoute('tipo_relacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
