#!/bin/bash -x

set -xe

#Run migrations
/var/www/bin/console doctrine:migrations:migrate -n

cd /var/www && composer install

chown www-data:www-data /var/www/src/SfCQRSDemo/Infrastructure/UI/Public/product_images

exec $*
