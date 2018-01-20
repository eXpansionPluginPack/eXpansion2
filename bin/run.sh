#!/usr/bin/env bash

# Move into proper directory.
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ..

echo "Clearing caches"
rm -rf var/cache/*

echo ""
echo "Checking for database updates"
./bin/console propel:migration:diff --env=prod
./bin/console propel:migration:migrate --env=prod

echo ""
echo "Launching eXpansion"
./bin/console eXpansion:run --env=prod > /dev/null 2>&1 &
