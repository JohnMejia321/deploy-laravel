<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ControllerPrueba2 extends Controller
{
    public function obtenerIngredientesYGuardarEnCache(Request $request)
    {
        // Obtiene los ingredientes actuales de la caché o inicia con un array vacío
        $endpoint = 'https://recruitment.alegra.com/api/farmers-market/buy?ingredient=rice';

        $response = Http::get($endpoint);

        if ($response->successful()) {
          return "conexion exitosa";
        } else {
            return response()->json(['mensaje' => 'No se pudieron obtener ingredientes desde el endpoint.']);
        }
    }
}
