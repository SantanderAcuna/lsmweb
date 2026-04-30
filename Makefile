# ---------------------------------------------------------------------------
# lsmweb — developer convenience targets
# Usage: `make <target>`. Run `make help` for the list.
# ---------------------------------------------------------------------------

DC          ?= docker compose
BACKEND_SVC ?= backend
FRONTEND_DIR?= frontend
BACKEND_DIR ?= backend

.DEFAULT_GOAL := help
.PHONY: help up down build rebuild logs ps shell-backend \
        migrate seed passport-keys \
        test-backend test-frontend test \
        lint lint-backend lint-frontend \
        composer-install npm-install

help: ## Show this help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

# ---------------------- Stack lifecycle ----------------------

up: ## Start the full stack in the background (mysql + backend + nginx + frontend).
	$(DC) up -d --build

down: ## Stop the stack and remove containers (keeps named volumes).
	$(DC) down

build: ## Build all images without starting containers.
	$(DC) build

rebuild: ## Force rebuild from scratch (no cache) and start.
	$(DC) build --no-cache
	$(DC) up -d

logs: ## Tail logs from every service.
	$(DC) logs -f --tail=200

ps: ## Show running services.
	$(DC) ps

shell-backend: ## Open a shell inside the backend (php-fpm) container.
	$(DC) exec $(BACKEND_SVC) sh

# ---------------------- Laravel one-shots ----------------------

migrate: ## Run pending database migrations against the running stack.
	$(DC) exec $(BACKEND_SVC) php artisan migrate --force

seed: ## Run database seeders.
	$(DC) exec $(BACKEND_SVC) php artisan db:seed --force

passport-keys: ## Generate Laravel Passport encryption keys (idempotent with --force).
	$(DC) exec $(BACKEND_SVC) php artisan passport:keys --force

# ---------------------- Tests ----------------------

test-backend: ## Run PHPUnit suite (uses phpunit.xml testing env: sqlite in-memory).
	cd $(BACKEND_DIR) && vendor/bin/phpunit

test-frontend: ## Run Vitest suite.
	cd $(FRONTEND_DIR) && npm run test

test: test-backend test-frontend ## Run both backend and frontend test suites.

# ---------------------- Lint / static analysis ----------------------

lint-backend: ## Laravel Pint in --test mode (no auto-fix).
	cd $(BACKEND_DIR) && vendor/bin/pint --test

lint-frontend: ## ESLint over .vue/.ts/.js files.
	cd $(FRONTEND_DIR) && npm run lint

lint: lint-backend lint-frontend ## Run all linters.

# ---------------------- Local install (no Docker) ----------------------

composer-install: ## Install backend PHP deps locally.
	cd $(BACKEND_DIR) && composer install

npm-install: ## Install frontend node deps locally.
	cd $(FRONTEND_DIR) && npm ci
