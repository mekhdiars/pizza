setup:
	composer install
	cp -n .env.example .env
	php artisan key:generate
	php artisan sail:install

up:
	./vendor/bin/sail up -d

migrate:
	./vendor/bin/sail artisan migrate

test_migrate:
	./vendor/bin/sail artisan migrate --env=testing

seed:
	./vendor/bin/sail artisan db:seed

stop:
	./vendor/bin/sail stop

down:
	./vendor/bin/sail down

build:
	./vendor/bin/sail build --no-cache

artisan:
	./vendor/bin/sail artisan $(cmd)

test:
	./vendor/bin/sail artisan test

composer:
	./vendor/bin/sail composer $(cmd)

publish:
	./vendor/bin/sail artisan sail:publish

clear:
	./vendor/bin/sail artisan optimize:clear

shell:
	./vendor/bin/sail shell

help:
	@echo "Доступные команды:"
	@echo "  make help         - Показать список команд"
	@echo "  make up           - Запустить контейнеры Sail"
	@echo "  make down         - Остановить контейнеры Sail"
	@echo "  make build        - Пересобрать контейнеры без использования кэша"
	@echo "  make artisan cmd=\"<команда>\"  - Выполнить команду artisan через Sail"
	@echo "  make test         - Запустить тесты (artisan test)"
	@echo "  make composer cmd=\"<команда>\" - Выполнить команду composer через Sail"
	@echo "  make publish      - Опубликовать Sail assets (sail:publish)"
	@echo "  make clear        - Очистить кеши Laravel (optimize:clear)"
	@echo "  make shell        - Открыть интерактивный шелл внутри контейнера"
