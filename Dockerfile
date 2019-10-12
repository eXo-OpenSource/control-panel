FROM alpine:latest

# Install packages
RUN apk --update add --no-cache \
      tzdata \
      nginx \
      curl \
      supervisor \
      gd \
      freetype \
      libpng \
      libjpeg-turbo \
      freetype-dev \
      libpng-dev \
      nodejs \
      git \
      php7 \
      php7-dom \
      php7-fpm \
      php7-mbstring \
      php7-mcrypt \
      php7-opcache \
      php7-pdo \
      php7-pdo_mysql \
      php7-pdo_pgsql \
      php7-pdo_sqlite \
      php7-xml \
      php7-phar \
      php7-openssl \
      php7-json \
      php7-curl \
      php7-ctype \
      php7-session \
      php7-gd \
      php7-zlib \
      php7-tokenizer \
      php7-bcmath \
      php7-redis \
      php7-fileinfo \
    && rm -rf /var/cache/apk/* && \
    addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app

# Configure nginx
COPY build/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY build/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY build/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R app.app /run && \
  chown -R app.app /var/lib/nginx && \
  chown -R app.app /var/tmp/nginx && \
  chown -R app.app /var/log/nginx && \
  chown -R app.app /var/www

# Setup document root
RUN mkdir -p /var/www/public

# Add application
WORKDIR /var/www

ADD . /var/www
RUN chown -R app:app /var/www

# Switch to use a non-root user from here on
USER 1000

RUN php artisan storage:link && \
    php artisan cache:clear

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
