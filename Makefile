.PHONY: start cleanup console

PROJECT=library-api
CMD=seed

start:
	@docker compose -p $(PROJECT) up -d mysql
	@docker compose -p $(PROJECT) up migrate
	@docker compose -p $(PROJECT) up -d --build api

cleanup:
	@docker compose -p $(PROJECT) down \
		--volumes \
		--remove-orphans \
		--rmi local

console:
	@docker compose -p $(PROJECT) exec api php bin/console.php $(CMD)