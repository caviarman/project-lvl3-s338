install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 app database public resources routes storage tests

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 app database public resources routes storage tests

test:
	composer run-script phpunit tests