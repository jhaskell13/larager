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

# Larager — Local Installation Guide

## Prerequisites

Before installing the project, ensure the following software is installed on your machine.

### Required Software

- PHP **8.4+**
- Composer
- Node.js + npm
- MySQL

### Recommended: Laravel Herd (Mac/Windows)

This project was developed using Laravel Herd for local PHP and web server management.

Install Laravel Herd here:

https://herd.laravel.com

After installation:

- Ensure **PHP 8.4** is installed
- Set PHP **8.4 as the active/default version** in Herd

---

## Project Installation

### 1. Extract the Project ZIP

Unzip the project to your preferred local directory.

Example:

```bash
Desktop/larager
```

---

### 2. Link project to Herd

Open Laravel Herd on your desktop, navigate to Sites and click Add. Select Link existing project and select the directory containing your unzipped project. Select PHP 8.4 and then click Next.

### 3. Open a Terminal in the Project Directory

Navigate into the unzipped project folder:

```bash
cd path/to/larager
```

---

### 4. Install PHP Dependencies

Install Laravel dependencies using Composer:

```bash
composer install
```

---

### 5. Configure Environment Variables

Create your local environment file:

```bash
cp .env.example .env
```

Open the `.env` file and configure your database connection.

### Option A — MySQL (Recommended)

Update the following values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Ensure the database already exists before continuing.

---

### Option B — SQLite (Simpler Setup)

If preferred, you may use SQLite instead of MySQL.

Update `.env`:

```env
DB_CONNECTION=sqlite
```

Then create the SQLite database file:

```bash
touch database/database.sqlite
```

Remove or ignore the MySQL-related `DB_*` variables.

---

### 6. Generate the Application Key

Run:

```bash
php artisan key:generate
```

---

### 7. Run Database Migrations

Create the database tables:

```bash
php artisan migrate
```

Seed data:

```bash
php artisan db:seed
```

---

### 8. Install Frontend Dependencies

Install Node packages:

```bash
npm install
```

---

### 9. Start the Development Server

Run the Vite frontend server:

```bash
npm run dev
```


---

## Running the Application

Once dependencies and configurations are setup, simply open your local site URL (found in Herd)

