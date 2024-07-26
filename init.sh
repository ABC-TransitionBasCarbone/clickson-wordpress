echo ">> composer install"
composer install --no-cache

echo ">> Working on missing files and folders ..."
mkdir -p ./wordpress/wp-content/uploads
cp .env-sample .env

echo ">>Installation in dev environement"
docker build -t harbor.norsys-afrique.ma/clickson/clickson-wp-dev:latest docker --no-cache
docker-compose up -d

echo ">> fin"

