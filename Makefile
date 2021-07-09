start:
	php -S localhost:8000 -t public public/index.php
install:
	composer install
create:
	touch db.sqlite