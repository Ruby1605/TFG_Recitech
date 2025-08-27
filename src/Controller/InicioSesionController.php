<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controlador para la gestión de inicio de sesión de usuarios.
 */
class InicioSesionController extends AbstractController
{
    /**
     * Muestra el formulario de inicio de sesión y gestiona los errores de autenticación.
     * 
     * Ruta: /inicio-de-sesion
     * Nombre de la ruta: app_main_iniciosesion
     * 
     * @param AuthenticationUtils $authenticationUtils Utilidad para obtener errores y último usuario.
     * @return Response
     */
    #[Route('/inicio-de-sesion', name: 'app_main_iniciosesion')]
    public function iniciosesion(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtiene el último error de autenticación, si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            // Muestra un mensaje flash si hay error de autenticación
            $this->addFlash('danger', 'Nombre de usuario o contraseña incorrectos.');
        }

        // Renderiza la plantilla de inicio de sesión, pasando el último nombre de usuario introducido
        return $this->render('iniciosesion/iniciosesion.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }
}