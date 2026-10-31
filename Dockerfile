FROM php:8.2-apache

# 1. Dependências do sistema e extensões necessárias para o CodeIgniter 4
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configura o Apache para apontar o DocumentRoot diretamente para a pasta public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilita módulo rewrite
RUN a2enmod rewrite

# 4. Copia os arquivos da aplicação
WORKDIR /var/www/html
COPY . /var/www/html/

# 5. Instala as dependências do Composer (gera a pasta vendor/ dentro da imagem)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Ajusta permissões dos arquivos e do diretório writable
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80
