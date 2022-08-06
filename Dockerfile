FROM php:8.1.0-apache

RUN apt-get upgrade -y
RUN apt-get -y update --fix-missing

# Other PHP7 Extensions
RUN apt-get update -y
#    apt-get install -y libmcrypt-dev && \
#    pecl install mcrypt-1.0.1 && \
#    docker-php-ext-enable mcrypt

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN pecl install redis && docker-php-ext-enable redis


RUN a2enmod headers && sed -ri -e 's/^([ \t])(<\/VirtualHost>)/\1\tHeader set Access-Control-Allow-Origin ""\n\1\2/g' /etc/apache2/sites-available/*.conf


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=2.0.7 --filename=composer
RUN docker-php-ext-install pdo  pdo_mysql

COPY . /var/www/html

COPY ./php/prod-php.ini /usr/local/etc/php/php.ini
COPY ./default.conf /etc/apache2/sites-enabled/000-default.conf

RUN apt-get update && apt-get install -y \
    software-properties-common \
    npm
RUN npm install npm@8.3.1 -g && \
    npm install n -g && \
    n latest --unsafe-perm=true --allow-root

RUN npm install vue-loader@^15.9.7 --save-dev --legacy-peer-deps

WORKDIR /var/www/html

RUN a2enmod rewrite headers ssl
RUN service apache2 restart

EXPOSE 80
EXPOSE 443
