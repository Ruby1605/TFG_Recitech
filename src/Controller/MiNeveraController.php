<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IngredienteRepository;
use App\Repository\RecetaRepository;

class MiNeveraController extends AbstractController
{
    #[Route('/minevera', name: 'app_minevera')]
    public function minevera(IngredienteRepository $ingredienteRepository): Response
    {
        $ingredientes = $ingredienteRepository->findAll();

        return $this->render('minevera/minevera.html.twig', [
            'ingredientes' => $ingredientes,
        ]);
    }

    #[Route('/minevera/filtrar', name: 'filtrar_recetas')]
    public function filtrar(
        Request $request,
        RecetaRepository $recetaRepository,
        IngredienteRepository $ingredienteRepository
    ): Response {
        $dificultad = $request->query->get('dificultad');
        $caloriasMax = $request->query->get('calorias_max');
        $tiempoMax = $request->query->get('tiempo_max');
        $ingredientesSeleccionados = explode(',', $request->query->get('ingredientes_seleccionados', '')); // array de nombres

        var_dump($ingredientesSeleccionados);
        die();
        
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

        $recetas = $qb->getQuery()->getResult();
        $ingredientes = $ingredienteRepository->findAll();

        // Calcular ingredientes a comprar para cada receta
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

        // Ordenar de menor a mayor ingredientes a comprar
        usort($recetasConFaltantes, function($a, $b) {
            return $a['num_faltantes'] <=> $b['num_faltantes'];
        });

        return $this->render('minevera/mineverafiltrada.html.twig', [
            'ingredientes' => $ingredientes,
            'recetas_filtradas' => $recetasConFaltantes,
            'ingredientes_seleccionados' => $ingredientesSeleccionados,
        ]);
    }
}