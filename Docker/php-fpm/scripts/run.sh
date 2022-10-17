#!/bin/sh
set -e
set -u

echo "Change permissions"
chown -R 82:82 /data

php-fpm