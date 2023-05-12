COMPOSE_FILE_ARGS ?= -f docker-compose.yml

DOCKER_COMPOSE = docker-compose $(COMPOSE_FILE_ARGS)

.PHONY: start
start: build start-deps db

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: build
build:
	$(DOCKER_COMPOSE) up -d --build

.PHONY: down
down:
	$(DOCKER_COMPOSE) down --remove-orphans

.PHONY: start-deps
start-deps:
	$(DOCKER_COMPOSE) run --rm start_dependencies

.PHONY: db
db: ## recreate database
	bin/console d:d:d --force --if-exists
	bin/console d:d:c --if-not-exists
	bin/console d:m:m -n

.PHONY: shell
shell:
	$(DOCKER_COMPOSE) exec php sh

.PHONY: ps
ps:
	$(DOCKER_COMPOSE) ps

.PHONY: logs
logs:
	$(DOCKER_COMPOSE) logs -f

.PHONY: restart
restart:
	$(DOCKER_COMPOSE) restart

.PHONY: composer-install
composer-install:
	bin/composer --no-ansi -n install

db-setup:
	bin/composer --no-ansi -n db-setup

db-migrate:
	bin/composer --no-ansi -n db-migrate

db-fixtures:
	bin/composer --no-ansi -n db-fixtures

phpunit:
	make start
	bin/phpunit tests

.DEFAULT: start