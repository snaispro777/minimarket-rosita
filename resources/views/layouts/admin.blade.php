{{--
    Layout del panel de administración.

    Cuelga de 'layouts.base' desde la Fase 5: antes traía su propia cabecera
    con Tailwind por CDN, que era la cuarta copia del mismo <head> (H-15, H-17).

    Sigue separado del de la tienda porque el panel no tiene carrito, ni
    buscador de catálogo, ni menú de categorías. Compartir layout obligaba a
    pintar la barra de la tienda sobre las pantallas de gestión.
--}}
@extends('layouts.base')

@section('contenido')

    <div class="admin-shell">
        <div class="admin-layout">
            <aside class="admin-sidebar text-gray-200">
                <div class="admin-sidebar-inner">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand flex items-center gap-3 text-white">
                        <span class="admin-brand-emblem">
                            <img src="{{ $logoTienda }}" alt="Rosita Minimarket">
                        </span>
                    </a>

                    <div class="admin-sidebar-rule"></div>

                    <nav class="admin-sidebar-nav text-sm" aria-label="Navegación del panel">
                    <p class="admin-sidebar-label">Administración</p>
                    @php
                        $enlaces = [
                            ['admin.dashboard', 'Resumen'],
                            ['admin.productos.index', 'Productos'],
                            ['admin.categorias.index', 'Categorías'],
                            ['admin.inventario.index', 'Inventario'],
                            ['admin.contenido.index', 'Contenido'],
                        ];
                    @endphp

                    @foreach($enlaces as [$ruta, $texto])
                            <a href="{{ route($ruta) }}"
                               class="admin-nav-link {{ request()->routeIs(str_replace('.index', '.*', $ruta)) ? 'is-active' : '' }}">
                                <span class="admin-nav-mark"></span>
                                {{ $texto }}
                            </a>
                    @endforeach
                    </nav>

                    <div class="admin-sidebar-footer text-sm">
                        
                        <a href="{{ route('home') }}" class="admin-sidebar-action">Ver la tienda <span aria-hidden="true">↗</span></a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="admin-sidebar-action">Cerrar sesión <span aria-hidden="true">→</span></button>
                        </form>
                        <div class="admin-profile">
                            <span class="admin-avatar" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="admin-profile-copy">
                                <span class="admin-sidebar-user">{{ auth()->user()->name }}</span>
                                <span class="admin-profile-role">Administrador</span>
                            </span>
                        </div>
                    </div>
            </div>
            </aside>

            <main class="admin-content">
                <div class="admin-main max-w-7xl mx-auto px-4 py-10">
                    <x-alerta-flash />

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

@endsection
