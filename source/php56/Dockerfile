FROM php:5.6-fpm

ARG TIME_ZONE=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo $TIME_ZONE > /etc/timezone

RUN mv /etc/apt/sources.list /etc/apt/sources.list.bak && mv /etc/apt/sources.list.d/buster.list  /etc/apt/sources.list.d/buster.list.bak

COPY sources.list /etc/apt/sources.list

## 不检查源的有效性
RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get -y upgrade

RUN apt-get install -y --no-install-recommends mysql-client libpq-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev  ntpdate cron vim unzip git

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd pdo_mysql mysqli zip exif bcmath sockets mysql

## memcache
RUN apt-get install -y  zlib1g-dev && pecl install memcache-2.2.7 && docker-php-ext-enable memcache

##redis扩展
RUN  pecl install redis-4.0.1 && docker-php-ext-enable redis

ENTRYPOINT ["/bin/bash", "/home/start.sh"]