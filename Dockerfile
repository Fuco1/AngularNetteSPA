FROM alpine:edge

# PHP
RUN echo 'http://dl-cdn.alpinelinux.org/alpine/edge/community' >> /etc/apk/repositories
RUN echo 'http://dl-cdn.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories
RUN apk update
RUN apk add curl openssl git tini
RUN apk add php7 php7-json php7-mongodb php7-phar php7-mbstring php7-iconv php7-openssl php7-tokenizer php7-pdo php7-pdo_mysql php7-session php7-mysqli php7-sqlite3 php7-pdo_sqlite php7-ctype

RUN echo "memory_limit=-1" > /etc/php7/conf.d/memory-limit.ini

# COMPOSER
ENV COMPOSER_HOME /composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('/tmp/composer-setup.php');" \
  && php -r "unlink('/tmp/composer-setup.sig');"

ENV PATH /composer/vendor/bin:$PATH
