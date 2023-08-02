#!/usr/bin/env bash

# run supervisord with given conf
/usr/bin/supervisord -c /etc/supervisord.conf &

# run PHP-FPM
php-fpm
