# Imagen base con PHP 8.2 y Apache (linux/amd64 compatible con Render)
FROM php:8.2-apache

# Instalar librerías necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-enable pdo pdo_pgsql pgsql

# Activar mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Configurar DocumentRoot para usar la carpeta public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Ajustar permisos para Render (no corre como root)
RUN chown -R www-data:www-data /var/www/html

# Limpiar para reducir tamaño
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Exponer puerto 80 para Render
EXPOSE 80

# Directorio de trabajo
WORKDIR /var/www/html

# Comando que Render usará para iniciar Apache
CMD ["apache2-foreground"]
