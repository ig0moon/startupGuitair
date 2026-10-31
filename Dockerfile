FROM php:8.2-apache

# Instala extensões comuns do PHP (PDO e mysqli para banco de dados)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita suporte a URLs amigáveis (.htaccess)
RUN a2enmod rewrite

# Copia os arquivos do projeto para o Apache
COPY . /var/www/html/

# Ajusta permissões dos arquivos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80