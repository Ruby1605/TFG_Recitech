<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecetaRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HomeRecitechController extends AbstractController
{
    private HttpClientInterface $client;
    public function __construct(HttpClientInterface $client)
{
    $this->client = $client;
}

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
    
    #[Route('/valornutricional/{id}', name: 'valor_nutricional')]
    public function valorNutricional(int $id, RecetaRepository $recetaRepository): Response
    {
        $receta = $recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        //obtener json con info de la receta
  
        // ... dentro de tu método, por ejemplo en verReceta o donde lo necesites ...
        $palabrasClaveLista = [
            'Cerdo', 'Queso', 'Vegana', 'Pollo', 'Patatas', 'Saludable', 'Pescado',
            'Desayuno', 'Asado', 'Almuerzo', 'Horno', 'Salsa', 'Verduras', 'Fruta', 'Carne', 'Postre'
            
        ];

        $ingredientesClave = [
            'Mantequilla', 'Azúcar', 'Huevo', 'Harina', 'Leche', 'Sal', 'Aceite'
        ];

        // Mapeo para nombres de variables sin acentos
        $ingredientesVariables = [
            'Mantequilla' => 'IngMantequilla',
            'Azúcar'      => 'IngAzucar',
            'Huevo'       => 'IngHuevos',
            'Harina'      => 'IngHarina',
            'Leche'       => 'IngLeche',
            'Sal'         => 'IngSal',
            'Aceite'      => 'IngAceite',
        ];

        // Obtener palabras clave de la receta como array (en minúsculas)
        $palabrasReceta = [];
        if ($receta->getPalabrasClave()) {
            $palabrasReceta = array_map(
                fn($p) => mb_strtolower(trim($p)),
                explode(',', $receta->getPalabrasClave())
            );
        }

        // Obtener nombres de ingredientes de la receta
        $nombresIngredientes = [];
        foreach ($receta->getRecetaIngredientes() as $recetaIngrediente) {
            $nombresIngredientes[] = strtolower(trim($recetaIngrediente->getIngrediente()->getNombre()));
        }

        $jsonData = [
            "Minutos" => $receta->getTiempo(),
        ];

        // Palabras clave (comparando en minúsculas)
        foreach ($palabrasClaveLista as $palabra) {
            $jsonData[$palabra] = in_array(mb_strtolower($palabra), $palabrasReceta, true) ? 1 : 0;
        }

        // Porciones
        $jsonData["RecipeServings"] = $receta->getPorciones();

        // Número de ingredientes
        $jsonData["NumeroIngredientes"] = count($receta->getRecetaIngredientes());

        // Ingredientes clave
        foreach ($ingredientesClave as $ing) {
            $nombreVariable = $ingredientesVariables[$ing];
            $jsonData[$nombreVariable] = in_array(strtolower($ing), $nombresIngredientes, true) ? 1 : 0;
        }

        // Para obtener el JSON como string:
        $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);

        // Puedes hacer dump, return new Response($jsonString), o pasarlo a la vista
        $response = $this->client->request('POST', 'http://127.0.0.1:8000/predict/grasas', [
        'json' => $jsonData // $jsonData debe ser un array, no un string
        ]);

        $resultadoApiGrasas = $response->toArray();

        // Puedes hacer dump, return new Response($jsonString), o pasarlo a la vista
        $response = $this->client->request('POST', 'http://127.0.0.1:8000/predict/carbohidratos', [
        'json' => $jsonData // $jsonData debe ser un array, no un string
        ]);
        $resultadoApiCarbohidratos = $response->toArray();


        return $this->render('homerecitech/valornutricional.html.twig', [
            'receta' => $receta,
            'jsonData' => $jsonString,
            'resultadoApiGrasas' => $resultadoApiGrasas,
            'resultadoApiCarbohidratos' => $resultadoApiCarbohidratos,
        ]);
    }

}