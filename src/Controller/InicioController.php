<?php

namespace App\Controller;

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
    public function index(TituloRepository $tituloRepository, CapituloRepository $capituloRepository, TemaRepository $temaRepository, NormaRepository $normaRepository): Response
    {
        return $this->render('barra_de_navegacion/barra_de_navegacion.html.twig', [
            'titulos' => $tituloRepository->findAll(),
            'capitulos' => $capituloRepository->findAll(),
            'temas' => $temaRepository->findAll(),
            'normas' => $normaRepository->findAll(),
        ]);
    }
}