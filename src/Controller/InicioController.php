<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\TemaRepository;
use App\Repository\NormaRepository;
use App\Repository\TituloRepository;
use App\Repository\CapituloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/inicio")
 */
class InicioController extends AbstractController
{
    /**
     * @Route("/", name="Inicio", methods={"GET"})
     */
    public function index(ItemRepository $items, TituloRepository $tituloRepository, CapituloRepository $capituloRepository, TemaRepository $temaRepository, NormaRepository $normaRepository): Response
    {
        


        return $this->render('indiceDigesto/indiceDigesto.html.twig', [
            'titulos' => $tituloRepository->findAll(),
            'capitulos' => $capituloRepository->findAll(),
            'temas' => $temaRepository->findAll(),
            'normas' => $normaRepository->findAll(),
            'items' => $items->findAll(),
        ]);
    }

    /**
     * @Route("/paginaPrincipal", name="pagina_principal", methods={"GET"})
     */
    public function paginaPrincipal(TituloRepository $tituloRepository, CapituloRepository $capituloRepository, TemaRepository $temaRepository, NormaRepository $normaRepository): Response
    {
        return $this->render('base.html.twig', [
            'titulos' => $tituloRepository->findAll(),
            'capitulos' => $capituloRepository->findAll(),
            'temas' => $temaRepository->findAll(),
            'normas' => $normaRepository->findAll(),
        ]);
    }
}