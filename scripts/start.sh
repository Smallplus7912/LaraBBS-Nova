#!/bin/sh

# 用于启动性能采集
# nohup tideways-daemon &

# 执行migration
cd /var/www/laravel02
php artisan migrate --force
if [! -f "public/storage"] ; then php artisan storage:link; fi

if [ $? -eq 0 ] ; then
    # 启动php-fpm
    php-fpm
else
   exit 1
fi