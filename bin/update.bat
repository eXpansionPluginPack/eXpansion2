@echo off

cd %~dp0
cd ..

chcp 65001

ECHO Updating composer
composer self-update

ECHO Updating eXpansion.
composer update --prefer-dist --prefer-stable --no-suggest -o

pause