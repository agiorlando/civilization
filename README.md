# InvoCivilizations

A Laravel 11 + Vue 3 CRUD application built with Docker (using Laravel Sail), Vite for asset bundling, and Vitest for Vue unit testing.

## Table of Contents

- Requirements
- Installation
- Database Setup & Data Ingestion
- Development
- Testing

## Requirements

- Docker and Docker Compose (see https://docs.docker.com/get-docker/)
- PHP 8.1+ (if not using Sail exclusively)
- Node.js & npm (if running npm commands outside Sail)

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/agiorlando/invocivilizations.git
   cd invocivilizations
2. **Install PHP Dependencies via Composer:**
  - Using Sail:
    ```bash
    ./vendor/bin/sail composer install
  - Or, if running locally:
    ```bash
    composer install
3. **Install JavaScript Dependencies:**
  - Using Sail:
    ```bash
    ./vendor/bin/sail npm install
  - Or simply:
    ```bash
    npm install
4. **Copy the Environment File and Generate an Application Key:**

  ```bash
  cp .env.example .env
  ./vendor/bin/sail artisan key:generate
  ```
5. **Set Up the Database:**

  Ensure your `.env` file has the correct database credentials for Sail (by default, Sail sets these up for you). Then run the migrations:

    ./vendor/bin/sail artisan migrate

## Database Setup & Data Ingestion

  After running migrations, populate your database with external data using the provided Artisan command:

    ./vendor/bin/sail artisan ingest-api

  This command fetches data from an external API and populates the database for InvoCivilizations.

## Development

### Starting Laravel Sail

  Start your Docker-based development environment in detached mode with:

    ./vendor/bin/sail up -d

  This starts the containers (MySQL, Redis, etc.) required for your application.

### Running Vite for Hot Reloading

  For a hot-reloading development environment, run:

    ./vendor/bin/sail npm run dev

  This launches Vite, which compiles your assets and watches for changes.

## Testing

### Running Laravel (PHPUnit) Tests

  Run your Laravel tests inside Sail with:

    ./vendor/bin/sail artisan test

  Or run PHPUnit directly with:

    ./vendor/bin/sail phpunit

### Running Vue (Vitest) Tests

  This project uses Vitest for unit testing Vue components. Ensure you have a `vitest.config.js` file at the project root. A sample configuration is provided below:

    import { defineConfig } from 'vitest/config'
      import vue from '@vitejs/plugin-vue'

      export default defineConfig({
        plugins: [vue()],
        resolve: {
          alias: {
            '@': '/resources/js'  // Adjust this alias if your source directory differs
          }
        },
        test: {
          environment: 'jsdom',
          globals: true,
        },
      })

  To run your Vue tests, use:
  
    ./vendor/bin/sail npm run test:unit
