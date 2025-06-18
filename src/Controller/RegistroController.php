<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistroType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'app_registro')]
    public function registrarse(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Validación básica (puedes mejorarla)
            if ($username && $email && $password) {
                $usuario = new Usuario();
                $usuario->setNombre($username);
                $usuario->setEmail($email);
                $usuario->setPassword($password);
                $usuario->setRol('ROLE_USER');

                $usuario->setPassword($password);

                $em->persist($usuario);
                $em->flush();

                $this->addFlash('success', 'Usuario registrado correctamente. Ahora puedes iniciar sesión.');
                return $this->redirectToRoute('app_main_iniciosesion');
            } else {
                $this->addFlash('danger', 'Por favor, rellena todos los campos.');
            }
        }

        return $this->render('registro/registro.html.twig');
    }
   

}