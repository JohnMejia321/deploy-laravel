<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class RecetasController extends Controller
{
    public function obtenerRecetaAleatoria()
    {
        // Obtiene los ingredientes desde la caché o inicializa con 5 unidades de cada uno.
        $ingredientes = Cache::get('ingredientes', [
            'Tomato' => 10,
            'Lemon' => 5,
            'Potato' => 5,
            'Rice' => 5,
            'Ketchup' => 5,
            'Lettuce' => 5,
            'Onion' => 50,
            'Cheese' => 5,
            'Meat' => 5,
            'Chicken' => 5,
        ]);

        $recetas = [
            [
                'nombre' => 'Ensalada de Pollo con Limón',
                'ingredientes' => [
                    'Lemon' => 2,
                    'Lettuce' => 3,
                    'Chicken' => 1,
                ],
                'descripcion' => 'Una refrescante ensalada de pollo con limón y lechuga.',
            ],
            [
                'nombre' => 'Sopa de Tomate y Cebolla',
                'ingredientes' => [
                    'Tomato' => 1,
                    'Onion' => 2,
                ],
                'descripcion' => 'Una sopa sabrosa y reconfortante de tomate y cebolla.',
            ],
            [
                'nombre' => 'Puré de Papas con Queso',
                'ingredientes' => [
                    'Potato' => 3,
                    'Cheese' => 1,
                ],
                'descripcion' => 'Puré de papas suave y cremoso con queso.',
            ],
            [
                'nombre' => 'Arroz con Salsa de Tomate',
                'ingredientes' => [
                    'Rice' => 2,
                    'Tomato' => 3,
                ],
                'descripcion' => 'Arroz cocido con una deliciosa salsa de tomate.',
            ],
            [
                'nombre' => 'Hamburguesa con Ketchup',
                'ingredientes' => [
                    'Meat' => 1,
                    'Ketchup' => 1,
                    'Lettuce' => 2,
                    'Cheese' => 1,
                ],
                'descripcion' => 'Una jugosa hamburguesa con ketchup, lechuga y queso.',
            ],
            [
                'nombre' => 'Pollo al Limón con Cebolla',
                'ingredientes' => [
                    'Lemon' => 1,
                    'Chicken' => 1,
                    'Onion' => 2,
                ],
                'descripcion' => 'Pollo marinado en limón y cebolla, asado a la perfección.',
            ],
        ];

        // Selecciona una receta aleatoria
        $recetaAleatoria = $recetas[array_rand($recetas)];

        $ingredienteFaltante = null;
        $minFaltante = PHP_INT_MAX; // Inicializa con un valor muy grande para encontrar el mínimo

        foreach ($recetaAleatoria['ingredientes'] as $ingrediente => $cantidad) {
            if (
                !array_key_exists($ingrediente, $ingredientes) ||
                $ingredientes[$ingrediente] < $cantidad
            ) {
                $faltante = $cantidad - ($ingredientes[$ingrediente] ?? 0);
                if ($faltante < $minFaltante) {
                    $minFaltante = $faltante;
                    $ingredienteFaltante = $ingrediente;
                }
            }
        }

        if ($ingredienteFaltante !== null) {


            // Realiza una solicitud al endpoint para obtener más unidades del ingrediente que falta
            $endpoint = "https://recruitment.alegra.com/api/farmers-market/buy?ingredient=".  strtolower($ingredienteFaltante);

            $response = Http::get($endpoint);

            try {
                if ($response->getStatusCode() == 200) {
                    $ingredientData = json_decode($response->getBody(), true);
                    $cantidadComprada = $ingredientData['quantitySold'];

                    if (!array_key_exists($ingredienteFaltante, $ingredientes)) {
                        $ingredientes[$ingredienteFaltante] = $cantidadComprada;
                    } else {
                        $ingredientes[$ingredienteFaltante] += $cantidadComprada;
                    }

                    // Resta la cantidad de ingredientes utilizados en la receta
                    foreach ($recetaAleatoria['ingredientes'] as $ingrediente => $cantidad) {
                        $ingredientes[$ingrediente] -= $cantidad;
                    }

                    // Almacena la cantidad restante de ingredientes en la caché
                    Cache::put('ingredientes', $ingredientes);

                    // Agrega la cantidad restante de ingredientes a la respuesta
                    $recetaAleatoria['ingredientes_restantes'] = $ingredientes;

                    return response()->json($recetaAleatoria);
                } else {
                    return response()->json(['error' => 'Error al obtener datos del ingrediente']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }






        // Reduce la cantidad de ingredientes utilizados
        foreach ($recetaAleatoria['ingredientes'] as $ingrediente => $cantidad) {
            $ingredientes[$ingrediente] -= $cantidad;
        }

        // Almacena la cantidad restante de ingredientes en la caché
        Cache::put('ingredientes', $ingredientes);

        // Agrega la cantidad restante de ingredientes a la respuesta
        $recetaAleatoria['ingredientes_restantes'] = $ingredientes;

        return response()->json($recetaAleatoria);
    }
}
