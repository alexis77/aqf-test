#!/bin/bash

ID=$(id -u)

case "$1" in

  console)
    shift
    docker-compose exec app bin/console $@
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
