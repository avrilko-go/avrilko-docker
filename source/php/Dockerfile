FROM php:7.2.10-fpm

ARG TIME_ZONE=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo $TIME_ZONE > /etc/timezone

RUN mv /etc/apt/sources.list /etc/apt/sources.list.bak

COPY sources.list /etc/apt/sources.list

RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get -y upgrade

RUN apt-get install -y --no-install-recommends mysql-client libpq-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev  ntpdate cron vim unzip git

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd pdo_mysql mysqli

## composer
ADD composer.phar /usr/local/bin/composer
RUN chmod 755 /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://packagist.laravel-china.org

##redis扩展
COPY redis-4.1.1.tgz /home/redis.tgz
RUN pecl install /home/redis.tgz && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini && rm -rf /home/redis.tgz

## memcached 扩展(这个安装起来是真的蛋疼)
COPY memcached-3.1.3 /home/memcached
COPY libmemcached-1.0.18.tar.gz /home/libmemcached-1.0.18.tar.gz
RUN tar zxvf /home/libmemcached-1.0.18.tar.gz \
    && cd libmemcached-1.0.18 \
    && ./configure --prefix=/usr/local/libmemcached --with-memcached \
    && make && make install

RUN cd /home/memcached && phpize && ./configure --disable-memcached-sasl --with-libmemcached-dir=/usr/local/libmemcached && make && make install \
&& echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini

## swoole 扩展
COPY swoole-src-master.zip /home/swoole-src-master.zip
RUN cd /home && unzip swoole-src-master.zip && cd swoole-src-master && phpize \
&& ./configure && make && make install \
&& echo "extension=swoole.so" > /usr/local/etc/php/conf.d/swoole.ini

## mcrypt 扩展
COPY mcrypt-1.0.0.tgz /home/mcrypt.tgz
RUN apt-get install -y libmcrypt-dev libmhash-dev \
	&& pecl install /home/mcrypt.tgz \
	&& echo "extension=mcrypt.so" > /usr/local/etc/php/conf.d/mcrypt.ini

## mongodb扩展
COPY mongodb-1.5.3.tgz /home/mongodb-1.5.3.tgz
RUN cd /home && pecl install mongodb-1.5.3.tgz && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

ENTRYPOINT php-fpm