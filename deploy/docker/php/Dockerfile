FROM php:7.3
WORKDIR /var/www
RUN docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get install -y msmtp libpng-dev
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
USER root
EXPOSE 9000
ENTRYPOINT ["/var/www/taskbot/deploy/docker/start.sh"]