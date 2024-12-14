#!/bin/bash
set -e

cd /var/www/html

# Check for .env file
if [ ! -f .env ]; then
    echo "Error: .env file is missing. Initialization aborted."
    exit 1
fi

# Check if initialization has already run
if [ ! -f .initialized ]; then
    echo "Generating application key..."
    php artisan key:generate || { echo "Key generation failed!"; exit 1; }
    echo "Running migrations..."
    php artisan migrate || { echo "Migration failed!"; exit 1; }

    echo "Running tests..."
    php artisan test || { echo "Tests failed!"; exit 1; }

    echo "Running seeders..."
    php artisan db:seed || { echo "Seeding failed!"; exit 1; }

    echo "Generating API documentation..."
    php artisan l5-swagger:generate || { echo "Swagger generation failed!"; exit 1; }

    # Mark as initialized
    touch .initialized
else
    echo "Application already initialized."
fi

exec "$@"
