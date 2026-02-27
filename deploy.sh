#!/bin/bash
# =============================================================
# Script de deploiement iCommerce Gabon sur o2switch
# Usage:
#   Premiere installation : bash deploy.sh --install
#   Mise a jour           : bash deploy.sh
# =============================================================

set -e

INSTALL=false
if [ "$1" = "--install" ]; then
    INSTALL=true
fi

if [ "$INSTALL" = false ]; then
    echo ">>> Mise en mode maintenance..."
    php artisan down
fi

echo ">>> Pull des derniers changements..."
git pull origin main

echo ">>> Installation des dependances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

#echo ">>> Installation des dependances Node et build..."
#npm ci
#npm run build

if [ "$INSTALL" = true ]; then
    echo ">>> Premiere installation detectee..."
    cp .env.example .env
    php artisan key:generate
    echo ">>> IMPORTANT: Editez le fichier .env avec vos identifiants avant de continuer"
    echo ">>> Puis relancez : bash deploy.sh --install"
    read -p ">>> .env configure ? (o/n) " confirm
    if [ "$confirm" != "o" ]; then
        echo ">>> Installation interrompue. Configurez .env puis relancez."
        exit 0
    fi
    php artisan migrate --force
    php artisan db:seed --force
    php artisan storage:link
    chmod -R 775 storage bootstrap/cache
else
    echo ">>> Migrations..."
    php artisan migrate --force
fi

echo ">>> Cache de configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "$INSTALL" = false ]; then
    echo ">>> Sortie du mode maintenance..."
    php artisan up
fi

echo ">>> Deploiement termine !"
