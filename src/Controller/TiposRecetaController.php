<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controlador para la gestión de la página de tipos de recetas.
 */
class TiposRecetaController extends AbstractController
{
    /**
     * Muestra la página principal de tipos de recetas.
     * 
     * Ruta: /tiporeceta
     * Nombre de la ruta: app_tiposrecetas
     * 
     * Renderiza la plantilla de tipos de recetas.
     */
    #[Route('/tiporeceta', name: 'app_tiposrecetas')]
    public function tiporeceta(): Response
    {
        // Renderiza la vista de tipos de recetas
        return $this->render('tiposrecetas/tiposrecetas.html.twig');
    }
}