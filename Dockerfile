FROM alpine:edge
LABEL maintainer="Code Climate <hello@codeclimate.com>"

WORKDIR /usr/src/app

RUN adduser -u 9000 -D app

# Install PHP
RUN apk add --no-cache \
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
      php7-simplexml \
      php7-sockets \
      php7-tokenizer \
      php7-xmlwriter \
      php7-xml && \
      ln -sf /usr/bin/php7 /usr/bin/php

# Bring composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer


# Install Dependencies
COPY composer.* ./
RUN composer install --no-dev && \
    chown -R app:app . && \
    rm -r ~/.composer

# Build Content
COPY bin/build-content ./bin/build-content
RUN apk add --no-cache ruby ruby-json ruby-bigdecimal ruby-dev build-base libxml2-dev libxslt-dev libffi-dev && \
    gem install rdoc nokogiri httparty --no-document && \
    ./bin/build-content && \
    chown -R app:app content && \
    gem uninstall --all rdoc httparty nokogiri && \
    rm -rf $( gem environment gemdir ) && \
    apk del --purge ruby ruby-json ruby-bigdecimal ruby-dev build-base libxml2-dev libxslt-dev libffi-dev && \
    rm -r /var/cache/* ~/.gem

COPY . ./

RUN find -not \( -user app -and -group app \) -exec chown -R app:app {} \;

USER app

WORKDIR /code
VOLUME /code

CMD ["/usr/src/app/bin/codeclimate-phpmd"]
