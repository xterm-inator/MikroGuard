#!/bin/sh

if [ ! -f /opt/app/storage/app/database/database.sqlite ]
then
    mkdir -p /opt/app/storage/app/database
    touch /opt/app/storage/app/database/database.sqlite
fi

mkdir -p /opt/app/storage/framework/sessions
mkdir -p /opt/app/storage/framework/views
mkdir -p /opt/app/storage/framework/cache
mkdir -p /opt/app/storage/logs

php artisan migrate --force

exec php artisan octane:start --host=0.0.0.0
