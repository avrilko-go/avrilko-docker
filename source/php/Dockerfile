FROM php:7.2.10-fpm

ARG TIME_ZONE=Asia/Shanghai

RUN ln -snf /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo $TIME_ZONE > /etc/timezone

RUN mv /etc/apt/sources.list /etc/apt/sources.list.bak

COPY sources.list /etc/apt/sources.list

## 不检查源的有效性
RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get -y upgrade

RUN apt-get install -y --no-install-recommends mysql-client libpq-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev  ntpdate cron vim unzip git

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd pdo_mysql mysqli zip exif bcmath sockets

## composer
ADD composer.phar /usr/local/bin/composer
RUN chmod 755 /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://packagist.laravel-china.org

##redis扩展
COPY redis-4.1.1.tgz /home/redis.tgz
RUN pecl install /home/redis.tgz && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini && rm -rf /home/redis.tgz

# memcached 扩展(这个安装起来是真的蛋疼 libmemcached编译极慢 原因未知)
#COPY memcached-3.1.3 /home/memcached
#COPY libmemcached-1.0.18.tar.gz /home/libmemcached-1.0.18.tar.gz
#RUN tar zxvf /home/libmemcached-1.0.18.tar.gz \
#    && cd libmemcached-1.0.18 \
#    && ./configure --prefix=/usr/local/libmemcached --with-memcached \
#    && make -j$(getconf _NPROCESSORS_ONLN) && make install
#RUN cd /home/memcached && phpize && ./configure --disable-memcached-sasl --with-libmemcached-dir=/usr/local/libmemcached && make -j$(getconf _NPROCESSORS_ONLN) && make install \
#&& echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini

## swoole 扩展
COPY swoole-src-master.zip /home/swoole-src-master.zip
RUN cd /home && unzip swoole-src-master.zip && cd swoole-src-master && phpize \
&& ./configure && make -j$(getconf _NPROCESSORS_ONLN) && make install \
&& echo "extension=swoole.so" > /usr/local/etc/php/conf.d/swoole.ini

## mcrypt 扩展
COPY mcrypt-1.0.0.tgz /home/mcrypt.tgz
RUN apt-get install -y libmcrypt-dev libmhash-dev \
	&& pecl install /home/mcrypt.tgz \
	&& echo "extension=mcrypt.so" > /usr/local/etc/php/conf.d/mcrypt.ini

## mongodb扩展
COPY mongodb-1.5.3.tgz /home/mongodb-1.5.3.tgz
RUN cd /home && pecl install mongodb-1.5.3.tgz && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

## 安装pcntl 扩展
COPY pcntl /home/pcntl
RUN cd /home/pcntl && phpize && ./configure && make -j$(getconf _NPROCESSORS_ONLN) && make install \
&& echo "extension=pcntl.so" > /usr/local/etc/php/conf.d/pcntl.ini

## 安装xdebug扩展
COPY xdebug-2.7.1.tgz /home/xdebug-2.7.1.tgz
RUN cd /home && tar -xvzf xdebug-2.7.1.tgz && cd xdebug-2.7.1 && phpize && ./configure  && make -j$(getconf _NPROCESSORS_ONLN) && make install \
&& echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini

## 安装ssh 方便远程登录
RUN apt-get -y install ssh && mv /etc/ssh/sshd_config /etc/ssh/sshd_config.bak
COPY sshd_config /etc/ssh/sshd_config
ARG PHP_SSH_LINK_MODE
ARG PHP_SSH_PASSWORD
ARG PHP_SSH_ID_PUB
ENV PHP_SSH_LINK_MODE ${PHP_SSH_LINK_MODE}
ENV PHP_SSH_PASSWORD ${PHP_SSH_PASSWORD}
ENV PHP_SSH_ID_PUB ${PHP_SSH_ID_PUB}
RUN if [ $PHP_SSH_LINK_MODE = 1 ]; then \
echo root:$PHP_SSH_PASSWORD|chpasswd \
;fi
RUN if [ $PHP_SSH_LINK_MODE = 2 ]; then \
    cd /root && mkdir .ssh && cd .ssh && touch authorized_keys && echo $PHP_SSH_ID_PUB > authorized_keys \
;fi

## 安装zsh工具 方便容器内调试开发
COPY install.sh /home/install.sh
RUN apt-get -y install zsh && /bin/bash /home/install.sh && chsh -s /bin/zsh
COPY zsh-autosuggestions.tar.bz2 /root/.oh-my-zsh/custom/plugins/zsh-autosuggestions.tar.bz2
RUN cd /root/.oh-my-zsh/custom/plugins/ && tar xvf zsh-autosuggestions.tar.bz2 && chmod -R 755 zsh-autosuggestions && chown -R root.root zsh-autosuggestions  && rm -rf /root/.zshrc
COPY zshrc /root/.zshrc

ENTRYPOINT ["/bin/bash", "/home/start.sh"]