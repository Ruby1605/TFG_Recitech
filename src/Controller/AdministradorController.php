<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class AdministradorController extends AbstractController
{
    #[Route('/administrador', name: 'administrador')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function administrador(): Response
    {
        // Código de la página de administrador
        return $this->render('administrador/index.html.twig');
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        
    }
}
