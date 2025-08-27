<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador principal de la aplicación.
 * Gestiona la página de inicio y la página "Acerca de".
 */
final class MainController extends AbstractController
{
    /**
     * Página de inicio de la aplicación.
     * 
     * Ruta: /
     * Nombre de la ruta: app_main_index
     * 
     * Renderiza la plantilla principal de bienvenida.
     */
    #[Route('/', name: 'app_main_index')]
    public function index(): Response
    {
        // Renderiza la vista de la página principal
        return $this->render('main/index.html.twig');
    }

    /**
     * Página "Acerca de" de la aplicación.
     * 
     * Ruta: /acerca-de
     * Nombre de la ruta: app_main_acercade
     * 
     * Renderiza la plantilla con información sobre la aplicación.
     */
    #[Route('/acerca-de', name: 'app_main_acercade')]
    public function about(): Response
    {
        // Renderiza la vista de la página "Acerca de"
        return $this->render('main/acercade.html.twig');
    }
}
