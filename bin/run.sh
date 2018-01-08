#!/usr/bin/env bash

# Move into proper directory.
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ..

echo "Checking for database updates"
./bin/console propel:migration:diff
./bin/console propel:migration:migrate

echo ""
echo "Clearing caches"
./bin/console cache:clear --no-warmup --env=prod

echo ""
echo "Launching eXpansion"
./bin/console eXpansion:run --env=prod > /dev/null 2>&1 &
