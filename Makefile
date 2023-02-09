start:
	./vendor/bin/sail up -d 
	./vendor/bin/sail artisan migrate:fresh
	./vendor/bin/sail artisan db:seed

	@echo "Open http://localhost:8989"