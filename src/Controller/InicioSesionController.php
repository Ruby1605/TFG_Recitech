<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class InicioSesionController extends AbstractController
{
   
    #[Route('/inicio-de-sesion', name: 'app_main_iniciosesion')]
    public function iniciosesion(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('danger', 'Nombre de usuario o contraseña incorrectos.');
        }

        return $this->render('iniciosesion/iniciosesion.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }
    
   
   
    
}