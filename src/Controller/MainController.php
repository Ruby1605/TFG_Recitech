<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;


final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/acerca-de', name: 'app_main_acercade')]
    public function about(): Response
    {
        return $this->render('main/acercade.html.twig');
    }
    
   
   
    
}
