$ErrorActionPreference = 'Stop'

$marcador = '.entrega-inicializada'

Write-Host '==> Comprobando Docker Desktop'
docker info | Out-Null

Write-Host '==> Construyendo y levantando la aplicación'
docker compose up -d --build

if (-not (Test-Path $marcador)) {
    Write-Host '==> Creando las cuentas iniciales'
    docker compose exec app php artisan db:seed --class=EntregaSeeder --force --no-interaction
    New-Item -ItemType File -Path $marcador -Force | Out-Null
} else {
    Write-Host '==> Las cuentas iniciales ya fueron creadas; no se ejecuta el seeder.'
}

Write-Host ''
Write-Host 'Aplicación disponible en http://localhost:8080' -ForegroundColor Green
Write-Host 'Admin:   rosita_admin@gmail.com / rosita123'
Write-Host 'Cliente: rosita_cliente@gmail.com / rosita123'
