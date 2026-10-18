<?php

namespace Database\Seeders;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EntregaSeeder extends Seeder
{
    public function run(): void
    {
        $cuentas = [
            [
                'name' => 'Rosita Administradora',
                'email' => 'rosita_admin@gmail.com',
                'password' => 'rosita123',
                'rol' => RolUsuario::Admin,
            ],
            [
                'name' => 'Rosita Cliente',
                'email' => 'rosita_cliente@gmail.com',
                'password' => 'rosita123',
                'rol' => RolUsuario::Cliente,
            ],
        ];

        foreach ($cuentas as $datos) {
            $usuario = User::firstOrNew(['email' => $datos['email']]);

            if (!$usuario->exists) {
                $usuario->name = $datos['name'];
                $usuario->password = Hash::make($datos['password']);
                $usuario->rol = $datos['rol'];
                $usuario->email_verified_at = now();
                $usuario->save();
            }

            $this->command?->info("Cuenta preparada: {$datos['email']}");
        }
    }
}
