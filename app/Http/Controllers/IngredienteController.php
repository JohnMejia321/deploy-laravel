<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IngredienteController extends Controller{



    public function getIngredientData($ingredient)
    {
        // Define la URL del endpoint externo con el ingrediente proporcionado
        $endpoint = "https://recruitment.alegra.com/api/farmers-market/buy?ingredient=$ingredient";

        $response = Http::get($endpoint);

        // Realiza la solicitud GET al endpoint
        try {


            // Verifica si la respuesta es exitosa
            if ($response->getStatusCode() == 200) {
                // Decodifica el contenido JSON de la respuesta en un array
                $ingredientData = json_decode($response->getBody(), true);

                // Retorna la respuesta como JSON
                return response()->json($ingredientData);
            } else {
                // En caso de una respuesta no exitosa, retorna un mensaje de error
                return response()->json(['error' => 'Error al obtener datos del ingrediente']);
            }
        } catch (\Exception $e) {
            // En caso de excepciones, retorna un mensaje de error
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function index(){
        return "hola";
    }




}
