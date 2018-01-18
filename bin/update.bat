@echo off

cd %~dp0
cd ..

chcp 65001

ECHO Updating composer
composer self-update

ECHO Updating eXpansion.
composer --prefer-dist --no-scripts --no-suggest --ignore-platform-reqs --no-dev
