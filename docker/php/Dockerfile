FROM php:7.4-cli

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

RUN pecl install -o -f xdebug
RUN docker-php-ext-enable xdebug

# Install meminfo
#RUN git clone https://github.com/BitOne/php-meminfo.git /tmp/meminfo \
#    && mkdir -p usr/src/php/ext/ \
#    && mkdir -p usr/src/php/meminfo \
#    && mv /tmp/meminfo/extension/php7 /usr/src/php/ext/meminfo \
#    && mv /tmp/meminfo/analyzer /usr/src/php/meminfo/analyzer
#RUN cd /usr/src/php/ext/meminfo \
#    && pwd \
#    && ls \
#    && phpize \
#    && docker-php-ext-install meminfo
#RUN cd /usr/src/php/meminfo/analyzer \
#    && composer update \
#    && echo 'alias mema="php /usr/src/php/meminfo/analyzer/bin/analyzer"' >> ~/.bashrc

# Add a alias to lake it easier to use sf commands
RUN echo 'alias sf="php bin/console"' >> ~/.bashrc
WORKDIR /var/www/expansion

ENTRYPOINT ["sleep","720m"]