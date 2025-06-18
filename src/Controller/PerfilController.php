<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PerfilController extends AbstractController
{
    
    #[Route('/perfil', name: 'app_perfil')]
    public function perfil(): Response
    {
        $usuario = $this->getUser(); // Obtiene el usuario autenticado
        return $this->render('perfil/perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/perfil/editar', name: 'app_perfil_editar', methods: ['POST'])]
    public function editarPerfil(Request $request, EntityManagerInterface $em): Response
    {
        /** @var Usuario $usuario */
        $usuario =  $this->getUser();
        if (!$usuario) {
            throw $this->createAccessDeniedException();
        }

        $nombre = $request->request->get('nombre');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $usuario->setNombre($nombre);
        $usuario->setEmail($email);

        if ($password) {
            $usuario->setPassword($password);
        }

        $em->flush();

        $this->addFlash('success', 'Perfil actualizado correctamente.');
        return $this->redirectToRoute('app_perfil');
    }
}