FROM dec24th82/complex:debian12-nginx1.22-php8.2-node18.13-laravel10

RUN apt-get update -y

RUN apt-get install -y vim supervisor nginx curl dirmngr unzip gnupg2 ca-certificates wget ufw
RUN apt-get install -y software-properties-common apt-transport-https lsb-release debian-archive-keyring
RUN apt-get install -y php-fpm php-cli php-mysql php-mbstring php-xml php-gd php-xdebug
RUN apt-get install -y php-ctype php-curl php-dom php-exif php-fileinfo php-iconv php-intl
RUN apt-get install -y php-mysqli php-opcache php-phar php-simplexml php-soap php-xml
RUN apt-get install -y php-xmlreader php-zip php-pdo php-xmlwriter php-tokenizer
RUN apt-get install -y nodejs npm

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure certs
COPY ./config/certs /etc/nginx/certs

# Configure nginx
COPY ./config/web/nginx/sites-enabled /etc/nginx/sites-enabled

# Configure php
COPY ./config/web/php/pool.d/www.conf /etc/php/8.2/fpm/pool.d/www.conf
COPY ./config/web/php/php.ini /etc/php/8.2/fpm/php.ini

# Configure supervisord
COPY ./config/web/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# After initial demo nginx
COPY ./src /var/www/dev.org

# Configure to ssh
RUN mkdir -p /opt/src/scripts
COPY ./config/ssh/ /opt/src/scripts
RUN chmod -R 777 /opt/src/scripts

RUN chmod -R 777 /var/www/dev.org
RUN chmod +x /var/www/dev.org
RUN chmod -R 777 /var/log/

WORKDIR /var/www/dev.org

RUN nginx -t
RUN service php8.2-fpm start
RUN service nginx start

RUN chown -R www-data:www-data /var/www/dev.org

EXPOSE 80 443

RUN /bin/sh -c /opt/src/scripts/initial.sh
# RUN /bin/sh -c /opt/src/scripts/start.sh

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
