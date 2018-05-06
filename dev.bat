chcp 65001
php bin\console cache:clear --env=dev --no-warmup
php bin\console eXpansion:run -vvv --env=dev
pause