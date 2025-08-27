<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IngredienteRepository;
use App\Repository\RecetaRepository;

/**
 * Controlador para la funcionalidad "Mi Nevera".
 * Permite al usuario ver ingredientes y filtrar recetas según los ingredientes disponibles.
 */
class MiNeveraController extends AbstractController
{
    /**
     * Muestra la página principal de "Mi Nevera" con todos los ingredientes disponibles.
     * 
     * Ruta: /minevera
     * Nombre de la ruta: app_minevera
     */
    #[Route('/minevera', name: 'app_minevera')]
    public function minevera(IngredienteRepository $ingredienteRepository): Response
    {
        // Obtiene todos los ingredientes de la base de datos
        $ingredientes = $ingredienteRepository->findAll();

        // Renderiza la vista principal de "Mi Nevera"
        return $this->render('minevera/minevera.html.twig', [
            'ingredientes' => $ingredientes,
        ]);
    }

    /**
     * Filtra recetas según los ingredientes seleccionados y otros criterios (dificultad, calorías, tiempo).
     * 
     * Ruta: /minevera/filtrar
     * Nombre de la ruta: filtrar_recetas
     */
    #[Route('/minevera/filtrar', name: 'filtrar_recetas')]
    public function filtrar(
        Request $request,
        RecetaRepository $recetaRepository,
        IngredienteRepository $ingredienteRepository
    ): Response {
        // Obtiene los filtros desde la petición (GET)
        $dificultad = $request->query->get('dificultad');
        $caloriasMax = $request->query->get('calorias_max');
        $tiempoMax = $request->query->get('tiempo_max');
        $ingredientesSeleccionados = explode(',', $request->query->get('ingredientes_seleccionados', '')); // array de nombres

        // Construye la consulta para filtrar recetas según los criterios seleccionados
        $qb = $recetaRepository->createQueryBuilder('r');

        if ($dificultad) {
            $qb->andWhere('r.dificultad = :dificultad')
               ->setParameter('dificultad', $dificultad);
        }
        if ($caloriasMax) {
            $qb->andWhere('r.calorias <= :caloriasMax')
               ->setParameter('caloriasMax', $caloriasMax);
        }
        if ($tiempoMax) {
            $qb->andWhere('r.tiempo <= :tiempoMax')
               ->setParameter('tiempoMax', $tiempoMax);
        }

        // Obtiene las recetas filtradas
        $recetas = $qb->getQuery()->getResult();
        $ingredientes = $ingredienteRepository->findAll();

        // Calcula los ingredientes faltantes para cada receta respecto a los seleccionados por el usuario
        $recetasConFaltantes = [];
        foreach ($recetas as $receta) {
            $ingredientesReceta = [];
            foreach ($receta->getRecetaIngredientes() as $ri) {
                $ingredientesReceta[] = $ri->getIngrediente()->getNombre();
            }
            $faltantes = array_diff($ingredientesReceta, $ingredientesSeleccionados);
            $recetasConFaltantes[] = [
                'receta' => $receta,
                'faltantes' => $faltantes,
                'num_faltantes' => count($faltantes),
            ];
        }

        // Ordena las recetas de menor a mayor número de ingredientes a comprar
        usort($recetasConFaltantes, function($a, $b) {
            return $a['num_faltantes'] <=> $b['num_faltantes'];
        });

        // Renderiza la vista con las recetas filtradas y los ingredientes seleccionados
        return $this->render('minevera/mineverafiltrada.html.twig', [
            'ingredientes' => $ingredientes,
            'recetas_filtradas' => $recetasConFaltantes,
            'ingredientes_seleccionados' => $ingredientesSeleccionados,
        ]);
    }
}