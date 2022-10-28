FROM php:8.1-fpm-bullseye

RUN apt-get clean && apt-get update -y --fix-missing \
      && apt-get install -y apt-utils apt-file \
      && apt-file update && apt-get update -y --fix-missing

RUN apt-get install -y zip ffmpeg \
      && apt-get autoremove -y && apt-get autoclean

RUN docker-php-ext-install pdo_mysql

ENV APP_PATH=/usr/local/apache2/htdocs/
WORKDIR $APP_PATH

#COPY . $APP_PATH
#RUN php composer.phar install
#
#ENV STORAGE_PATH="$APP_PATH/storage"
#RUN chown www-data:www-data $STORAGE_PATH
#
#USER www-data
#
#VOLUME $STORAGE_PATH
#