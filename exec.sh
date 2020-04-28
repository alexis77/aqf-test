#!/bin/bash

ID=$(id -u)

case "$1" in

  install)
    docker-compose build &&
    docker-compose up -d &&
    docker-compose exec afq_app chown -R www-data:1000 var &&
    docker-compose exec -u www-data afq_app composer install &&
    docker-compose exec afq_app bin/console doctrine:migrations:migrate --no-interaction
    ;;
  up)
    docker-compose up -d
    ;;
  stop)
    docker-compose stop
    ;;
  console)
    shift
    docker-compose exec afq_app bin/console $@
    ;;
  console)
    shift
    docker-compose exec afq_app bin/phpunit $@
    ;;
  composer)
    cd app && composer ${@:2}
    ;;

  update)
    docker-compose exec afq_app bin/console doctrine:migrations:migrate --no-interaction
    docker-compose exec afq_app composer install --prefer-dist
    ;;
  *)
esac
