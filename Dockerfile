# Main application image
FROM php:8.3

# Install required system packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    procps \
    gnupg \
    nodejs \
    npm \
    gosu \
    curl \
    ca-certificates \
    protobuf-compiler \
    zip \
    unzip \
    git \
    supervisor \
    sqlite3 \
    libcap2-bin \
    libpng-dev \
    python3 \
    python3-pip \
    python3.11-venv \
    dnsutils \
    librsvg2-bin \
    fswatch \
    nano \
    ffmpeg \
    poppler-utils \
    libzip-dev \
    libonig-dev \
    libjson-c-dev \
    build-essential \
    autoconf \
    zlib1g-dev \
    pkg-config \
    wget \
    redis \
    golang \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*





# Install PHP extensions
RUN docker-php-ext-install bcmath gd exif zip pdo_mysql pcntl sockets && \
    docker-php-ext-enable bcmath gd exif zip pcntl sockets
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1 && \
    docker-php-ext-install redis



# Install Composer
RUN curl -sSL https://getcomposer.org/download/latest-stable/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

# Set up application
WORKDIR /var/www/php/

# Copy application
COPY ./application-domain/php/ .
COPY ./application-domain/php/env.production .env
# Install Laravel Octane
RUN composer require laravel/octane
RUN composer require laravel/horizon

# Install PHP dependencies

WORKDIR /var/www/php/
# Install and build Laravel Octane
RUN php artisan octane:install

# Install and build Node.js assets
RUN npm install vue
RUN npm install --legacy-peer-deps && npm run build


# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


RUN echo "memory_limit=1024M" > /usr/local/etc/php/conf.d/memory-limit.ini


# Customize shell with Zsh
RUN sh -c "$(wget -O- https://github.com/deluan/zsh-in-docker/releases/download/v1.1.5/zsh-in-docker.sh)" -- \
    -t https://github.com/denysdovhan/spaceship-prompt \
    -a 'SPACESHIP_PROMPT_ADD_NEWLINE="false"' \
    -a 'SPACESHIP_PROMPT_SEPARATE_LINE="false"' \
    -p git \
    -p ssh-agent \
    -p https://github.com/zsh-users/zsh-autosuggestions \
    -p https://github.com/zsh-users/zsh-completions


# Expose ports
EXPOSE 1120 1121 1122 5173

# Set working directory back to application root
WORKDIR /var/www/

# Start Supervisor
ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
