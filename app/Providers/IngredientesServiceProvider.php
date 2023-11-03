<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;


class IngredientesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Verifica si no se han inicializado los ingredientes y configura su valor a 5.
        if (!Cache::has('ingredientes')) {
            Cache::put('ingredientes', [
                'Tomato' => 5,
                'Lemon' => 5,
                'Potato' => 5,
                'Rice' => 5,
                'Ketchup' => 5,
                'Lettuce' => 5,
                'Onion' => 5,
                'Cheese' => 5,
                'Meat' => 5,
                'Chicken' => 5,
            ]);
        }
    }

    public function register()
    {
        //
    }
}
