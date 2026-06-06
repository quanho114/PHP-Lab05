FROM php:8.2-cli

# Install dependencies and PDO MySQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /app

# Expose port 8000
EXPOSE 8000

# Start PHP built-in web server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
