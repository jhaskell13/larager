Local Project Installation

Pre-requisites:
Laravel Herd: (link to download/installation)
PHP 8.4 (installed and set to default via Herd)

cd your/unzipped/project
composer install
cp .env.example .env 
// update .env variables (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) for your local mysql database connection
// Alternatively use SQLite: remove DB_ variables, and keep DB_CONNECTION=sqlite
php artisan key:generate
php artisan migrate
npm i
npm run dev

open your site in a browser
