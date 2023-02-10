start:
	composer install
	cp default.env .env
	./vendor/bin/sail up -d 
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail artisan db:seed

	@echo "Open http://localhost:8989"