<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador para la gestión de la zona de administración.
 * Incluye la página principal del administrador y el cierre de sesión.
 */
final class AdministradorController extends AbstractController
{
    /**
     * Página principal del administrador.
     * 
     * Ruta: /administrador
     * Nombre de la ruta: administrador
     * 
     * Solo accesible para usuarios autenticados completamente.
     */
    #[Route('/administrador', name: 'administrador')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function administrador(): Response
    {
        // Renderiza la plantilla de la página de administrador
        return $this->render('administrador/index.html.twig');
    }

    /**
     * Ruta para cerrar sesión (logout).
     * 
     * Ruta: /logout
     * Nombre de la ruta: app_logout
     * Método: POST
     * 
     * Symfony gestiona el logout automáticamente, por lo que este método puede estar vacío.
     */
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        // El controlador puede estar vacío, Symfony gestionará el logout automáticamente
    }
}
