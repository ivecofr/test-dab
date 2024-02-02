help:
	@echo "Commandes dans le projet"
	@echo ""
	@echo "Commandes Docker"
	@echo "-------------------"
	@echo "up       docker compose up -d"
	@echo "down     docker compose down"
	@echo "ps       docker compose ps"
	@echo "build    docker compose build"
	@echo ""
	@echo "Commandes Composer"
	@echo "---------------------"
	@echo "composer-show              docker compose exec php composer show"
	@echo "composer-dump-autoload     docker compose exec php composer dump-autoload"
	@echo ""
	@echo "Commandes Yarn"
	@echo "-----------------"
	@echo "yarn-dev                   docker compose exec node yarn encore dev"
	@echo "yarn-dev-config [nom]      docker compose exec node yarn encore dev --config-name [nom]"
	@echo "yarn-prod                  docker compose exec node yarn encore prod"
	@echo "yarn-watch                 docker compose exec node yarn watch"
	@echo "yarn-watch-config [nom]    docker compose exec node yarn watch --config-name [nom]"
	@echo "yarn-watch-old             docker compose exec node yarn encore dev --watch --watch-poll=300"
	@echo ""
	@echo "Commandes de la base de données"
	@echo "----------------------------------"
	@echo "db-drop             docker compose exec php php bin/console d:d:d --force"
	@echo "db-create           docker compose exec php php bin/console d:d:c"
	@echo "db-update           docker compose exec php php bin/console d:s:u --force"
	@echo "db-update-dump      docker compose exec php php bin/console d:s:u --dump-sql"
	@echo "db-schema-create    docker compose exec php php bin/console d:s:c"
	@echo "db-migrate          docker compose exec php php bin/console d:m:m"
	@echo "db-diff             docker compose exec php php bin/console d:m:diff"
	@echo "db-reset            drop, create and migrate"
	@echo ""
	@echo "Commandes de vérification des normes du code"
	@echo "-------------------------"
	@echo "quality-check                       phpcs-check + phpstan-check + rector-check"
	@echo "phpcs-fix	                       corrige le code selon les normes de codage symfony/php"
	@echo "phpcs-check                         affiche les normes non respectées par fichier"
	@echo "phpcs-describe [nom de la règle]    affiche la définition de la règle"
	@echo "phpstan-check                       lance l'analyse phpstan"
	@echo "rector-check                        lance l'analyse rector"
	@echo "rector-fix                          lance les fix automatique de rector"
	@echo ""
	@echo "Commandes Symfony"
	@echo "-------------------------"
	@echo "symfony [nom de la commande]		exécute la commande Symfony passée en paramètre"
	@echo "module					docker compose exec php php bin/console make:module"
	@echo ""
	@echo "Commandes PHPUnit"
	@echo "-------------------------"
	@echo "phpunit	       exécute les tests unitaires du projet"
	@echo ""
	@echo "Commandes autres"
	@echo "-------------------------"
	@echo "fos-routing	   docker compose exec php php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json"

up:
	docker compose up -d

down:
	docker compose down

ps:
	docker compose ps

build:
	docker compose build

composer-show:
	docker compose exec php composer show

composer-dump-autoload:
	docker compose exec php composer dump-autoload

yarn-dev:
	docker compose exec node yarn encore dev

yarn-dev-config:
	docker compose exec node yarn encore dev --config-name $(filter-out $@,$(MAKECMDGOALS))

yarn-prod:
	docker compose exec node yarn encore prod

yarn-watch:
	docker compose exec node yarn watch

yarn-watch-config:
	docker compose exec node yarn watch --config-name $(filter-out $@,$(MAKECMDGOALS))

yarn-watch-old:
	docker compose exec node yarn encore dev --watch --watch-poll=300
	docker compose exec node yarn encore dev --watch --watch-poll=300

db-drop:
	docker compose exec php php bin/console d:d:d --force

db-create:
	docker compose exec php php bin/console d:d:c

db-update:
	docker compose exec php php bin/console d:s:u --force

db-update-dump:
	docker compose exec php php bin/console d:s:u --dump-sql

db-schema-create:
	docker compose exec php php bin/console d:s:c

db-migrate:
	docker compose exec php php bin/console d:m:m

db-diff:
	docker compose exec php php bin/console d:m:diff

db-reset:
	$(MAKE) db-drop
	$(MAKE) db-create
	docker compose exec php php bin/console d:m:m -n

phpcs-fix:
	-docker compose exec php php-cs-fixer fix --config=.php-cs-fixer.php

phpcs-check:
	-docker compose exec php php-cs-fixer fix --config=.php-cs-fixer.php --verbose --dry-run

phpcs-describe:
	docker compose exec php php-cs-fixer describe $(filter-out $@,$(MAKECMDGOALS))

symfony:
	docker compose exec php php bin/console $(filter-out $@,$(MAKECMDGOALS))

phpunit:
	-docker compose exec php php bin/phpunit

fos-routing:
	-docker compose exec php php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

phpstan-check:
	docker compose exec php vendor/bin/phpstan analyse

rector-check:
	docker compose exec php php vendor/bin/rector process --dry-run

rector-fix:
	docker compose exec php php vendor/bin/rector process

quality-check:
	-docker compose exec php php-cs-fixer fix --config=.php-cs-fixer.php --verbose --dry-run
	docker compose exec php vendor/bin/phpstan analyse
	docker compose exec php php vendor/bin/rector process --dry-run

module:
	docker compose exec php php bin/console make:module
	docker compose exec php composer dump-autoload
