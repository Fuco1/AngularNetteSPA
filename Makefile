run:
	docker-compose up -d
	./node_modules/.bin/webpack-dev-server --host=0.0.0.0 --progress --colors --devtool cheap-module-inline-source-map --hot --inline --watch

deps:
	docker-compose up -d
	docker-compose exec -T app composer install
	npm install

test-db:
	docker-compose exec -T mysql mysql -uroot -p123 -e "create database if not exists cosmonauts_test;"

test: test-db
	docker-compose exec -T app vendor/bin/phing build-fast

test-slow: test-db
	docker-compose exec -T app vendor/bin/phing build-slow

cs-fix:
	docker-compose exec -T app vendor/bin/phing cs-fix
