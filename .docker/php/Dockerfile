FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    python3 \
    python3-pip \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy initialization script
COPY ./init.sh /usr/local/bin/init.sh

# Give execution permission to init.sh
RUN chmod +x /usr/local/bin/init.sh

EXPOSE 9000

# Use entrypoint to run initialization script
CMD ["/usr/local/bin/init.sh"]
