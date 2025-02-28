# Multi-stage build for Go applications
FROM golang:1.21-alpine AS go-builder

# Install necessary build dependencies
RUN apk add --no-cache gcc musl-dev

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
    cargo \
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



# Install Python tools
RUN pip install pipx --break-system-packages
RUN pip install spacy-streamlit --break-system-packages

# Install PHP extensions
RUN docker-php-ext-install bcmath gd exif zip pdo_mysql pcntl sockets && \
    docker-php-ext-enable bcmath gd exif zip pcntl sockets
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1 && \
    docker-php-ext-install redis

# Install Python dependencies
WORKDIR /var/www/python/
RUN pip install --no-cache-dir \
    PyMuPDF \
    pymupdf4llm \
    fastapi \
    fastapi_versioning \
    yake \
    vaderSentiment \
    python-multipart \
    markdownify \
    newspaper3k \
    uvicorn \
    duckdb \
    lxml_html_clean \
    sqlalchemy \
    spacy \
    spacy \
    spacy-transformers \
    spacy-streamlit \
    spacy-llm \
    socialshares \
    socid_extractor \
    socials \
    --break-system-packages


COPY ./application-domain/python .

# Install spaCy model
RUN python3 -m spacy download en_core_web_trf --break-system-packages


# Install Composer
RUN curl -sSL https://getcomposer.org/download/latest-stable/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

# Set up application
WORKDIR /var/www/php/

# Copy application
COPY ./application-domain/php/ .
COPY ./application-domain/php/.env.production .env
# Install Laravel Octane
RUN composer require laravel/octane

# Install PHP dependencies

WORKDIR /var/www/php/
# Install and build Laravel Octane
RUN php artisan octane:install

# Install and build Node.js assets
RUN npm install --legacy-peer-deps && npm run build


# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Expose ports
EXPOSE 1120 1121 1122

# Set working directory back to application root
WORKDIR /var/www/

# Start Supervisor
ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
