<?php

namespace App\Controller;

use App\Entity\Ingrediente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredienteRepository;
use Symfony\Component\HttpFoundation\Request;

final class IngredientesController extends AbstractController
{
    private IngredienteRepository $ingredienteRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(IngredienteRepository $recetaRepository, EntityManagerInterface $entityManager)
    {
        $this->ingredienteRepository = $recetaRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/ingredientes', name: 'gestion_ingredientes')]
    public function index(): Response
    {
        $ingredientes = $this->ingredienteRepository->findAll();
        return $this->render('ingredientes/index.html.twig', [
            'ingredientes' => $ingredientes,
        ]);
    
    }

    #[Route('/ingredientes/nuevo', name: 'nuevo_ingrediente', methods: ['GET', 'POST'])]
    public function nuevo(Request $request): Response
    {
        $ingrediente = new \App\Entity\Ingrediente();
        $form = $this->createForm(\App\Form\IngredienteType::class, $ingrediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ingrediente);
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_ingredientes');
        }

        return $this->render('ingredientes/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ingredientes/{id}/editar', name: 'ingrediente_editar')]
    public function editarIngrediente(Request $request, int $id): Response
    {
        $ingrediente = $this->ingredienteRepository->find($id);

        if (!$ingrediente) {
            throw $this->createNotFoundException('Ingrediente no encontrado');
        }

        $form = $this->createForm(\App\Form\IngredienteType::class, $ingrediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_ingredientes');
        }

        return $this->render('ingredientes/editar.html.twig', [
            'form' => $form->createView(),
            'ingrediente' => $ingrediente,
        ]);
    }

    #[Route('/ingredientes/{id}/eliminar', name: 'ingrediente_eliminar')]
    public function eliminarIngrediente(int $id): Response
    {
        $ingrediente = $this->ingredienteRepository->find($id);

        if (!$ingrediente) {
            throw $this->createNotFoundException('Ingrediente no encontrado');
        }

        $this->entityManager->remove($ingrediente);
        $this->entityManager->flush();

        return $this->redirectToRoute('gestion_ingredientes');
    }



}
