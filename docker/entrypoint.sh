#!/bin/sh

# Create required directories
mkdir -p /var/log/nginx
mkdir -p /var/log/supervisor
mkdir -p /run/nginx

# Set proper permissions
chown -R www-data:www-data /var/log/nginx
chmod -R 755 /var/log/nginx
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Replace environment variables in nginx configuration
envsubst '${PORT}' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

# Test nginx configuration
nginx -t || exit 1

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
