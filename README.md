
## Requirements

- PHP 8+
- Laravel 9
- SQLite Driver
- NPM installed


## Installation
- Clone the repository
- Composer update
- run: npm install
- run: npm run dev
- Rename .env.example to .env
- run: php artisan key:generate
- run: php artisan migrate:fresh --seed
- if any error is thrown in the console then check if your sqlite driver is working or not. if working then try to run: touch database/database.sqlite and then run php artisan migrate --seed
- run: php artisan optimize
- run: php artisan cache:clear
- run: php artisan serve
- Go to the browser with the url
- That's it! Application has been started.

## Credential
- User name= admin@email.com
- Password= password

## Using the application
- After you enter the application you should see the login page.
- Login to the application with the given credential above.
- After login to the system you should see the dashboard with the option to file an upload form.
- Upload input.csv file and you will get the result.

## Run PHPStan
- Run: php ./vendor/phpstan/phpstan/phpstan analyse --generate-baseline --memory-limit=2G

## Run PHPUnit
- Install PHPUnit throgh composer in global with this command: composer global require phpunit/phpunit
- run php artisan config:clear (for safe)
- run: phpunit
- run: phpunit --filter test_logged_in_users_can_upload_and_calculate_fee (this is only for the fee task)

## Note
In the task description the output result now is not right. Due to big changes in JPY currency with the EUR one result is not the same as the given output result.
