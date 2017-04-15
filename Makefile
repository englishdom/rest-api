.DEFAULT_GOAL := help

build : create-env dc-build dc-up build-backend

####################################################################################################
# Create environment
####################################################################################################
create-env:
	cp -u docker/.env.develop .env

####################################################################################################
# Work with a docker
####################################################################################################
dc-build: ## Create docker images described in docker-compose.yml
	docker-compose build

dc-up: ## Create docker containers described in docker-compose.yml
	docker-compose up -d

dc-down: ## Stopping and remove docker containers described in docker-compose.yml
	docker-compose down

dc-stop: ## Just stoppping docker containers described in docker-compose.yml
	docker-compose stop

dc-start: ## Start docker containers described in docker-compose.yml
	docker-compose start

####################################################################################################
# Prepare the project
####################################################################################################
build-backend: ## Build backend
	docker-compose exec --user www-data php sh -c "composer install"

####################################################################################################
# Connect to containers
####################################################################################################
console-nginx: ## Connect to nginx (user root)
	docker-compose exec nginx bash

console-php: ## Connect to php (user root)
	docker-compose exec php bash

console-node: ## Connect to node (user www-data)
	docker-compose run --user www-data --rm node bash

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'