#! /bin/bash

# Comandos laravel
php artisan key:generate
php artisan optimize:clear

# Necessário para php:8.3-apache
exec apache2-foreground
