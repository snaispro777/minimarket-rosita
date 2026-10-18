<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Crea únicamente las cuentas iniciales necesarias para acceder al sistema.
     *
     * Los catálogos y el inventario deben ser cargados por la clienta desde
     * la aplicación, no como datos de demostración durante el despliegue.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
    }
}
