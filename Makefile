# Parameters
SHELL         = bash
PROJECT       = scm-backend
PROJECT_DB    = scm-database
GIT_AUTHOR    = Qamar Hayat
HTTP_PORT     = 3001


# Executables
EXEC_PHP      = php
COMPOSER      = composer
GIT           = git

# Alias
#SYMFONY       = docker exec www_docker_symfony $(EXEC_PHP) bin/console
SYMFONY 	  = $(SYMFONY_BIN) console
PHP			  = $(EXEC_PHP) bin/console
# if you use php you can replace "with: $(EXEC_PHP) bin/console"

Executables: vendors
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS        = ./vendor/bin/phpcs
CODESNIFFER   = ./vendor/squizlabs/php_codesniffer/bin/phpcs

# Executables: local only
SYMFONY_BIN   = symfony 
apt-get       = sudo apt-get
DOCKER        = docker
DOCKER_COMP   = docker-compose

# Misc
.DEFAULT_GOAL = help
.PHONY       = 

## —— 🐝 View full command 🐝 —————————————————————————————————————————————————————————————

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer 🧙‍♂️ ——————————————————————————————————————————————————————————————————————————
install: ## Install vendors according to the current composer.lock file
	$(DOCKER) exec $(PROJECT) composer install --no-progress --prefer-dist --optimize-autoloader
	
update:## update composer
	$(DOCKER) exec  $(PROJECT) composer update --dev --no-interaction -o

## —— Symfony environnement🎵 ———————————————————————————————————————————————————————————————

sf: ## List all Symfony commands
	$(DOCKER) exec -i $(PROJECT) $(PHP) 

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(DOCKER) exec -i $(PROJECT) $(PHP) c:c

warmup: ## Warmup the cache
	$(DOCKER) exec -i $(PROJECT) $(PHP) cache:warmup

fix-perms: ## Fix permissions of all var files
	sudo chmod 777 ./var ./vendor ./php ./ .git

assets: purge ## Install the assets with symlinks in the public folder
	$(DOCKER) EXEC -I $(PROJECT) $(PHP) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

entity: ## create Entity (before using this command, connect on your container with make:bash)
	$(DOCKER) EXEC -I $(PROJECT) $(PHP) make:entity

migration: ## make migration (before using this command, connect on your container with make:bash)
	$(DOCKER) exec -i $(PROJECT) $(PHP) make:migration --no-interaction

migrate: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	$(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev d:m:m --no-interaction

migrate-force: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	$(DOCKER) exec -i $(PROJECT) $(PHP) doctrine:schema:update --force

crud : ## make crud (create reset delete)(before using this command, connect on your container with make:bash)
	$(DOCKER) exec -i $(PROJECT) $(PHP) make:crud 

controller : ## make controller (before using this command, connect on your container with make:bash)
	 $(DOCKER) exec -i $(PROJECT) $(PHP) make:controller 

router : ## debugging App routing
	$(DOCKER) exec -i $(PROJECT) $(PHP) debug:router

dispatcher : ## see dispatcher event
	 $(DOCKER) exec -i $(PROJECT) $(PHP) debug:event-dispatcher
	
framework : ## see framework config
	$(DOCKER) exec -i $(PROJECT) $(PHP) debug:config framework 

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Start the docker hub (MySQL,phpMyadmin,php)
	$(DOCKER_COMP) up -d

docker-build: ## Builds the PHP image
	$(DOCKER_COMP) build 

down: ## Stop the docker hub
	$(DOCKER_COMP) down --remove-orphans

destroy: ## destroy  docker containers
	$(DOCKER_COMP) rm -v --force --stop || true

restart: ## restart docker containers
	$(DOCKER_COMP) restart $$(docker  -l -c )	

bash: ## Connect to the application container
	$(DOCKER) exec -it  $(PROJECT)  bash

kill-r-containers: ## Kill all running containers 
	$(DOCKER) kill $$(docker ps -q)

delete-s-containers: ## Delete all stopped containers
	$(DOCKER) rm $$(docker ps -a -q)

delete-images: ## Delete Delete all images
	$(DOCKER) rmi $$(docker images -q)

stop-containers: ## Stop all containers
	$(DOCKER) stop `docker ps -q`

## —— Stripe 💳 ————————————————————————————————————————————————————————————————

stripe: ## install stripe
	$(DOCKER) exec  $(PROJECT) composer require stripe/stripe-php

## —— Project 🐝 ———————————————————————————————————————————————————————————————

commands: ## Display all commands in the project namespace
	$(DOCKER) exec -i $(PROJECT) $(PHP)  list $(PROJECT)

build : docker-build up install load-fixtures   ## Build project, Install vendors according to the current composer.lock file load fixtures

reload: restart load-fixtures  ## Load fixtures 

stop: down  ## Stop docker and the Symfony binary server


load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:cache:clear-metadata
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:database:create --if-not-exists
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:schema:drop --force
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:schema:create
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:schema:validate
	 $(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:fixtures:load --no-interaction

rebuild-database: drop-db create-db migration migrate-force load-fixtures ## Drop database, create database, Doctrine migration migrate,reload fixtures

create-db:## Create the database
	$(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev doctrine:database:create --if-not-exists --no-interaction

reload-fixtures:## reload just fixtures
	$(DOCKER) exec -i $(PROJECT) $(PHP) --env=dev d:f:l --no-interaction

drop-db:## Drop the  database (before using this command, connect on your container with make:bash)
	$(DOCKER) exec -i $(PROJECT) $(PHP)   doctrine:database:drop --force --no-interaction

app:
	$(DOCKER) exec -it $(PROJECT) bash

app-db: ## MYSQL CLI access
	$(DOCKER) exec -it $(PROJECT_DB) sh -c "mysql -u root -ppassword scm"

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml check ## Run main functional and unit tests
	@$(eval testsuite ?= 'main') # or "external"
	@$(eval filter ?= '.')
	@$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter) --stop-on-failure


test-all: phpunit.xml ## Run all tests
	$(PHPUNIT) --stop-on-failure

phpunit: ## Run PHP unit test
	@$(DOCKER) exec -i $(PROJECT) $(PHPUNIT)

phpstan: ## Run PHP STAN test
	@$(DOCKER) exec -i $(PROJECT) $(PHPSTAN)

phpcs: ## Run PHP CS test
	@$(DOCKER) exec -i $(PROJECT) $(PHP_CS) src/ scm/ infrastructure/

phpmd:
	@$(DOCKER) exec -i $(PROJECT) ./vendor/bin/phpmd src/,scm/,infrastructure/ ansi ./phpmd.xml --exclude src/Kernel.php