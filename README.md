## Baraka

Your favourite christian music hub

## SET UP
### Installation

Before you go ahead, make sure you have [docker](https://docs.docker.com/install/), [docker-compose](https://docs.docker.com/compose/install/) and [composer](https://getcomposer.org/download/) installed on
 your
 machine
```bash
git clone https://github.com/reiosantos/baraka.git
```

#### [1] With docker-compose
Running with docker compose:
> Make sure no service is running on port 8000 and 3306

```bash
cd baraka

docker-compose up
```

Access the site on https://localhost:8000
Or the admin site on https://localhost:8000/admin

#### [2] Without docker-compose

> If run without docker-compose, you need to provide the following environment variables

```bash
APP_DEBUG
DB_HOST
DB_USERNAME
DB_PASSWORD
DB_DATABASE
```

> If run without usomg docker compose, you need to install composer dependencies with 

```bash
composer install

composer dump-autoload -o
```

Migrate tables
```bash
docker exec -it baraka-app bash

./vendor/bin/doctrine-migrations migrations:migrate
```
To run this directly on the apache server's root ```/var/www/html/```, You only ned to copy the
 contents of the baraka folder to the web root folder.
 
```bash
cp baraka/* /var/www/html/
``` 
You can then access the site on http://localhost/


