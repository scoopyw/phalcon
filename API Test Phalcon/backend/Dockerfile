FROM mileschou/phalcon:7.4-fpm-alpine

#RUN printf "deb http://archive.debian.org/debian/ jessie main\ndeb-src http://archive.debian.org/debian/ jessie main\ndeb http://security.debian.org jessie/updates main\ndeb-src http://security.debian.org jessie/updates main" > /etc/apt/sources.list
#
#RUN apt-get update && \
#    apt-get install -y libpq-dev && \
#    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
#    docker-php-ext-install pgsql pdo_pgsql

#RUN apk update && \
#    apk add php7-pdo_mysql php7-gd php7-bcmath php7-pgsql php7-pdo_pgsql php7-bz2 && \
#    adduser -D www www

RUN apk update && \
    apk add --no-cache postgresql-client postgresql-dev zlib-dev libpng-dev bzip2-dev php7-xdebug autoconf \
    git cmake make gcc g++ musl-dev autoconf && \
    yes 'no' | pecl install redis && \
#    pip install ctypesgen && \
#    git clone https://github.com/dosten/graphql-parser-php.git &&\
#    cd graphql-parser-php/deps/libgraphqlparser && \
#    cmake . && make && make install && \
#    cd ../.. && phpize && ./configure && make && make install &&\
#    echo "extension=graphql.so" > /usr/local/etc/php/conf.d/graphql.ini && \
    adduser -D www www

#RUN apk update && \
#    apk add postgresql-client postgresql-dev zlib-dev libpng-dev bzip2-dev && \
#    adduser -D www www

#RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
RUN        docker-php-ext-install pgsql pdo_pgsql pdo_mysql bcmath gd bz2


# install xdebug
#RUN pecl channel-update pecl.php.net
#RUN pecl install xdebug
#RUN docker-php-ext-enable xdebug
#RUN echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY ./backend/configs/www.conf /usr/local/etc/php-fpm.d/www.conf
#COPY ./backend/configs/extensions/*.ini /usr/local/etc/php/conf.d/
RUN mkdir /keys
COPY ./backend/keys /keys


#RUN printf "xdebug.default_enable = 1\nxdebug.remote_autostart = 1\nxdebug.remote_enable = 1\nxdebug.remote_port = 9000\nxdebug.idekey = docker\nxdebug.remote_connect_back = 0\n" >>  /usr/local/etc/php/conf.d/xdebug.ini
#RUN printf "xdebug.remote_host = 192.168.254.72" >>  /usr/local/etc/php/conf.d/xdebug.ini
EXPOSE 9000

ENTRYPOINT ["php-fpm", "--nodaemonize"]
