FROM alpine:3.18
LABEL Maintainer="Piotr Miloszewicz <piotr@miloszewicz.com>"

WORKDIR /var/www/html

RUN apk add --no-cache \
  curl \
  nginx \
  php81 \
  php81-ctype \
  php81-curl \
  php81-dom \
  php81-fpm \
  php81-intl \
  php81-mbstring \
  php81-tokenizer \
  php81-pdo \
  php81-pgsql \
  php81-pdo_pgsql \
  php81-pdo_mysql \
  php81-mysqlnd \
  php81-opcache \
  php81-openssl \
  php81-phar \
  php81-session \
  php81-xml \
  php81-simplexml \
  php81-pecl-amqp \
  php81-bz2 \
  php81-sodium \
  php81-zip \
  php81-xmlwriter \
  php81-xdebug \
  composer \
  supervisor

COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/conf.d /etc/nginx/conf.d/

COPY .docker/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY .docker/php.ini /etc/php81/conf.d/custom.ini

COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir /var/www/html/var
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx
USER nobody

COPY --chown=nobody src/ /var/www/html/

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
