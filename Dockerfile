FROM benvenuti/php-composer:1.1

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli
RUN a2enmod headers

COPY . /var/www/html