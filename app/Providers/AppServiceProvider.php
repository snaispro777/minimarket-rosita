<?php

namespace App\Providers;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\ConfiguracionSitio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            // 🔹 CONTADOR DEL CARRITO
            //
            // Antes solo miraba Auth::user()->carrito, una relación que no
            // existía: devolvía null y el contador se quedaba siempre en 0,
            // ni para invitados ni para usuarios (H-08).
            $carrito = Auth::check()
                ? Auth::user()->carrito
                : Carrito::whereNull('user_id')->find(Session::get('carrito_id'));

            $carritoCount = $carrito ? $carrito->totalUnidades() : 0;

            // 🔹 CATEGORÍAS PARA EL MENÚ LATERAL
            $categorias = Categoria::orderBy('nombre')->get();

            // 🔹 COMPARTIR VARIABLES CON TODAS LAS VISTAS
            $view->with([
                'carritoCount' => $carritoCount,
                'categorias' => $categorias,
            ]);
        });

        View::composer(['layouts.navigation', 'layouts.admin'], function ($view) {
            $configuracion = ConfiguracionSitio::first();

            $view->with('logoTienda', $configuracion?->logo_url ?? asset('rosita.jpg'));
        });
    }
}
