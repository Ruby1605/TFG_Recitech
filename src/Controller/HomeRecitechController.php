<?php

// Definimos el espacio de nombres del controlador
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecetaRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Controlador principal para la Home y funcionalidades relacionadas con recetas y valor nutricional.
 */
final class HomeRecitechController extends AbstractController
{
    // Cliente HTTP para llamadas a APIs externas
    private HttpClientInterface $client;

    /**
     * Constructor: inyecta el cliente HTTP.
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Página principal de la aplicación.
     * Ruta: /home
     */
    #[Route('/home', name: 'app_home')]
    public function home(RecetaRepository $recetaRepository): Response
    {
        $recetas = $recetaRepository->findAll();

        // Renderiza la vista principal con todas las recetas
        return $this->render('homerecitech/homerecitech.html.twig', [
            'recetas' => $recetas,
        ]);
    }

    /**
     * Vista de una receta individual.
     * Ruta: /receta/{id}
     */
    #[Route('/receta/{id}', name: 'ver_receta')]
    public function verReceta(int $id, RecetaRepository $recetaRepository): Response
    {
        $receta = $recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        // Renderiza la vista de detalle de la receta
        return $this->render('homerecitech/verrecetas.html.twig', [
            'receta' => $receta,
        ]);
    }
    
    /**
     * Vista de valor nutricional de una receta.
     * Realiza llamadas a varias APIs externas para obtener predicciones nutricionales.
     * Ruta: /valornutricional/{id}
     */
    #[Route('/valornutricional/{id}', name: 'valor_nutricional')]
    public function valorNutricional(int $id, RecetaRepository $recetaRepository): Response
    {
        $receta = $recetaRepository->find($id);

        if (!$receta) {
            throw $this->createNotFoundException('Receta no encontrada');
        }

        // --- Construcción del JSON de entrada para la API ---
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

        // Obtener nombres de ingredientes de la receta (en minúsculas)
        $nombresIngredientes = [];
        foreach ($receta->getRecetaIngredientes() as $recetaIngrediente) {
            $nombresIngredientes[] = strtolower(trim($recetaIngrediente->getIngrediente()->getNombre()));
        }

        // Construcción del array de datos para la API
        $jsonData = [
            "Minutos" => $receta->getTiempo(),
        ];

        // Añadir palabras clave (1 si está presente, 0 si no)
        foreach ($palabrasClaveLista as $palabra) {
            $jsonData[$palabra] = in_array(mb_strtolower($palabra), $palabrasReceta, true) ? 1 : 0;
        }

        // Porciones
        $jsonData["RecipeServings"] = $receta->getPorciones();

        // Número de ingredientes
        $jsonData["NumeroIngredientes"] = count($receta->getRecetaIngredientes());

        // Ingredientes clave (1 si está presente, 0 si no)
        foreach ($ingredientesClave as $ing) {
            $nombreVariable = $ingredientesVariables[$ing];
            $jsonData[$nombreVariable] = in_array(strtolower($ing), $nombresIngredientes, true) ? 1 : 0;
        }

        // Convertir a JSON para mostrarlo en la vista si se desea
        $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);

        // --- Llamadas a las APIs externas de predicción nutricional ---
        // Grasas
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/grasas', [
            'json' => $jsonData
        ]);
        $resultadoApiGrasas = $response->toArray();

        // Carbohidratos
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/carbohidratos', [
            'json' => $jsonData
        ]);
        $resultadoApiCarbohidratos = $response->toArray();

        // Calorías
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/calorias', [
            'json' => $jsonData
        ]);
        $resultadoApiCalorias = $response->toArray();

        // Azúcar
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/azucar', [
            'json' => $jsonData
        ]);
        $resultadoApiAzucar = $response->toArray();

        // Proteína
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/proteina', [
            'json' => $jsonData
        ]);
        $resultadoApiProteina = $response->toArray();

        // Grasas saturadas
        $response = $this->client->request('POST', 'https://recetasapi-production.up.railway.app/predict/grasassaturadas', [
            'json' => $jsonData
        ]);
        $resultadoApiGrasasSaturadas = $response->toArray();

        // Renderiza la vista con todos los resultados de las APIs
        return $this->render('homerecitech/valornutricional.html.twig', [
            'receta' => $receta,
            'jsonData' => $jsonString,
            'resultadoApiGrasas' => $resultadoApiGrasas,
            'resultadoApiCarbohidratos' => $resultadoApiCarbohidratos,
            'resultadoApiCalorias' => $resultadoApiCalorias,
            'resultadoApiAzucar' => $resultadoApiAzucar,
            'resultadoApiProteina' => $resultadoApiProteina,
            'resultadoApiGrasasSaturadas' => $resultadoApiGrasasSaturadas,
        ]);
    }
}