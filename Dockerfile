FROM alpine:3.3

WORKDIR /usr/src/app
COPY composer.json /usr/src/app/
COPY composer.lock /usr/src/app/

RUN apk --update add git php-common php-xml php-dom php-ctype php-iconv \
    php-json php-pcntl php-phar php-openssl php-opcache php-sockets curl \
    build-base ruby-dev ruby ruby-bundler && \
    gem install httparty --no-rdoc --no-ri && \
    curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar install && \
    apk del build-base

COPY . /usr/src/app

RUN adduser -u 9000 -D app
RUN chown -R app .

USER app

RUN ./bin/build-content

CMD ["/usr/src/app/bin/codeclimate-phpmd"]
