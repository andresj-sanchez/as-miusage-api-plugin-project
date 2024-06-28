FROM wordpress:latest
ARG UID=1000
ARG GID=1000
ARG SEED_FILE=basic-wordpress-seed.sh

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo_mysql

# Set up the environment
RUN apt-get update \
    && apt-get -y install default-mysql-client less

# Install WP-CLI
RUN curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# setup for correct user id and group id use same as user on local system in the container 
RUN usermod -u $UID www-data && groupmod -o -g $GID www-data \
    && mkdir -p /var/www/.wp-cli/packages \
    && mkdir -p /var/www/.composer/cache/vcs \
    && chown -R $UID:$GID /var/www

COPY ./docker/wordpress/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
COPY ./docker/wordpress/seeds/$SEED_FILE /usr/local/bin/wordpress-seed.sh
COPY ./docker/wordpress/seeds/core-install.sh /usr/local/bin/core-install.sh
COPY ./docker/wordpress/seeds/zip-plugin.sh /usr/local/bin/zip-plugin.sh
COPY ./docker/wordpress/wp-cli-faker.zip /tmp/wp-cli-faker.zip

USER www-data

# Install dist-archive package
RUN wp package install wp-cli/dist-archive-command

# # Ensure permissions
# RUN chown -R www-data:www-data /var/www/html/wp-content/plugins/as-miusage-api-plugin

# # Copy PHP configuration for large file uploads
# COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini
