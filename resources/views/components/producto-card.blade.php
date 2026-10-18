@props(['producto'])

{{--
    Tarjeta de producto de la tienda.

    Estaba duplicada en el home y en los "productos similares" de la ficha, con
    dos maquetaciones distintas y dos botones distintos — uno de los cuales no
    funcionaba (H-43).
--}}
<div class="store-product-card rounded-2xl overflow-hidden group flex flex-col">

    <a href="{{ route('productos.show', $producto) }}" class="block">
        <div class="store-product-image relative p-4">
            <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}"
                 class="w-full h-40 object-contain group-hover:scale-105 transition">

            @if($producto->stock <= 0)
                <span class="absolute top-3 left-3 bg-gray-700 text-white text-xs px-2.5 py-1 rounded-full">
                    Agotado
                </span>
            @elseif($producto->bajoMinimo())
                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs px-2.5 py-1 rounded-full">
                    Últimas unidades
                </span>
            @endif
        </div>
    </a>

    <div class="px-4 pb-4 flex flex-col flex-1">
        <h3 class="store-product-name text-sm font-semibold leading-tight line-clamp-2">
            {{ $producto->nombre }}
        </h3>

        {{-- La unidad sale del enum, no de un texto fijo: el home decía
             "Precio por Kg" hasta debajo del papel higiénico. --}}
        <p class="text-xs text-gray-500 mt-1">
            Precio por {{ $producto->unidad_medida->etiqueta() }}
        </p>

        <div class="mt-2 mb-3">
            <span class="store-product-price text-xl font-bold">
                S/ {{ number_format($producto->precio, 2) }}
            </span>
        </div>

        {{-- El id viaja en un campo del formulario, no en la URL: 'carrito.agregar'
             no recibe parámetros de ruta y el id se perdía (H-43). --}}
        <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-auto">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">

            <button type="submit" @disabled($producto->stock <= 0)
                    class="store-add-button w-full text-white py-2.5 rounded-xl text-sm font-semibold transition disabled:cursor-not-allowed">
                {{ $producto->stock > 0 ? 'Agregar' : 'Sin stock' }}
            </button>
        </form>
    </div>
</div>
