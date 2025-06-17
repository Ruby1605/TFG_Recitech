<?php

namespace App\Controller;

use App\Entity\Receta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetaRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecetaType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function nueva(Request $request, SluggerInterface $slugger): Response
    {
        $receta = new Receta();
        $form = $this->createForm(RecetaType::class, $receta);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $fotoFile = $form->get('imagen')->getData();
                if ($fotoFile) {
                    $originalFilename = pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$fotoFile->guessExtension();

                    try {
                        $fotoFile->move(
                            $this->getParameter('fotos_recetas_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Manejar error si lo deseas
                    }

                    $receta->setImagen($newFilename);
                }

                $this->entityManager->persist($receta);
                $this->entityManager->flush();

                $this->addFlash('success', '¡La receta se guardó correctamente!');
                return $this->redirectToRoute('gestion_recetas');
            } else {
                // Recopilar errores
                $errores = [];
                foreach ($form->getErrors(true) as $error) {
                    $errores[] = $error->getMessage();
                }
                $this->addFlash('danger', 'Errores en el formulario: ' . implode(' | ', $errores));
            }
        }

        return $this->render('recetas/nueva.html.twig', [
            'form' => $form->createView(),
            'receta' => $receta,
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
    public function editar(Request $request, Receta $receta, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecetaType::class, $receta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Eliminar imagen si el checkbox está marcado
            if ($request->request->get('eliminar_imagen')) {
                if ($receta->getImagen()) {
                    $rutaImagen = $this->getParameter('fotos_recetas_directory') . '/' . $receta->getImagen();
                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                    $receta->setImagen(null);
                }
            }

            // Subir nueva imagen si se selecciona
            $imagenFile = $form->get('imagen')->getData();
            if ($imagenFile) {
                $originalFilename = pathinfo($imagenFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagenFile->guessExtension();

                $imagenFile->move(
                    $this->getParameter('fotos_recetas_directory'),
                    $newFilename
                );

                // Elimina la imagen anterior si existe
                if ($receta->getImagen()) {
                    $rutaImagen = $this->getParameter('fotos_recetas_directory') . '/' . $receta->getImagen();
                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }

                $receta->setImagen($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', '¡La receta se actualizó correctamente!');
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
        $this->addFlash('success', '¡La receta se eliminó correctamente!');

        return $this->redirectToRoute('gestion_recetas');
    }
}
