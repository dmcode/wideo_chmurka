FROM php:8.1-fpm-bullseye

RUN apt-get clean && apt-get update -y --fix-missing \
      && apt-get install -y apt-utils apt-file \
      && apt-file update && apt-get update -y --fix-missing

RUN apt-get install -y zip ffmpeg \
      && apt-get autoremove -y && apt-get autoclean

RUN docker-php-ext-install pdo_mysql

RUN mkdir -p /srv/app

WORKDIR /srv/app
