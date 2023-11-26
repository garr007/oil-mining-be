## API doc

postman: [link](https://documenter.getpostman.com/view/19672778/2s9YeEarGP)

## Prerequisites

-   MySQL version 8 (tested on 8.0.30)
-   php ver sufficient to run laravel 10.33.0

## Getting started

1. `composer install` to install dependencies

2. `cp .env.example .env`

3. php artisan key:generate

4. create database (name is same as specified in .env)

5. `php artisan migrate`

6. (optional) `php artisan db:seed` to seed database. It includes user/employees with it's status, positions, and division. By default it will seed 1 admin and 1 manager for each division.
