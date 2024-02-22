# FROM php:apache

# RUN DEBIAN_FRONTEND=noninteractive
# WORKDIR /var/www/html/
# COPY web .

# RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql pgsql
# ENV PORT=8000
# EXPOSE ${PORT}

# RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf

FROM --platform=linux/amd64 php:apache
RUN DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html/
COPY web .

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql pgsql

RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /usr/src/php/ext/imagick; \
    curl -fsSL https://github.com/Imagick/imagick/archive/06116aa24b76edaf6b1693198f79e6c295eda8a9.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1; \
    docker-php-ext-install imagick;
    
    
ENV PORT=8000
EXPOSE ${PORT}

RUN chmod -R 777 /var/www/html/uploads/
# RUN chmod -R 777 /tmp/

RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf