<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class InicioSesionController extends AbstractController
{
   
    #[Route('/inicio-de-sesion', name: 'app_main_iniciosesion')]
    public function iniciosesion(): Response
    {
        return $this->render('iniciosesion/iniciosesion.html.twig');
    }
    #[Route('/registro', name: 'app_registro')]
    public function registrarse(): Response
    {
        return $this->render('registro/registro.hmtl.twig');
    }
   #[Route('/', name: 'app_main_index')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('homerecitech/homerecitech.html.twig');
    }
    
}