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
envsubst '${PORT}' < /etc/nginx/nginx.conf > /etc/nginx/nginx.conf.tmp
mv /etc/nginx/nginx.conf.tmp /etc/nginx/nginx.conf

# Test nginx configuration
nginx -t || exit 1

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
