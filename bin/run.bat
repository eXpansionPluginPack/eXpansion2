@echo off

cd %~dp0
cd ..

chcp 65001

ECHO Checking for database updates
php bin/console propel:migration:diff --env=prod
php bin/console propel:migration:migrate --env=prod

ECHO Clearing caches
php bin/console cache:clear --no-warmup --env=prod

ECHO Launching eXpansion
php bin\console eXpansion:run -vvv --env=prod
