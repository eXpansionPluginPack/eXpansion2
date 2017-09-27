FROM php:7.0-cli

RUN apt-get update \
     && apt-get install -y \
	git \
	unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql

# Add a alias to lake it easier to use sf commands
RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

WORKDIR /var/www/expansion