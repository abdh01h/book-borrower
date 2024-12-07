# About this project 

A Simple Book Borrowing System

## Requirements  

This project uses Laravel 11.31, therefore the minimum requirements to run the project as below:

- [Composer](https://getcomposer.org/) v2.8.
- [PHP](https://www.php.net/) v8.3 or higher.
- [MySQL](https://www.mysql.com/) v8 or higher.

## How To Setup?

1. Clone the repository:

```bash
git clone https://github.com/abdh01h/book-borrower.git
```
2. Navigate to the project directory:

```bash
cd your-laravel-project
```
 
3. Install project dependencies using Composer:

```bash
composer install
```
 
4. Create a copy of the .env.example file and save it as .env:

```bash
cp .env.example .env
```

5. Create a new database with your_database_name, your_database_username and your_database_password.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
 
6. Run database migrations to create the database tables:
 
```bash
php artisan migrate --seed
```

7. Lastly, run the project:
 
```bash
php artisan serve --port 4000
```
 
 The End
