# Docker LEMP Stack

A containerized LEMP stack using Docker Compose. Brings up Nginx, PHP-FPM 8.2, MariaDB 10.11, and Redis 7 as isolated containers on a private network with a single command.

## Stack

| Container | Image | Port |
|-----------|-------|------|
| Nginx | nginx:1.25-alpine | 80 (public) |
| PHP-FPM | custom (php:8.2-fpm-alpine) | Internal only |
| MariaDB | mariadb:10.11 | Internal only |
| Redis | redis:7-alpine | Internal only |

## Architecture

    Browser
      |
    Nginx :80 (public)
      |
    PHP-FPM :9000 (internal — FastCGI)
      |
    MariaDB :3306 (internal only)
    Redis   :6379 (internal only)

All containers share a private bridge network (lemp_network). Only Nginx is exposed to the host on port 80. MariaDB and Redis are not accessible from outside the Docker network.

## What the PHP image includes

Built from php:8.2-fpm-alpine with the following additions:

- pdo_mysql, mysqli — database connectivity
- gd — image processing (with freetype, jpeg, webp support)
- zip, intl, mbstring, opcache, bcmath, exif — standard PHP extensions
- redis — installed via PECL
- imagick — installed via PECL
- OPcache configured for production
- Custom php.ini: 64M upload limit, 256M memory limit, 300s max execution

## Usage

    # Clone the repo
    git clone https://github.com/hamzanaqvix-code/docker-lemp-stack.git
    cd docker-lemp-stack

    # Copy and configure environment variables
    cp .env.example .env
    nano .env

    # Start the stack
    docker compose up --build

    # Start in background
    docker compose up --build -d

    # Stop the stack
    docker compose down

    # Stop and remove volumes (wipes database)
    docker compose down -v

## Environment variables

Copy .env.example to .env and set your values before starting.

    MYSQL_ROOT_PASSWORD=your_root_password
    MYSQL_DATABASE=lemp_db
    MYSQL_USER=lemp_user
    MYSQL_PASSWORD=your_db_password

The .env file is gitignored and never committed. Use .env.example as the template.

## Verify the stack

Visit http://localhost/index.php after starting. Expected output:

- PHP Version: 8.2.x
- PHP SAPI: fpm-fcgi (confirms PHP-FPM is handling requests)
- MariaDB: Connected successfully
- Redis: Connected and read/write verified
- OPcache: Enabled
- Imagick: Loaded
- GD: Loaded

## Useful commands

    # View running containers
    docker compose ps

    # View logs
    docker compose logs -f

    # View logs for specific service
    docker compose logs -f php

    # Rebuild after Dockerfile changes
    docker compose up --build

    # Shell into PHP container
    docker compose exec php sh

    # Shell into MariaDB
    docker compose exec mariadb mariadb -u lemp_user -p lemp_db

## Verified on

- Docker 29.5.2 on darwin/arm64 (Apple M2)
- Docker Compose v5.1.3

## Related projects

- lemp-stack-bootstrap: https://github.com/hamzanaqvix-code/lemp-stack-bootstrap
- thunderstack-bootstrap: https://github.com/hamzanaqvix-code/thunderstack-bootstrap
- openlitespeed-wordpress-bootstrap: https://github.com/hamzanaqvix-code/openlitespeed-wordpress-bootstrap
- terraform-do-infrastructure: https://github.com/hamzanaqvix-code/terraform-do-infrastructure
