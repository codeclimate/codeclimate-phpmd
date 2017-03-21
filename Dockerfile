FROM alpine:edge

MAINTAINER Code Climate <hello@codeclimate.com>

WORKDIR /usr/src/app
COPY . /usr/src/app

# Install PHP
RUN apk --update add \
      php7 \
      php7-common \
      php7-ctype \
      php7-dom \
      php7-iconv \
      php7-json \
      php7-mbstring \
      php7-opcache \
      php7-openssl \
      php7-pcntl \
      php7-phar \
      php7-sockets \
      php7-xml && \
    rm /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php

RUN apk --update add curl && \
    curl -sS https://getcomposer.org/installer | php && \
    ./composer.phar install && \
    apk del curl && \
    rm /usr/src/app/composer.phar \
       /var/cache/apk/*

# Build Content
RUN apk --update add build-base ca-certificates ruby ruby-dev && \
    gem install json httparty --no-rdoc --no-ri && \
    ./bin/build-content && \
    rm -rf $( gem environment gemdir ) && \
    apk del build-base ca-certificates ruby ruby-dev && \
    rm /var/cache/apk/*

RUN adduser -u 9000 -D app
RUN chown -R app:app .

USER app

WORKDIR /code
VOLUME /code

CMD ["/usr/src/app/bin/codeclimate-phpmd"]
