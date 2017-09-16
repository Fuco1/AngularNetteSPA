#!/bin/bash

docker-compose exec app php www/index.php --ansi "$@"
