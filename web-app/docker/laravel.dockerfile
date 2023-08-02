FROM php:8.2-fpm

# Copy composer.lock and composer.json into the working directory
COPY ../composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    zip \
    vim \
    git \
    curl \
    libmemcached-dev

# Install extensions for php
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd

# install MongoDB PHP
RUN apt-get install -y autoconf pkg-config libssl-dev
RUN	pecl install mongodb
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini

# install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_19.x | bash
RUN apt-get install -y nodejs

# install memcached
RUN pecl install memcached
RUN docker-php-ext-enable memcached

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents to the working directory
COPY .. /var/www/html

# install supervisord
RUN apt-get update && apt-get install -y supervisor &&  apt-get install -y procps
RUN mkdir -p /var/log/supervisor

# add /var/scripts to $PATH
ENV PATH="/var/scripts:${PATH}"

# toch some files
RUN touch /var/log/heartbeat.log
RUN touch /var/log/runner.log
RUN chown www-data /var/log/heartbeat.log
RUN chown www-data /var/log/runner.log

COPY ./docker/supervisord.conf /etc/supervisord.conf
CMD ["/usr/bin/supervisord -c /etc/supervisord.conf &"]

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod ugo+x /entrypoint.sh

ENTRYPOINT /entrypoint.sh
