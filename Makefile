app-up:
	@bash -l -c 'docker-compose up -d'

app-down:
	@bash -l -c 'docker-compose down'

app-access:
	@docker exec -it scm-backend bash

app-db:
	@docker exec -it scm-database sh -c "mysql -u root -ppassword scm"

build: composer db-migrate db-data

composer:
	@docker exec -i scm-backend composer install

db-migrate:
	@docker exec -i scm-backend php bin/console --env=dev d:m:m --no-interaction

db-data:
	@docker exec -i scm-backend php bin/console --env=dev d:f:l --no-interaction

phpcs:
	@docker exec -i scm-backend ./vendor/bin/phpcs src/ scm/ infrastructure/

phpstan:
	@docker exec -i scm-backend ./vendor/bin/phpstan

phpunit:
	@docker exec -i scm-backend ./vendor/bin/phpunit