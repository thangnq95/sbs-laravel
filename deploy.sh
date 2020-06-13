#!/bin/sh

# Author : Thang Nguyen
# Script follows here:

echo "Start service listen discord"
#Start service listen discord
php artisan serve
php artisan queue:listen
cd discord
php run.php

echo "Starting"
