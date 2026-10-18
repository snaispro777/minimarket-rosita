<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiapositivaRequest;
use App\Http\Requests\Admin\UpdateDiapositivaRequest;
use App\Http\Requests\Admin\UpdateLogoRequest;
use App\Models\ConfiguracionSitio;
use App\Models\Diapositiva;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContenidoController extends Controller
{
    public function index(): View
    {
        return view('admin.contenido.index', [
            'configuracion' => ConfiguracionSitio::firstOrCreate([]),
            'diapositivas' => Diapositiva::orderBy('orden')->get(),
        ]);
    }

    public function updateLogo(UpdateLogoRequest $request): RedirectResponse
    {
        $configuracion = ConfiguracionSitio::firstOrCreate([]);
        $logoAnterior = $configuracion->getRawOriginal('logo');
        $logoNuevo = $request->file('logo')->store('marca', 'public');

        $configuracion->update(['logo' => $logoNuevo]);
        $this->eliminarArchivoGestionado($logoAnterior, 'marca/');

        return back()->with('success', 'Logo actualizado correctamente.');
    }

    public function storeSlide(StoreDiapositivaRequest $request): RedirectResponse
    {
        if (Diapositiva::count() >= 5) {
            return back()->withErrors(['imagen' => 'El slider admite como máximo 5 imágenes.']);
        }

        Diapositiva::create([
            'imagen' => $request->file('imagen')->store('slider', 'public'),
            'texto_alt' => $request->validated('texto_alt'),
            'orden' => (Diapositiva::max('orden') ?? 0) + 1,
        ]);

        return back()->with('success', 'Imagen añadida al slider.');
    }

    public function updateSlide(UpdateDiapositivaRequest $request, Diapositiva $diapositiva): RedirectResponse
    {
        $datos = ['texto_alt' => $request->validated('texto_alt')];
        $imagenAnterior = $diapositiva->getRawOriginal('imagen');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('slider', 'public');
        }

        $diapositiva->update($datos);

        if (isset($datos['imagen'])) {
            $this->eliminarArchivoGestionado($imagenAnterior, 'slider/');
        }

        return back()->with('success', 'Imagen del slider actualizada.');
    }

    public function destroySlide(Diapositiva $diapositiva): RedirectResponse
    {
        if (Diapositiva::count() <= 3) {
            return back()->withErrors(['imagen' => 'El slider debe conservar al menos 3 imágenes.']);
        }

        $imagen = $diapositiva->getRawOriginal('imagen');
        $diapositiva->delete();
        $this->eliminarArchivoGestionado($imagen, 'slider/');

        return back()->with('success', 'Imagen retirada del slider.');
    }

    private function eliminarArchivoGestionado(?string $ruta, string $prefijo): void
    {
        if ($ruta && str_starts_with($ruta, $prefijo)) {
            Storage::disk('public')->delete($ruta);
        }
    }

}
