#!/usr/bin/env bash

# Move into proper directory.
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ..

echo "Updating composer"
composer self-update

echo "Updating eXpansion"
composer update --prefer-dist --prefer-stable --no-suggest --no-dev -o
