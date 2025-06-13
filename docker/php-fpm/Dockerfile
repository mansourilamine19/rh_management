
# docker/php-fpm/Dockerfile
FROM php:8.4-fpm

#RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install the cURL extension
RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl

RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp
#RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
#    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
#    locale-gen

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
#RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache
RUN docker-php-ext-install intl
#RUN docker-php-ext-install zip
RUN docker-php-ext-install calendar
RUN pecl install apcu amqp mailparse xdebug
#RUN docker-php-ext-install dom
#RUN docker-php-ext-install mbstring
#RUN docker-php-ext-install zip
#RUN docker-php-ext-install xsl
#RUN pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm ; bin/console doctrine:schema:update --force
EXPOSE 9000