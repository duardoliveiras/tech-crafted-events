FROM ubuntu:22.04

# Install dependencies
env DEBIAN_FRONTEND=noninteractive
RUN apt-get update; apt-get install -y --no-install-recommends libpq-dev vim nginx php8.1-fpm php8.1-mbstring php8.1-xml php8.1-pgsql php8.1-curl ca-certificates


# Install npm
RUN apt-get install php-gd -y
RUN apt-get update && apt-get install -y ca-certificates curl gnupg
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install nodejs -y
RUN apt-get update && apt-get install -y cron

# Copy project code and install project dependencies
COPY --chown=www-data . /var/www/

# Cron
RUN (crontab -l ; echo "* * * * * cd /var/www && /usr/bin/php /var/www/artisan schedule:run >> /dev/null 2>&1") | crontab -

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env_production /var/www/.env
COPY docker_run.sh /docker_run.sh

# Start command
CMD sh /docker_run.sh
