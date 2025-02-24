run:
	cp .env.example .env
	cp ./backend/src/.env.example ./backend/src/.env
	docker compose down
	docker compose build
	docker compose up -d
	docker exec php /bin/sh -c "composer install && chmod -R 777 storage bootstrap/cache && php artisan key:generate && php artisan migrate:fresh --seed"
	docker exec frontend /bin/sh -c "cd /app && npm install && npm run build"

stop:
	docker compose down