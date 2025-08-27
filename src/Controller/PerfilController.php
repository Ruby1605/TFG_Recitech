<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controlador para la gestión del perfil de usuario.
 * Permite ver y editar los datos del usuario autenticado.
 */
class PerfilController extends AbstractController
{
    /**
     * Muestra la página de perfil del usuario autenticado.
     * 
     * Ruta: /perfil
     * Nombre de la ruta: app_perfil
     */
    #[Route('/perfil', name: 'app_perfil')]
    public function perfil(): Response
    {
        // Obtiene el usuario actualmente autenticado
        $usuario = $this->getUser();

        // Renderiza la vista del perfil, pasando el usuario a la plantilla
        return $this->render('perfil/perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Permite editar los datos del perfil del usuario autenticado.
     * 
     * Ruta: /perfil/editar
     * Nombre de la ruta: app_perfil_editar
     * Método: POST
     */
    #[Route('/perfil/editar', name: 'app_perfil_editar', methods: ['POST'])]
    public function editarPerfil(Request $request, EntityManagerInterface $em): Response
    {
        /** @var Usuario $usuario */
        $usuario =  $this->getUser();
        if (!$usuario) {
            // Si no hay usuario autenticado, lanza excepción de acceso denegado
            throw $this->createAccessDeniedException();
        }

        // Obtiene los datos enviados desde el formulario
        $nombre = $request->request->get('nombre');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Actualiza los datos del usuario
        $usuario->setNombre($nombre);
        $usuario->setEmail($email);

        // Si se ha enviado una nueva contraseña, la actualiza (debería codificarse)
        if ($password) {
            $usuario->setPassword($password);
        }

        // Guarda los cambios en la base de datos
        $em->flush();

        // Muestra un mensaje de éxito y redirige al perfil
        $this->addFlash('success', 'Perfil actualizado correctamente.');
        return $this->redirectToRoute('app_perfil');
    }
}