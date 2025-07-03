#!/bin/bash

# Criar diretórios necessários e ajustar permissões
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# Criar arquivo do banco de dados SQLite
touch database/database.sqlite

# Ajustar permissões
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chmod -R 777 database

# Parar containers existentes
docker compose down

# Reconstruir containers
docker compose up -d --build

# Aguardar os containers iniciarem
sleep 10

# Instalar dependências do Composer e configurar Laravel
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Instalar dependências do Node.js e compilar assets
docker compose exec node npm install
docker compose exec node npm run build

# Reiniciar todos os containers
docker compose restart

echo "Setup completed! Access the application at http://localhost:8167" 