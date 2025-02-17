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

# Set up Check if Email Exists
WORKDIR /var/check-if-email-exists-exists/
RUN LATEST_URL=$(curl -s "https://api.github.com/repos/reacherhq/check-if-email-exists/releases/latest" | \
    grep "browser_download_url" | \
    grep "check_if_email_exists-x86_64-unknown-linux-gnu.tar.gz" | \
    cut -d '"' -f 4) && \
    if [ -n "$LATEST_URL" ]; then \
        curl -L -O "$LATEST_URL" && \
        tar xzf check_if_email_exists-*.tar.gz && \
        rm check_if_email_exists-*.tar.gz && \
        mv check_if_email_exists /usr/local/bin/ && \
        chmod +x /usr/local/bin/check_if_email_exists; \
    else \
        echo "Failed to get release URL" && exit 1; \
    fi

# Install Python tools
RUN pip install pipx --break-system-packages
RUN pipx install ghunt
RUN pipx install bbot
RUN pipx install sherlock-project
RUN pipx ensurepath

# Install PHP extensions
RUN docker-php-ext-install zip pdo_mysql pcntl sockets && \
    docker-php-ext-enable zip pcntl sockets
RUN mkdir -p /usr/src/php/ext/redis && \
    curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1 && \
    docker-php-ext-install redis

# Install Email Validator
RUN curl -sSL https://mailsherpa.sh/install.py | python3 && \
    mv mailsherpa /usr/local/bin/validator && \
    chmod +x /usr/local/bin/validator && \
    validator --version || echo "Mailsherpa installation failed"

# Set up SpiderFoot
WORKDIR /home
RUN git clone https://github.com/izdrail/spiderfoot.izdrail.com.git && \
    pip install --no-cache-dir -r spiderfoot.izdrail.com/requirements.txt --break-system-packages

# Install Composer
RUN curl -sSL https://getcomposer.org/download/latest-stable/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

# Set up application
WORKDIR /var/www/
COPY . .
COPY .env.production .env

# Install PHP dependencies
RUN composer install --no-interaction --no-suggest --ignore-platform-req=ext-gd --ignore-platform-req=ext-exif

# Set up demo database
RUN touch laravel.sqlite

# Install and build Node.js assets
RUN npm install --legacy-peer-deps && npm run build


# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install Python dependencies
WORKDIR /var/www/python/
RUN pip install --no-cache-dir \
    PyMuPDF \
    pymupdf4llm \
    fastapi \
    yake \
    vaderSentiment \
    markdownify \
    newspaper3k \
    uvicorn \
    spacy \
    spacy-transformers \
    spacy-llm \
    socialshares \
    socid_extractor \
    socials \
    --break-system-packages

# Install spaCy model
RUN python3 -m spacy download en_core_web_trf --break-system-packages

# Expose ports
EXPOSE 1600 1601 1602

# Set working directory back to application root
WORKDIR /var/www/

# Start Supervisor
ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
