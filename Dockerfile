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
    pkg-config \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    pdo_sqlite \
    zip

# Obter Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório home do usuário
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos da aplicação
COPY . /var/www/

# Configurar permissões
RUN mkdir -p /var/www/storage/framework/{sessions,views,cache} \
    /var/www/storage/logs \
    /var/www/bootstrap/cache \
    && chown -R $user:$user /var/www \
    && chmod -R 775 /var/www \
    && find /var/www/storage -type d -exec chmod 777 {} \; \
    && find /var/www/storage -type f -exec chmod 666 {} \; \
    && chmod -R 777 /var/www/storage/framework \
    && chmod -R 777 /var/www/storage/logs \
    && chmod -R 777 /var/www/bootstrap/cache

# Configurar Git
RUN git config --system safe.directory /var/www

# Mudar para o usuário
USER $user 