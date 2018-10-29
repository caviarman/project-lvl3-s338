install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 app public resources routes 
lint-fix:
	composer run-script phpcbf -- --standard=PSR12 app public resources routes

test:
	composer run-script phpunit tests