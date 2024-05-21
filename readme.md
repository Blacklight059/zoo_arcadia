# Zoo Arcadia

This repository contains the code for Zoo Arcadia, a Symfony-based web application.

## Requirements

- PHP 8.0 or higher
- Composer
- Symfony CLI
- Node.js and npm
- MongoDB
- MySQL (or another supported database)

## Installation

### Step 1: Clone the repository
```sh
git clone https://github.com/Blacklight059/zoo_arcadia.git
cd zoo_arcadia

### Step 2: Install PHP dependencies
sh
Copy the code
composer install

### Step 3: Set up environment variables
Create a .env.local file and configure your database connection and other environment variables:

dotenv
Copy the code
DATABASE_URL="mysql://username:password@127.0.0.1:3306/dbname"
MONGODB_URL="mongodb://localhost:27017"

### Step 4: Set up the database
Create the database and run migrations:

sh
Copier le code
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

### Step 5: Install JavaScript dependencies
sh
Copier le code
npm install
npm run dev

### Step 6: Load fixtures (optional)
To load initial data for development:

sh
Copy the code
php bin/console doctrine:fixtures:load
Running the Application
Start the Symfony server
sh
Copy the code
symfony serve
Access the application
Open your browser and go to http://127.0.0.1:8000.

Additional Commands
Clear the cache
sh
Copy the code
php bin/console cache:clear
Run tests
sh
Copy the code
php bin/phpunit
Common Issues
DebugBundle Error
If you encounter a DebugBundle error, ensure that Symfony\Bundle\DebugBundle\DebugBundle is only registered in the dev and test environments:

php
Copy the code
// config/bundles.php

return [
    // ...
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    // ...
];
MongoDB Connection Issues
Ensure MongoDB is running and the connection string in .env.local is correct.

sh
Copier le code
mongod
Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

