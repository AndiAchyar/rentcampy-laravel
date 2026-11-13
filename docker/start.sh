#!/usr/bin/env bash
set -e

cd /var/www/html

# Render memberi port lewat $PORT (default 10000)
PORT="${PORT:-10000}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Disk Render free tidak permanen: pulihkan foto produk bawaan setiap start
mkdir -p storage/app/public/products/seed
for f in database/seeders/images/*; do
    [ -f "$f" ] || continue
    name="$(basename "$f" | tr '[:upper:]' '[:lower:]')"
    cp -f "$f" "storage/app/public/products/seed/$name"
done
chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link --force || true

php artisan config:clear
php artisan migrate --force

# Seeder hanya dijalankan jika RUN_SEED=true (aman diulang, tidak menduplikasi data)
if [ "${RUN_SEED}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
