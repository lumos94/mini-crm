# Mini CRM Project

## Requirements
To run this project, ensure your environment meets the following requirements:
- **PHP**: `>=7.1.3`
- **Laravel**: `5.8`
- **Composer**: `^2.2.24`

## Project Setup Instructions

### Step 1: Clone the Repository
First, clone the repository from GitHub or another source:
```bash
git clone <repository-url>
```
Navigate into the project directory:

```bash
cd mini-crm
```
### Step 2: Install Dependencies

After cloning the project, install the necessary dependencies using Composer:

```bash
composer install
```
### Step 3: Set Up the .env File

Create a copy of the .env.example file and rename it to .env:

```bash
cp .env.example .env
```
Generate a new application key:

```bash
php artisan key:generate
```
### Step 4: Set Up the Database
Make sure to configure your database credentials in the .env file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
Run the following command to create the database tables:

```bash
php artisan migrate
```
### Step 5: Seed the Database
To populate the database with the default admin user and some dummy data (if applicable), run the seeder:

```bash
php artisan db:seed
```
### Step 6: Set Up Storage Link
To link the storage folder, run the following command:

```bash
php artisan storage:link
```
### Step 7: Before Running the Project Locally Compile the frontend
Before serving the application locally run the following commands:

```bash
npm install
npm run dev
```

### Step 8: Running the Project Locally
After setting up the project, you can serve the application locally using:

```bash
php artisan serve
```
This will start a local development server at http://localhost:8000.

## Building for Production

### Step 1: Install Node.js and NPM (if not already installed)
Make sure you have Node.js and npm installed in your environment. Install the required dependencies:

```bash
npm install
```
### Step 2: Build Frontend Assets
To compile the frontend assets for production, run:

```bash
npm run production
```
This command will minify your JavaScript and CSS files for production use.

### Step 3: Configure Caching and Optimizations
For optimal performance, run the following commands to cache the configuration and routes:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
