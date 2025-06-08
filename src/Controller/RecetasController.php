<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetaRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecetaType;

use Doctrine\ORM\EntityManagerInterface;

final class RecetasController extends AbstractController
{
    private RecetaRepository $recetaRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(RecetaRepository $recetaRepository, EntityManagerInterface $entityManager)
    {
        $this->recetaRepository = $recetaRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/recetas', name: 'gestion_recetas')]
    public function listadoRecetas(): Response
    {
        
        $recetas = $this->recetaRepository->findAll();
        
        return $this->render('recetas/index.html.twig', [
            'recetas' => $recetas,
        ]);
    }

    #[Route('/recetas/nueva', name: 'receta_nueva')]
    public function nuevaReceta(Request $request): Response
    {
        $receta = new \App\Entity\Receta();
        $form = $this->createForm(\App\Form\RecetaType::class, $receta);
        $form->handleRequest($request); 
       
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($receta);
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_recetas');
        }
        
        return $this->render('recetas/nueva.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recetas/{id}', name: 'receta_ver')]
    public function verReceta(int $id): Response
    {
        $receta = $this->recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        return $this->render('recetas/ver.html.twig', [
            'receta' => $receta,
        ]);
    }
    
    #[Route('/recetas/{id}/editar', name: 'receta_editar')]
    public function editarReceta(Request $request, int $id): Response
    {
        $receta = $this->recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        $form = $this->createForm(\App\Form\RecetaType::class, $receta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_recetas');
        }

        return $this->render('recetas/editar.html.twig', [
            'form' => $form->createView(),
            'receta' => $receta,
        ]);
    }
    
    #[Route('/recetas/{id}/eliminar', name: 'receta_eliminar')]
    public function eliminarReceta(int $id): Response
    {
        $receta = $this->recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        $this->entityManager->remove($receta);
        $this->entityManager->flush();

        return $this->redirectToRoute('gestion_recetas');
    }
}
