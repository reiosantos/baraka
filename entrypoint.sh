#!/usr/bin/env bash

composer install
composer dump-autoload -o

#./vendor/bin/doctrine orm:schema-tool:create
#yes | ./vendor/bin/doctrine-migrations migrations:migrate

apache2-foreground
