#!/bin/bash
# =============================================================
# Script de deploiement iCommerce Gabon sur o2switch
# Usage: bash deploy.sh
# =============================================================

set -e

echo ">>> Mise en mode maintenance..."
php artisan down

echo ">>> Pull des derniers changements..."
git pull origin main

echo ">>> Installation des dependances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">>> Installation des dependances Node et build..."
npm ci
npm run build

echo ">>> Cache de configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">>> Migrations..."
php artisan migrate --force

echo ">>> Sortie du mode maintenance..."
php artisan up

echo ">>> Deploiement termine !"
