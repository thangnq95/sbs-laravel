#!/bin/sh

# Author : Thang Nguyen
# Script follows here:

echo "Start!"
#Start service listen discord
php artisan migrate
php artisan db:seed

echo "Finished"
echo "End!"
