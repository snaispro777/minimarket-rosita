<?php

namespace Tests\Unit;

use App\Models\CarritoItem;
use App\Models\Producto;
use App\Services\CarritoService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CarritoServiceTest extends TestCase
{
    public function test_calcula_el_total_de_varias_lineas_con_el_precio_actual(): void
    {
        $primero = (new CarritoItem(['cantidad' => 2]))->setRelation(
            'producto',
            new Producto(['precio' => 4.50])
        );
        $segundo = (new CarritoItem(['cantidad' => 3]))->setRelation(
            'producto',
            new Producto(['precio' => 7.25])
        );

        $total = app(CarritoService::class)->total(new Collection([$primero, $segundo]));

        $this->assertSame(30.75, $total);
    }
}
