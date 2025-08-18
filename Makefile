# Variables
DC=docker compose --file docker-compose.dev.yml 

.PHONY: up down logs migrate

up:
	$(DC) up -d --build
	$(DC) exec app composer install

down:
	$(DC) down

logs:
	$(DC) logs -f -n 10

migrate:
	docker compose exec app php artisan migrate
