{{--
    Barra de la tienda.

    La marca sale de config('app.name') y no escrita a mano: convivían
    "PlazaKing", "Tattos Market", "Supermercado" y "Laravel" según la
    pantalla (H-18).
--}}
<nav class="store-nav">
    <div class="store-nav-inner max-w-7xl mx-auto px-4 flex items-center gap-4">

        <button type="button" onclick="alternarMenuCategorias()"
                class="store-menu-button text-2xl leading-none px-1" aria-label="Categorías">
            ☰
        </button>

        <a href="{{ route('home') }}" class="whitespace-nowrap">
            <img width="100" height="40" src="{{ $logoTienda }}" alt="Rosita Minimarket" />
        </a>

        <form action="{{ route('productos.index') }}" method="GET" class="flex-1 min-w-0">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar productos..."
                   class="store-search w-full rounded-lg px-4 py-2.5">
        </form>

        <div class="flex items-center gap-4 text-sm whitespace-nowrap">

            <a href="{{ route('carrito.index') }}" class="store-cart relative text-xl" aria-label="Carrito">
                🛒
                @if($carritoCount > 0)
                    <span class="store-cart-count absolute -top-2 -right-2 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $carritoCount }}
                    </span>
                @endif
            </a>

            @auth
                {{-- El enlace al panel solo existe para el administrador (H-14) --}}
                @if(auth()->user()->esAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                              class="store-panel-link text-white px-3 py-2 rounded-lg">
                        Panel
                    </a>
                @endif

                @if(!auth()->user()->esAdmin())
                    <a href="{{ route('mis-pedidos.index') }}" class="store-nav-link hidden sm:inline">
                        Mis pedidos
                    </a>
                @endif

                <a href="{{ route('mi-cuenta') }}" class="store-nav-link">
                    Mi cuenta
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="store-nav-link">Cerrar sesión</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="store-nav-link">Entrar</a>
                <a href="{{ route('register') }}" class="store-nav-link">Registro</a>
            @endauth
        </div>
    </div>
</nav>

{{-- MENÚ LATERAL DE CATEGORÍAS --}}
<div id="menu-categorias"
    class="store-category-drawer fixed top-0 left-0 w-72 h-full transform -translate-x-full transition-transform z-50">

    <div class="p-5 border-b border-gray-100 font-bold text-lg flex items-center justify-between">
        Categorías
        <button type="button" onclick="alternarMenuCategorias()" class="text-gray-400 hover:text-black">✕</button>
    </div>

    <ul>
        @foreach($categorias as $categoria)
            <li>
                <a href="{{ route('productos.index', ['categoria' => $categoria->id]) }}"
                         class="store-category-link block p-4">
                    {{ $categoria->nombre }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div id="fondo-menu" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"
     onclick="alternarMenuCategorias()"></div>

@push('scripts')
<script>
    function alternarMenuCategorias() {
        document.getElementById('menu-categorias').classList.toggle('-translate-x-full');
        document.getElementById('fondo-menu').classList.toggle('hidden');
    }
</script>
@endpush
