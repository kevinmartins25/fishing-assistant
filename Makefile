DOCKER_COMPOSE = docker-compose
SYMFONY = $(DOCKER_COMPOSE) exec php php bin/console
COMPOSER = $(DOCKER_COMPOSE) exec php composer

SUPPORTED_COMMANDS := composer-require
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMAND_ARGS):;@:)
endif

start:	## Start all containers
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop:	## Stop all containers
	$(DOCKER_COMPOSE) stop

kill:	## Kill Docker containers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

prepare-dev:
	$(COMPOSER) install --prefer-dist
	$(SYMFONY) doctrine:database:drop --if-exists -f
	$(SYMFONY) doctrine:database:create
	$(SYMFONY) doctrine:schema:update -f
	$(SYMFONY) hautelook:fixtures:load -q

cache-clear:
	$(SYMFONY) cache:clear

composer-require:
	$(COMPOSER) require $(COMMAND_ARGS)