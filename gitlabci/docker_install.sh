#!/bin/bash
# source: https://docs.gitlab.com/ee/ci/examples/php.html

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install git unzip -yqq

pecl install xdebug-2.9.2 \
  && docker-php-ext-enable xdebug

curl -sS https://raw.githubusercontent.com/composer/getcomposer.org/d3e09029468023aa4e9dcd165e9b6f43df0a9999/web/installer | php --  --install-dir=/usr/local/bin --filename=composer
