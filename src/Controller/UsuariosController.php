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

final class UsuariosController extends AbstractController
{
    private UsuarioRepository $usuarioRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/usuarios', name: 'gestion_usuarios')]
    public function listadoUsuarios(): Response
    {

        $usuarios = $this->usuarioRepository->findAll();

        return $this->render('usuarios/index.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    #[Route('/usuarios/nuevo', name: 'usuario_nuevo')]
    public function nuevoUsuario(Request $request): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_usuarios');
        }

        return $this->render('usuarios/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/usuarios/{id}', name: 'usuario_ver')]
    public function verUsuario(int $id): Response
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('usuarios/ver.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/usuarios/{id}/editar', name: 'usuario_editar')]
    public function editarUsuario(Request $request, int $id): Response
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('gestion_usuarios');
        }

        return $this->render('usuarios/editar.html.twig', [
            'form' => $form->createView(),
            'usuario' => $usuario,
        ]);
    }

    #[Route('/usuarios/{id}/eliminar', name: 'usuario_eliminar')]
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
