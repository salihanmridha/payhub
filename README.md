
## Task
https://gist.github.com/PayseraGithub/ef2a59d0a6d6e680af2e46ccff1bca37

## Requirements

- PHP 8+
- Laravel 9
- NPM installed


## Installation
- Clone the repository
- Composer update
- run: npm install
- run: npm run build or npm run dev
- Rename .env.example to .env
- run: php artisan key:generate
- run: php artisan optimize
- run: php artisan cache:clear
- run: php artisan serve
- Go to the browser with the url
- That's it! Application has been started.

## Using the application
- After you enter the application you should see the page with file uploading form.
- Upload input.csv file and you will get the result.

## Run PHPStan
- Run: php ./vendor/phpstan/phpstan/phpstan analyse --memory-limit=2G

## Run PHPUnit
- Install PHPUnit throgh composer in global with this command: composer global require phpunit/phpunit
- run php artisan config:clear (for safe)
- run: phpunit
- run: phpunit --filter test_users_can_upload_and_calculate_fee (this is only for the fee task)

## Note
In the task description the output result now is not right. Due to big changes in JPY currency with the EUR, one result is not the same as the given output result.
