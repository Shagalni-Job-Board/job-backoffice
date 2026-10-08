# Shagalni — Backoffice

This repository contains the administration and company-management application for the Shagalni platform. It is the Laravel backend dashboard used by administrators and company owners to manage users, companies, job categories, vacancies, and incoming applications.

## Purpose

The main purpose of this project is to support the operational side of the platform. Admins manage the platform, while company owners manage their own vacancies and review applicants submitted from the public job-app.

## Main technologies

- PHP 8.2
- Laravel 12
- Laravel Breeze
- Blade + Tailwind CSS + Vite
- MariaDB / MySQL
- Laravel Echo and Pusher support
- AWS S3-compatible storage
- Pest for tests
- Composer package: job/shared

## Main features

- Role-based access control for admin and company-owner users
- Company management
- Vacancy management with restore support
- Application review and status updates
- User and category management
- Dashboard metrics for different user roles
- Notification handling for application activity

## Project structure

- app/Http/Controllers: admin and company-owner flows
- app/Http/Middleware: role-based authorization
- app/Repositories: dashboard repository logic
- app/Services: dashboard processing
- app/Providers: repository binding and service registration
- resources/views: admin and company dashboard views
- routes/web.php: protected route groups by role
- database/migrations: application schema for the platform domain

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/YoussefSayed-cs/job-backoffice.git
   cd job-backoffice
   ```

2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

## Environment configuration

Update .env with the correct values for your local environment. The important variables in .env.example include:

- APP_NAME, APP_ENV, APP_URL, APP_DEBUG
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- SESSION_DRIVER and CACHE_STORE
- QUEUE_CONNECTION=database
- MAIL configuration
- AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, AWS_BUCKET
- VITE_APP_NAME

This project shares a database with job-app and uses the common models from job-shared. The database values should match the public app setup so both applications read and write the same shared data.

## Database setup and migrations

Run the application migrations from this repo:

```bash
php artisan migrate
```

This project creates the platform domain tables used by the shared model layer. The job-seeker app connects to the same database and relies on that shared schema for its vacancy and application logic.

## Running locally

Use the project script:

```bash
composer dev
```

This starts the local Laravel app, queue worker, log viewer, and Vite frontend in parallel.

You can also run the services manually:

```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

## Requirements

- PHP >= 8.2
- Composer
- Node.js and npm
- MariaDB or MySQL
- Shared database with job-app
- AWS S3-compatible storage for uploaded files
- job-shared package available through Composer

## Related repositories

- job-app: public job-seeker application
- job-shared: shared models and notifications package
