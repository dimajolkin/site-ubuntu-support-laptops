FROM php:7.4

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pgsql
WORKDIR /app