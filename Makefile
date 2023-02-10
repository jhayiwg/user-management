start:
	composer install
	cp default.env .env
	./vendor/bin/sail up -d 
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail artisan migrate:fresh
	./vendor/bin/sail artisan db:seed
	./vendor/bin/sail npm install
	./vendor/bin/sail npm run build

	@echo "Open http://localhost:8989"