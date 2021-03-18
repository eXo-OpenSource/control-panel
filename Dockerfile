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
      php8 \
      php8-dom \
      php8-fpm \
      php8-mbstring \
      php8-opcache \
      php8-pdo \
      php8-pdo_mysql \
      php8-pdo_pgsql \
      php8-pdo_sqlite \
      php8-xml \
      php8-phar \
      php8-openssl \
      php8-json \
      php8-curl \
      php8-ctype \
      php8-session \
      php8-gd \
      php8-zlib \
      php8-tokenizer \
      php8-bcmath \
      php8-redis \
      php8-fileinfo \
      nodejs \
      npm \
    && rm -rf /var/cache/apk/* && \
    addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app && \
    mkdir /var/log/websockets && \
    touch /var/log/php8/stdout.log && \
    touch /var/log/php8/stderr.log && \
    touch /var/log/nginx/stdout.log && \
    touch /var/log/nginx/stderr.log && \
    touch /var/log/websockets/stdout.log && \
    touch /var/log/websockets/stderr.log && \
    chown -R 1000:0 /var/log/*

# Set timzone
RUN cp /usr/share/zoneinfo/Europe/Vienna /etc/localtime && \
    echo "Europe/Vienna" > /etc/timezone

# Configure nginx
COPY build/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY build/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY build/php.ini /etc/php8/conf.d/zzz_custom.ini

# Configure cron
COPY build/crontab /etc/cron/crontab

# Configure supervisord
COPY build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R app.app /run && \
  chown -R app.app /var/lib/nginx && \
  chown -R app.app /var/log/nginx && \
  chown -R app.app /var/log/websockets && \
  chown -R app.app /var/www

# Setup document root
RUN mkdir -p /var/www/public
RUN crontab /etc/cron/crontab

# Add application
WORKDIR /var/www

#ADD . /var/www
COPY --chown=app:app . /var/www
#RUN chown -R app:app /var/www

# Switch to use a non-root user from here on
USER 1000

RUN php8 artisan storage:link && \
    php8 artisan cache:clear && \
    rm public/js/app.js.map

USER 0

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
