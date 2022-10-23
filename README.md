# MyStuff

MyStuff is full Vue.Js SPA with Laravel backend API in Docker environment (Laravel Sail). 
Features: 
- Authentication 
- Chat

## Installation

- git clone git@github.com:DimitrijeD/my-stuff.git
- cd my-stuff
- cp .env.example .env
- composer install
- ./vendor/bin/sail up
- sail composer install
- sail npm install 
- sail npm run dev

## Seeding Data

Command

- sail php artisan db:seed

will create at least two users:
- email qwe@qwe
- password qweqweqweQ1

- email asd@asd
- password qweqweqweQ1

Seeder will also 
- create and store Chat Rules into Redis (not required for app to function, but requests will be slower),
- create Chat with auto generated messages.