@extends('layouts.tienda')

@section('content')

    {{-- ================= CARRUSEL DE PROMOCIONES ================= --}}
    {{--
        Swiper viene del bundle de Vite y se arranca desde resources/js/app.js.
        Antes la vista traía su propio <link> y su propio <script src="...">
        apuntando a un CDN (H-17).
    --}}
    <div class="store-hero">
        <div class="swiper promociones-swiper">
            <div class="swiper-wrapper">
                @foreach($diapositivas as $diapositiva)
                    <div class="swiper-slide">
                        <img src="{{ $diapositiva->imagen }}" alt="{{ $diapositiva->texto_alt }}" class="w-full h-[420px] object-cover">
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    {{-- ================= PRODUCTOS DESTACADOS ================= --}}
    <div id="productos" class="store-section">

        <div class="store-section-heading">
            <div>
                <p class="store-section-kicker mb-2">Selección Rosita</p>
                <h2 class="store-section-title">Productos destacados</h2>
            </div>
            <a href="{{ route('productos.index') }}" class="store-section-link hidden sm:inline">Ver catálogo →</a>
        </div>

        @if($productos->isEmpty())
            <p class="text-gray-500">No hay productos registrados aún.</p>
        @else
            <div class="store-products-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach($productos as $producto)
                    <x-producto-card :producto="$producto" />
                @endforeach
            </div>
        @endif
    </div>

@endsection
