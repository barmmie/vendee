start:
	docker-compose up -d --build app

artisan:
	docker-compose run --rm artisan ${command}

composer:
	docker-compose run --rm composer ${command}

npm:
	docker-compose run --rm npm ${command}

test:
	docker-compose run --rm artisan test ${command}


