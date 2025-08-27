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

/**
 * Controlador para la gestión del registro de nuevos usuarios.
 */
class RegistroController extends AbstractController
{
    /**
     * Muestra el formulario de registro y gestiona la creación de un nuevo usuario.
     * 
     * Ruta: /registro
     * Nombre de la ruta: app_registro
     * 
     * @param Request $request Petición HTTP.
     * @param EntityManagerInterface $em EntityManager para operaciones con la base de datos.
     * @param UserPasswordHasherInterface $passwordHasher Servicio para hashear contraseñas.
     * @return Response
     */
    #[Route('/registro', name: 'app_registro')]
    public function registrarse(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Si el formulario se ha enviado por POST
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Validación básica de campos 
            if ($username && $email && $password) {
                $usuario = new Usuario();
                $usuario->setNombre($username);
                $usuario->setEmail($email);
                $usuario->setRol('ROLE_USER');

                // Hashea la contraseña antes de guardarla
                $hashedPassword = $passwordHasher->hashPassword($usuario, $password);
                $usuario->setPassword($hashedPassword);

                // Guarda el usuario en la base de datos
                $em->persist($usuario);
                $em->flush();

                // Mensaje de éxito y redirección a inicio de sesión
                $this->addFlash('success', 'Usuario registrado correctamente. Ahora puedes iniciar sesión.');
                return $this->redirectToRoute('app_main_iniciosesion');
            } else {
                // Mensaje de error si faltan campos
                $this->addFlash('danger', 'Por favor, rellena todos los campos.');
            }
        }

        // Renderiza la plantilla del formulario de registro
        return $this->render('registro/registro.html.twig');
    }
}