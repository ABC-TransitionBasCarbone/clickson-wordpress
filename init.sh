echo ">> composer install"
composer install --no-cache

echo ">> Working on missing files and folders ..."
mkdir -p ./wordpress/wp-content/uploads
cp .env-sample .env

echo ">> Creating certificate files"
mkdir docker/certs
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout certs/localhost.key -out certs/localhost.crt -subj "/CN=localhost"

echo ">>Installation in dev environement"
docker build -t harbor.norsys-afrique.ma/clickson/clickson-wp-dev:latest docker --no-cache
docker-compose up -d

echo ">> Importing the initial database..."
docker exec -i clickson-wordpress_db_1 mysql -uroot -proot clickson_wp_db < database/backup-wp-clickson.sql
echo ">> fin"

