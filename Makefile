.PHONY: setup

setup:
	@docker compose -p library-api up -d mysql
	@sleep 5
	@docker compose -p library-api up -d migrate

start:
	@docker compose -p library-api up -d --build api

cleanup:
	@docker compose -p library-api down \
		--volumes \
		--remove-orphans \
		--rmi local