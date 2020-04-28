#!/bin/bash

ID=$(id -u)

case "$1" in

  install)
    docker-compose build &&
    docker-compose up -d &&
    docker-compose exec app chown -R www-data:1000 var &&
    docker-compose exec -u www-data app composer install &&
    docker-compose exec app bin/console doctrine:migrations:migrate --no-interaction
    ;;
  up)
    docker-compose up -d
    ;;
  stop)
    docker-compose stop
    ;;
  console)
    shift
    docker-compose exec app bin/console $@
    ;;
  console)
    shift
    docker-compose exec app bin/phpunit $@
    ;;
  composer)
    cd app && composer ${@:2}
    ;;

  update)
    docker-compose exec app bin/console doctrine:migrations:migrate --no-interaction
    docker-compose exec app composer install --prefer-dist
    ;;
  *)
esac
