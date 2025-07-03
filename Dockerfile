FROM php:8.3-fpm

# Argumentos
ARG user=www-data
ARG uid=1000

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    pkg-config

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Obter Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar diretório do sistema e configurar permissões
RUN mkdir -p /var/www && \
    chown -R $user:$user /var/www && \
    chmod -R 775 /var/www

# Configurar permissões do usuário www-data
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chmod -R 775 /home/$user

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos da aplicação com permissões corretas
COPY --chown=$user:$user . /var/www/

# Configurar permissões para diretórios específicos do Laravel
RUN mkdir -p /var/www/storage/framework/{sessions,views,cache} \
    && mkdir -p /var/www/storage/logs \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && chown -R $user:$user /var/www/storage \
    && chown -R $user:$user /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/vendor \
    && chown -R $user:$user /var/www/vendor

# Mudar para o usuário
USER $user 