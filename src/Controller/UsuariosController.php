<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsuarioRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UsuarioType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador para la gestión de usuarios.
 * Solo accesible para usuarios con ROLE_ADMIN.
 */
final class UsuariosController extends AbstractController
{
    // Repositorio de usuarios y EntityManager para operaciones con la base de datos
    private UsuarioRepository $usuarioRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor: inyecta el repositorio y el entity manager.
     */
    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Listado de todos los usuarios.
     * Ruta: /usuarios
     * Solo accesible para administradores.
     */
    #[Route('/usuarios', name: 'gestion_usuarios')]
    #[IsGranted('ROLE_ADMIN')]
    public function listadoUsuarios(): Response
    {
        $usuarios = $this->usuarioRepository->findAll();

        // Renderiza la vista con el listado de usuarios
        return $this->render('usuarios/index.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Crear un nuevo usuario.
     * Ruta: /usuarios/nuevo
     * Métodos: GET, POST
     * Solo accesible para administradores.
     */
    #[Route('/usuarios/nuevo', name: 'usuario_nuevo')]
    #[IsGranted('ROLE_ADMIN')]
    public function nuevoUsuario(Request $request): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        // Si el formulario es válido, guarda el nuevo usuario
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_usuarios');
        }

        // Renderiza el formulario de creación de usuario
        return $this->render('usuarios/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Ver los detalles de un usuario.
     * Ruta: /usuarios/{id}
     * Solo accesible para administradores.
     */
    #[Route('/usuarios/{id}', name: 'usuario_ver')]
    #[IsGranted('ROLE_ADMIN')]
    public function verUsuario(int $id): Response
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        // Renderiza la vista de detalle del usuario
        return $this->render('usuarios/ver.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Editar un usuario existente.
     * Ruta: /usuarios/{id}/editar
     * Métodos: GET, POST
     * Solo accesible para administradores.
     */
    #[Route('/usuarios/{id}/editar', name: 'usuario_editar')]
    #[IsGranted('ROLE_ADMIN')]
    public function editarUsuario(Request $request, int $id): Response
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        // Si el formulario es válido, guarda los cambios
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_usuarios');
        }

        // Renderiza el formulario de edición de usuario
        return $this->render('usuarios/editar.html.twig', [
            'form' => $form->createView(),
            'usuario' => $usuario,
        ]);
    }

    /**
     * Eliminar un usuario.
     * Ruta: /usuarios/{id}/eliminar
     * Solo accesible para administradores.
     */
    #[Route('/usuarios/{id}/eliminar', name: 'usuario_eliminar')]
    #[IsGranted('ROLE_ADMIN')]
    public function eliminarUsuario(int $id): Response
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $this->entityManager->remove($usuario);
        $this->entityManager->flush();

        return $this->redirectToRoute('gestion_usuarios');
    }
}
