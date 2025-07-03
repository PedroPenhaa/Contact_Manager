# Contacts Manager

Uma aplicação Laravel com Vue.js para gerenciamento de contatos.

## Requisitos

- Docker
- Docker Compose

## Configuração Inicial

1. Clone o repositório:
```bash
git clone <repository-url>
cd <repository-name>
```

2. Copie o arquivo de ambiente:
```bash
cp .env.example .env
```

3. Execute o script de setup:
```bash
./setup.sh
```

## Acessando a Aplicação

- Frontend: http://localhost:8167
- Mailpit (emails): http://localhost:8025

## Estrutura do Projeto

- `app/` - Código PHP da aplicação
- `resources/js/` - Componentes Vue.js
- `database/` - Migrações e seeds
- `routes/` - Definição das rotas
- `docker/` - Configurações do Docker

## Comandos Úteis

### Reconstruir os Containers
```bash
docker compose down && docker compose up -d --build
```

### Limpar Caches
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan cache:clear
```

### Recompilar Assets
```bash
docker compose exec node npm run build
```

### Executar Migrações
```bash
docker compose exec app php artisan migrate
```

## Solução de Problemas

### Permissões
Se encontrar problemas de permissão:
```bash
chmod -R 777 storage bootstrap/cache database
```

### Banco de Dados
Se precisar recriar o banco de dados:
```bash
rm database/database.sqlite
touch database/database.sqlite
chmod 777 database/database.sqlite
docker compose exec app php artisan migrate
```

### Assets não Carregam
Se os assets não carregarem:
```bash
docker compose exec node npm install
docker compose exec node npm run build
docker compose restart
```
