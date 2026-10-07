.PHONY: start cleanup console trigger-runner trigger-worker

PROJECT=library-api
CMD=seed

# How many workers `make trigger-worker` starts, e.g. `make trigger-worker WORKERS=3`.
WORKERS=1

start:
	@docker compose -p $(PROJECT) up -d mysql
	@docker compose -p $(PROJECT) up migrate
	@docker compose -p $(PROJECT) up -d --build api
	@docker compose -p $(PROJECT) up mailpit

cleanup:
	@docker compose -p $(PROJECT) down \
		--volumes \
		--remove-orphans \
		--rmi local

console:
	@docker compose -p $(PROJECT) exec api php bin/console.php $(CMD)

# `up` rather than `run` so the containers get their real compose names
# ($(PROJECT)-runner-1, $(PROJECT)-worker-1...) instead of a one-off run name.
trigger-runner:
	@docker compose -p $(PROJECT) up --build runner

# Runs in the foreground across every worker; ctrl-c stops them all, and each
# one finishes the batch it is holding before it goes.
trigger-worker:
	@docker compose -p $(PROJECT) up --build --scale worker=$(WORKERS) worker
