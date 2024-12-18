<?php

namespace App\Controller;

use App\Repository\ProfessionalsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ProfessionalsRepository $professionalsRepository): Response
    {
        // Fetch all professionals
        $professionals = $professionalsRepository->findAll();

        // Pass professionals data to the Twig template
        return $this->render('index.html.twig', [
            'professionals' => $professionals,
        ]);
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(): Response
    {
        return $this->render('connexion.html.twig');
    }
}
