<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class MiNeveraController extends AbstractController
{
    #[Route('/minevera', name: 'app_minevera')]
    public function minevera(): Response
    {
        return $this->render('minevera/minevera.html.twig');
    }
    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('homerecitech/homerecitech.html.twig');
    }
    #[Route('/tiporeceta', name: 'app_tiposrecetas')]
    public function tiporeceta(): Response
    {
        return $this->render('tiposrecetas\tiposrecetas.html.twig');
    }
    #[Route('/perfil', name: 'app_perfil')]
    public function perfil(): Response
    {
        return $this->render('perfil/perfil.html.twig');
    }
}