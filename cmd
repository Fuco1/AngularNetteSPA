#!/bin/bash

if [ "$1" = "--test" ] ; then
    shift
    docker-compose exec app php tests/index.php --ansi "$@"
else
    docker-compose exec app php www/index.php --ansi "$@"
fi
