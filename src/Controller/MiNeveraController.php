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
    
}