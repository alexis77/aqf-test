#!/bin/bash

ID=$(id -u)

case "$1" in

  install)
    docker-compose build &&
    docker-compose up -d &&
    docker exec -it afq_app chown -R www-data:1000 var &&
    docker exec -it -u www-data afq_app composer install &&
    docker exec -it afq_app ./bin/console doctrine:migrations:migrate --no-interaction
    ;;
  up)
    docker-compose up -d
    ;;
  stop)
    docker-compose stop
    ;;
  console)
    shift
    docker exec afq_app ./bin/console $@
    ;;
  phpunit)
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
