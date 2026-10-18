@extends('layouts.admin')

@section('titulo', 'Categorías')

@section('content')

    <div class="page-heading flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1">Organización</p>
            <h1 class="text-3xl font-bold">Categorías</h1>
        </div>

        <a href="{{ route('admin.categorias.create') }}"
           class="admin-button-primary text-white px-4 py-2.5 rounded-lg">
            + Nueva categoría
        </a>
    </div>

    <div class="admin-panel bg-white rounded-xl overflow-hidden">
        <table class="admin-table w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3 text-right">Productos</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categorias as $categoria)
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $categoria->nombre }}</td>

                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.productos.index', ['categoria' => $categoria->id]) }}"
                               class="text-blue-600 hover:underline">
                                {{ $categoria->productos_count }}
                            </a>
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                            <a href="{{ route('admin.categorias.edit', $categoria) }}"
                               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                Editar
                            </a>

                            {{-- Sin productos dentro no se puede borrar: la clave
                                 foránea es RESTRICT y responderÍa con un 500 --}}
                            @if($categoria->sePuedeBorrar())
                                <form action="{{ route('admin.categorias.destroy', $categoria) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('¿Borrar la categoría «{{ $categoria->nombre }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Borrar
                                    </button>
                                </form>
                            @else
                                <span class="inline-block px-3 py-1 text-gray-400"
                                      title="Tiene productos asignados">
                                    Borrar
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="admin-empty px-4 py-10 text-center text-sm">
                            No hay categorías todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
