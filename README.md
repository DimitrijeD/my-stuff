# > MyStuff
MyStuff is full Vue.Js SPA with Laravel backend API in Docker environment (Laravel Sail). 
Features: 
- Authentication 
- Chat

## Frontend
- VueJs 3
- VueX 4
- VueRouter 4
- Tailwind 3

## Backend
- Laravel Sail
- PHP 8+
- MySql
- Soketi *(for what ever reason, Laravel-websockets were impossible to configure)*

### Required stuff
Make sure you have following programs installed before installing app:
- Git, PHP, MySql, Composer, Docker, Docker-compose.
*Also, make sure localhost is free (or setup new config), because Sail will not boot app if port/s are occupied.*

## Installation
```
git clone git@github.com:DimitrijeD/my-stuff.git
cd my-stuff
cp .env.example .env
composer install
php artisan key:generate
./vendor/bin/sail up -d
./vendor/bin/sail npm install 
./vendor/bin/sail npm run dev
```
## Tests

Backend is extensively test covered
```
./vendor/bin/sail test
```
Make sure 
```
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```
are running.

## Auth

App uses Sanctum package for authentiaction.
After registering you should open
```
localhost:8025
```
where you can open verification email.
VueJs middleware will prevent you from opening "guest restricted" pages.

## Seeding Data
Command
```
./vendor/bin/sail php artisan db:seed
```
will create at least two users *( You can modify seeder class ./database/seeders/ChatGroupClusterSeeder )*:
- *email* qwe@qwe
- *password* qweqweqweQ1

- *email* asd@asd
- *password* qweqweqweQ1
Seeder will also 
- create and store Chat Rules into Redis *(not required for app to function, but requests will be slower)*,
- create Chat with auto generated messages,
- create few users which have first name *"Test"*.
You can run this seeder as many times as you like. Each time it will make more *"Test"* users and 1 more chat group
in which *qwe@qwe* and *asd@asd* participate.

#### sail up -d
Will run websocket server in background (Soketi package).