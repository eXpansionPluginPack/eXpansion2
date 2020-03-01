@echo off

cd %~dp0
cd ..

chcp 65001

ECHO Clearing caches
RMDIR /S /Q var\cache\prod

ECHO Checking for database updates
php bin/console propel:migration:diff --env=prod
php bin/console propel:migration:migrate --env=prod

ECHO Launching eXpansion
php bin\console eXpansion:run -vvv --env=prod

pause