# English Champions League

## How to install
0) copy .env.example to .env file
1) run `docker-compose up -d --build`
2) run `docker-compose run --rm composer install`
3) run `docker-compose run --rm artisan key:generate`
4) run `docker-compose run --rm artisan migrate`
5) run `docker-compose run --rm artisan db:seed`
