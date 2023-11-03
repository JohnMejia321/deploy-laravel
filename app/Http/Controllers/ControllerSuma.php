<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControllerSuma extends Controller{

    public function __construct()
    {
        // Inicializa la variable de sesión "total" a 0 si aún no existe
        if (!Session::has('total')) {
            Session::put('total', 0);
        }
    }

    public function sumar( $valor)
{
    // Obtiene el valor actual de la variable de sesión "total"
    $total = Session::get('total');

    // Suma el valor proporcionado en la petición
    $total = $valor+$total;

    // Actualiza la variable de sesión "total" con el nuevo valor
    Session::put('total', $total);

    // Devuelve una respuesta con el nuevo valor
    return response()->json(['total' => $total]);
}


public function almacenarValorEnSesion()
{
    // Para almacenar un valor en la sesión
    Session::put('mi_valor', 'Mi valor de prueba');

    // Para recuperar un valor de la sesión
    $miValor = Session::get('mi_valor');
    return $miValor;
}


}
