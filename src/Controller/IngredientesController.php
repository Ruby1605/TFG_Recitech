<?php

namespace App\Controller;

use App\Entity\Ingrediente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredienteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador para la gestión de ingredientes.
 * Solo accesible para usuarios con ROLE_ADMIN.
 */
final class IngredientesController extends AbstractController
{
    // Repositorio de ingredientes y EntityManager para operaciones con la base de datos
    private IngredienteRepository $ingredienteRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor: inyecta el repositorio y el entity manager.
     */
    public function __construct(IngredienteRepository $recetaRepository, EntityManagerInterface $entityManager)
    {
        $this->ingredienteRepository = $recetaRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Listado de ingredientes.
     * Ruta: /ingredientes
     * Solo accesible para administradores.
     */
    #[Route('/ingredientes', name: 'gestion_ingredientes')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $ingredientes = $this->ingredienteRepository->findAll();
        return $this->render('ingredientes/index.html.twig', [
            'ingredientes' => $ingredientes,
        ]);
    }

    /**
     * Crear un nuevo ingrediente.
     * Ruta: /ingredientes/nuevo
     * Métodos: GET, POST
     * Solo accesible para administradores.
     */
    #[Route('/ingredientes/nuevo', name: 'nuevo_ingrediente', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function nuevo(Request $request): Response
    {
        $ingrediente = new Ingrediente();
        $form = $this->createForm(\App\Form\IngredienteType::class, $ingrediente);
        $form->handleRequest($request);

        // Si el formulario es válido, guardar el nuevo ingrediente
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ingrediente);
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_ingredientes');
        }

        // Renderiza el formulario de creación
        return $this->render('ingredientes/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Editar un ingrediente existente.
     * Ruta: /ingredientes/{id}/editar
     * Solo accesible para administradores.
     */
    #[Route('/ingredientes/{id}/editar', name: 'ingrediente_editar')]
    #[IsGranted('ROLE_ADMIN')]
    public function editarIngrediente(Request $request, int $id): Response
    {
        $ingrediente = $this->ingredienteRepository->find($id);

        if (!$ingrediente) {
            throw $this->createNotFoundException('Ingrediente no encontrado');
        }

        $form = $this->createForm(\App\Form\IngredienteType::class, $ingrediente);
        $form->handleRequest($request);

        // Si el formulario es válido, guardar los cambios
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_ingredientes');
        }

        // Renderiza el formulario de edición
        return $this->render('ingredientes/editar.html.twig', [
            'form' => $form->createView(),
            'ingrediente' => $ingrediente,
        ]);
    }

    /**
     * Eliminar un ingrediente.
     * Ruta: /ingredientes/{id}/eliminar
     * Solo accesible para administradores.
     */
    #[Route('/ingredientes/{id}/eliminar', name: 'ingrediente_eliminar')]
    #[IsGranted('ROLE_ADMIN')]
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
