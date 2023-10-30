FROM php:8.1-fpm

ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    openssl\
    nodejs\
    npm\
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip\
    sudo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd mysqli

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory

RUN useradd -m -s /bin/bash -u $uid -d /home/$user $user && \
    usermod -aG sudo $user && \
    echo "$user ALL=(ALL:ALL) NOPASSWD:ALL" > /etc/sudoers.d/$user

# Create a working directory for the user
USER $user
WORKDIR /home/$user

RUN mkdir -p /home/$user/.composer && chown -R $user:$user /home/$user/.composer

WORKDIR /var/www

USER $user
