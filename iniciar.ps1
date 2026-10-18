$ErrorActionPreference = 'Stop'

docker compose up -d
Write-Host 'Aplicación disponible en http://localhost:8080' -ForegroundColor Green
