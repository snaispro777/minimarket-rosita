<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Diapositiva;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::latest()->take(8)->get();
        $diapositivas = Diapositiva::orderBy('orden')->get();

        if ($diapositivas->count() < 3) {
            $diapositivas = collect([
                ['imagen' => 'https://formatodigital.net/wp-content/uploads/2019/02/flyers-que-son-1024x683.jpg', 'texto_alt' => 'Promociones de Rosita Minimarket'],
                ['imagen' => 'https://www.shutterstock.com/image-vector/merry-christmas-50-percent-off-260nw-2393670303.jpg', 'texto_alt' => 'Ofertas especiales de Rosita Minimarket'],
                ['imagen' => 'https://fantasiasmiguel.com/cdn/shop/collections/promociones_navidenas.jpg?v=1639694906&width=1920', 'texto_alt' => 'Promociones de temporada'],
            ])->map(fn (array $diapositiva) => new Diapositiva($diapositiva));
        }

        return view('home', compact('productos', 'diapositivas'));
    }
}
