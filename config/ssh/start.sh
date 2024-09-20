echo "**** Configure source ****"

# chown -R www-data:www-data /var/www/dev.org
# chmod -R 777 /var/www/dev.org
# chmod -R 777 /var/www/dev.org/*

cd ./dev.org
compose update
npm install