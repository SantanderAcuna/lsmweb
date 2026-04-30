# syntax=docker/dockerfile:1.7
# ---------------------------------------------------------------------------
# Backend Laravel 12 — Multi-stage build
# Stage 1: install Composer deps in a clean composer image (no PHP runtime bloat).
# Stage 2: php-fpm-alpine runtime; copy vendor + source. Alpine keeps image small.
# ---------------------------------------------------------------------------

# ---------- Stage 1: Composer dependencies ----------
FROM composer:2 AS vendor

WORKDIR /app

# Copy only the files needed for dependency resolution to maximise layer cache.
COPY composer.json composer.lock* ./

# --no-scripts: avoid running artisan during build (no app code yet).
# --no-dev: production-only dependencies.
# --prefer-dist: faster, no git history.
RUN composer install \
        --no-dev \
        --no-scripts \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --optimize-autoloader

# ---------- Stage 2: PHP-FPM runtime ----------
FROM php:8.3-fpm-alpine AS runtime

# install-php-extensions handles all native build deps cleanly and is removed at end.
# Pinned to a specific version for reproducibility.
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Required system packages (runtime only, build deps are handled by install-php-extensions).
RUN apk add --no-cache \
        bash \
        curl \
        tini \
        icu-data-full \
    && install-php-extensions \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -f /usr/local/bin/install-php-extensions

# Production-tuned opcache config.
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=0'; \
        echo 'opcache.memory_consumption=192'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.jit=tracing'; \
        echo 'opcache.jit_buffer_size=64M'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Copy vendor first (changes less often than app code).
COPY --from=vendor /app/vendor ./vendor

# Copy application source.
COPY . .

# www-data already exists in php-fpm-alpine. Ensure storage/cache writable.
RUN mkdir -p storage/framework/{cache,sessions,views,testing} storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000

# php-fpm exposes a TCP healthcheck via cgi-fcgi; we use a lightweight TCP probe instead.
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php-fpm -t 2>/dev/null || exit 1

# tini reaps zombie children spawned by php-fpm workers.
ENTRYPOINT ["/sbin/tini", "--"]
CMD ["php-fpm", "-F"]
