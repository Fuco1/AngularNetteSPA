run:
	docker-compose up -d
	./node_modules/.bin/webpack-dev-server --host=0.0.0.0 --progress --colors --devtool cheap-module-inline-source-map --hot --inline --watch

deps:
	docker-compose up -d
	docker-compose exec -T app composer install
	npm install
