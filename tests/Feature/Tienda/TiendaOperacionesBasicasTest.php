<?php

namespace Tests\Feature\Tienda;

use App\Models\Carrito;
use App\Models\Diapositiva;
use App\Models\Producto;
use App\Models\User;
use App\Services\CarritoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiendaOperacionesBasicasTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_muestra_las_diapositivas_configuradas(): void
    {
        Diapositiva::create([
            'imagen' => 'slider/primera.png',
            'texto_alt' => 'Primera promoción',
            'orden' => 1,
        ]);
        Diapositiva::create([
            'imagen' => 'slider/segunda.png',
            'texto_alt' => 'Segunda promoción',
            'orden' => 2,
        ]);
        Diapositiva::create([
            'imagen' => 'slider/tercera.png',
            'texto_alt' => 'Tercera promoción',
            'orden' => 3,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Primera promoción')
            ->assertSee('Segunda promoción')
            ->assertSee('Tercera promoción');
    }

    public function test_el_catalogo_filtra_por_nombre(): void
    {
        Producto::factory()->create(['nombre' => 'Arroz Extra Superior']);
        Producto::factory()->create(['nombre' => 'Jugo de Naranja']);

        $this->get(route('productos.index', ['buscar' => 'Arroz']))
            ->assertOk()
            ->assertSee('Arroz Extra Superior')
            ->assertDontSee('Jugo de Naranja');
    }

    public function test_un_usuario_puede_eliminar_una_linea_propia(): void
    {
        $cliente = User::factory()->cliente()->create();
        $producto = Producto::factory()->conStock(10)->create();

        $this->actingAs($cliente)
            ->post(route('carrito.agregar'), ['producto_id' => $producto->id, 'cantidad' => 2]);

        $linea = $cliente->carrito->items()->firstOrFail();

        $this->actingAs($cliente)
            ->delete(route('carrito.eliminar', $linea->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('carrito_items', ['id' => $linea->id]);
    }

    public function test_un_usuario_puede_vaciar_su_carrito(): void
    {
        $cliente = User::factory()->cliente()->create();
        $producto = Producto::factory()->conStock(10)->create();

        $this->actingAs($cliente)
            ->post(route('carrito.agregar'), ['producto_id' => $producto->id]);

        $this->actingAs($cliente)
            ->post(route('carrito.vaciar'))
            ->assertRedirect();

        $this->assertDatabaseCount('carrito_items', 0);
    }

    public function test_el_carrito_de_invitado_se_fusiona_al_iniciar_sesion(): void
    {
        $cliente = User::factory()->cliente()->create();
        $producto = Producto::factory()->conStock(10)->create();
        $carritos = app(CarritoService::class);
        $invitado = $carritos->paraInvitado(null);
        $carritos->agregar($invitado, $producto, 2);

        $this->withSession(['carrito_id' => $invitado->id])
            ->post(route('login'), [
                'email' => $cliente->email,
                'password' => 'password',
            ])
            ->assertRedirect();

        $this->assertAuthenticatedAs($cliente);
        $this->assertDatabaseHas('carrito_items', [
            'carrito_id' => $cliente->carrito->id,
            'producto_id' => $producto->id,
            'cantidad' => 2,
        ]);
        $this->assertDatabaseMissing('carritos', ['id' => $invitado->id]);
    }

    public function test_los_pedidos_de_un_cliente_requieren_autenticacion(): void
    {
        $this->get(route('mis-pedidos.index'))->assertRedirect(route('login'));
    }

    public function test_el_checkout_guarda_todas_las_lineas_y_el_total(): void
    {
        $cliente = User::factory()->cliente()->create();
        $primero = Producto::factory()->conStock(10)->create(['precio' => 4.50]);
        $segundo = Producto::factory()->conStock(10)->create(['precio' => 7.25]);

        $this->actingAs($cliente)->post(route('carrito.agregar'), [
            'producto_id' => $primero->id,
            'cantidad' => 2,
        ]);
        $this->actingAs($cliente)->post(route('carrito.agregar'), [
            'producto_id' => $segundo->id,
            'cantidad' => 3,
        ]);

        $this->actingAs($cliente)
            ->post(route('pago.procesar'))
            ->assertRedirect(route('pago.exito'));

        $this->assertDatabaseHas('ventas', [
            'user_id' => $cliente->id,
            'total' => '30.75',
        ]);
        $this->assertDatabaseHas('venta_items', [
            'producto_id' => $primero->id,
            'cantidad' => 2,
            'precio' => '4.50',
        ]);
        $this->assertDatabaseHas('venta_items', [
            'producto_id' => $segundo->id,
            'cantidad' => 3,
            'precio' => '7.25',
        ]);
    }
}
