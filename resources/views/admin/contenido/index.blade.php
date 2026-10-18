@extends('layouts.admin')

@section('titulo', 'Contenido de la tienda')

@section('content')

    <div class="page-heading">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-1">Experiencia pública</p>
        <h1 class="text-3xl font-bold">Contenido de la tienda</h1>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="admin-panel bg-white rounded-xl overflow-hidden xl:col-span-1">
            <div class="admin-panel-header px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Identidad</p>
                <h2 class="text-xl font-bold mt-1">Logo de la tienda</h2>
            </div>

            <div class="p-5">
                <div class="flex items-center justify-center h-32 rounded-xl bg-gray-50 border border-dashed border-gray-200 mb-5">
                    <img src="{{ $configuracion->logo_url }}" alt="Logo actual" class="max-w-[180px] max-h-20 object-contain">
                </div>

                <form action="{{ route('admin.contenido.logo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Reemplazar logo</label>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white text-sm">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG o WebP. Máximo 5 MB.</p>
                    </div>

                    <button class="admin-button-primary w-full text-white px-4 py-2.5 rounded-lg font-semibold">
                        Actualizar logo
                    </button>
                </form>
            </div>
        </section>

        <section class="admin-panel bg-white rounded-xl overflow-hidden xl:col-span-2">
            <div class="admin-panel-header px-5 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Portada pública</p>
                    <h2 class="text-xl font-bold mt-1">Imágenes del slider</h2>
                </div>
                <span class="text-sm font-semibold px-3 py-1.5 rounded-full {{ $diapositivas->count() >= 3 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $diapositivas->count() }} / 5 imágenes
                </span>
            </div>

            <div class="p-5">
                <p class="text-sm text-gray-600 mb-5">
                    Configura entre 3 y 5 imágenes. Con menos de 3, la tienda mantiene temporalmente las promociones de respaldo.
                </p>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($diapositivas as $diapositiva)
                        <div class="border border-gray-200 rounded-xl p-3 bg-gray-50">
                            <img src="{{ $diapositiva->imagen }}" alt="{{ $diapositiva->texto_alt }}"
                                 class="w-full h-32 object-cover rounded-lg bg-white mb-3">

                            <form action="{{ route('admin.contenido.diapositivas.update', $diapositiva) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Texto alternativo</label>
                                    <input type="text" name="texto_alt" value="{{ old('texto_alt', $diapositiva->texto_alt) }}" required maxlength="150"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nueva imagen (opcional)</label>
                                    <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs bg-white">
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1">
                                    <button class="admin-button-filter text-white px-3 py-2 rounded-lg text-sm">Guardar cambios</button>
                            </form>
                                    @if($diapositivas->count() > 3)
                                        <form action="{{ route('admin.contenido.diapositivas.destroy', $diapositiva) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 text-sm font-semibold"
                                                    onclick="return confirm('¿Retirar esta imagen del slider?')">Retirar</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400" title="Se necesitan al menos 3 imágenes">Mínimo</span>
                                    @endif
                                </div>
                        </div>
                    @endforeach
                </div>

                @if($diapositivas->count() < 5)
                    <div class="mt-6 border-t border-gray-200 pt-5">
                        <h3 class="font-semibold mb-3">Añadir imagen</h3>
                        <form action="{{ route('admin.contenido.diapositivas.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-[1fr_1fr_auto] items-end">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Imagen</label>
                                <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp" required
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Texto alternativo</label>
                                <input type="text" name="texto_alt" value="{{ old('texto_alt') }}" required maxlength="150"
                                       placeholder="Describe la promoción"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                            </div>
                            <button class="admin-button-primary text-white px-4 py-2.5 rounded-lg font-semibold">Añadir</button>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    </div>

@endsection
