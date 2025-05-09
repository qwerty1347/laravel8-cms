FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    python3 \
    python3-pip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    vim \
    librdkafka-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Set timezone (example: Asia/Seoul)
RUN echo "Asia/Seoul" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# Install MongoDB PHP extension
RUN pecl install mongodb-1.15.0 && docker-php-ext-enable mongodb

# Install Kafka (rdkafka) PHP extension
RUN pecl install rdkafka-5.0.2 && docker-php-ext-enable rdkafka

# Install bcmath and mbstring PHP extensions
RUN docker-php-ext-install bcmath mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add PHPUnit alias
RUN echo "alias phpunit='/var/www/html/vendor/bin/phpunit'" >> ~/.bashrc

# Set working directory
WORKDIR /var/www/html

# Copy initialization script
COPY ./init.sh /usr/local/bin/init.sh

# Give execution permission to init.sh
RUN chmod +x /usr/local/bin/init.sh

EXPOSE 9000

# Use entrypoint to run initialization script
CMD ["/usr/local/bin/init.sh"]
