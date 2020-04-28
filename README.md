# README.MD Symfony 4.4 + Docker (Missions Dashboard)

##  Requirements

- [Docker](https://docs.docker.com/engine/installation/) installed
- [Docker Compose](https://docs.docker.com/compose/install/) installed

## Services

- PHP-FPM 7.3
- Nginx 1.13
- MySQL 5.7

## Installation

1. Clone this repository
    ```bash
    $ git clone https://github.com/alexis77/aqf-test.git
    ```
2. Copy the `.env.dist` file to `.env`
      ```bash
      $ cp .env.dist .env
      ``` 
    You can change the Docker `.env` file according to your needs. The `NGINX_HOST` environment variable allows you to use a custom server name.
    
    MYSQL port is bind to port 3306 in your local, please change in docker-compose.yml if necessary:
    ```
    3306:3006
    ```
    

3. Add the server name in your system host file and flush your [DNS cache](https://www.hostinger.com/tutorials/how-to-flush-dns)

4. Copy the `app/.env.dist` file to `app/.env`
    ```bash
    $ cp app/.env.dist app/.env
    ```
6. Build & run containers with `docker-compose` by using the exec.sh file
    ```bash
    $ ./exec.sh install
    ```

7. Composer install

    first, configure permissions on `symfony/var` folder
    ```bash
    $ docker-compose exec app chown -R www-data:1000 var
    ```
    then
    ```bash
    $ docker-compose exec -u www-data app composer install
    ```

## Access the application

You can access the application in HTTP:
[http://aqf.localhost:8080](http://aqf.localhost:8080)
[http://127.0.0.1:8080](http://127.0.0.1:8080)

**Note:** `symfony-docker.localhost` is the default server name. You can customize it in the `.env` file with `NGINX_HOST` variable.

## Commands

```bash
# bash
$ docker-compose exec app /bin/bash

# Symfony console
$ docker-compose exec -u www-data app bin/console

# configure permissions, e.g. on `var/log` folder
$ docker-compose exec app chown -R www-data:1000 var/log

# MySQL
# access with application account
$ docker-compose exec mysql mysql -usymfony -psymfony
