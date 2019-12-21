#!/bin/bash

composer install
#./vendor/bin/codecept build

./bin/console assets:install
./bin/console doctrine:schema:drop -f
./bin/console doctrine:schema:create
#./bin/console doctrine:migrations:migrate prev -n
#./bin/console doctrine:migrations:migrate -n
#./bin/console app:currency-rate-update