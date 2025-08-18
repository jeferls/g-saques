FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    librabbitmq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    curl \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql zip gd sockets \
 && printf "\n" | pecl install amqp \
 && docker-php-ext-enable amqp \
 && npm install -g pm2 \
 && rm -rf /var/lib/apt/lists/*

# Instala o Datadog PHP Tracer
RUN curl -LO https://github.com/DataDog/dd-trace-php/releases/latest/download/datadog-setup.php \
 && php datadog-setup.php --php-bin php --tracer-version latest \
 && rm datadog-setup.php

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho DENTRO de src
WORKDIR /var/www/html/src

# Instala dependências PHP do projeto (composer.* estão em src/)
COPY src/composer.json src/composer.lock* ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copia o restante do código (pasta src/)
COPY src/ .

# Cria .env dentro de src/ (a partir de templates em src/ OU na raiz) e ajusta permissões
RUN git config --global --add safe.directory /var/www/html/src \
 && { [ -f .env ] || cp .env.template .env || cp .env.example .env || cp ../.env.template .env || cp ../.env.example .env || true; } \
 && mkdir -p storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Copia configurações customizadas do PHP e entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-F"]
