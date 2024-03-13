FROM benvenuti/php-composer:1.1

RUN docker-php-ext-install mysqli
RUN a2enmod headers
