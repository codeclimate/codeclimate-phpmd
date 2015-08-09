FROM phusion/baseimage

WORKDIR /usr/src/app
COPY composer.json /usr/src/app/
COPY composer.lock /usr/src/app/

RUN apt-get update
RUN DEBIAN_FRONTEND="noninteractive" apt-get install -y curl git
RUN add-apt-repository -y ppa:ondrej/php5-5.6
RUN apt-get update
RUN DEBIAN_FRONTEND="noninteractive" apt-get install -y --force-yes php5-cli

RUN curl -sS https://getcomposer.org/installer | php
RUN /usr/src/app/composer.phar install

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN adduser --uid 9000 --disabled-password app
USER app

COPY . /usr/src/app

CMD ["/usr/src/app/bin/codeclimate-phpmd"]

