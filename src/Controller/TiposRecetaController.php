<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class TiposRecetaController extends AbstractController
{
    #[Route('/tiporeceta', name: 'app_tiposrecetas')]
    public function tiporeceta(): Response
    {
        return $this->render('tiposrecetas\tiposrecetas.html.twig');
    }
    
}