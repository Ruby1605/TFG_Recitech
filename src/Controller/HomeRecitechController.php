<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecetaRepository;

final class HomeRecitechController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function home(RecetaRepository $recetaRepository): Response
    {
        $recetas = $recetaRepository->findAll();

        return $this->render('homerecitech/homerecitech.html.twig', [
            'recetas' => $recetas,
        ]);
    }

    #[Route('/receta/{id}', name: 'ver_receta')]
    public function verReceta(int $id, RecetaRepository $recetaRepository): Response
    {
        $receta = $recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        return $this->render('homerecitech/verrecetas.html.twig', [
            'receta' => $receta,
        ]);
    }
}