# Docker Setup for Laravel Application

This project uses Docker to run the Laravel application with MySQL database.

## Prerequisites

- Docker
- Docker Compose

## Quick Start

1. **Clone the repository and navigate to the project directory**
   ```bash
   cd arrahnu-auction-online
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Update database configuration in .env file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=arrahnu_auction
   DB_USERNAME=arrahnu_user
   DB_PASSWORD=secret
   ```

4. **Build and start the containers**
   ```bash
   docker-compose up -d --build
   ```

5. **Install PHP dependencies**
   ```bash
   docker-compose exec app composer install
   ```

6. **Generate application key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. **Run database migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

8. **Seed the database (optional)**
   ```bash
   docker-compose exec app php artisan db:seed
   ```

## Access the Application

- **Web Application**: http://localhost:8000
- **Database**: localhost:3306
  - Username: arrahnu_user
  - Password: secret
  - Database: arrahnu_auction

## Useful Commands

**Start containers:**
```bash
docker-compose up -d
```

**Stop containers:**
```bash
docker-compose down
```

**View logs:**
```bash
docker-compose logs -f
```

**Access app container:**
```bash
docker-compose exec app bash
```

**Access database:**
```bash
docker-compose exec db mysql -u arrahnu_user -p arrahnu_auction
```

**Run artisan commands:**
```bash
docker-compose exec app php artisan [command]
```

**Run tests:**
```bash
docker-compose exec app php artisan test
```

## Container Details

- **app**: Laravel application with PHP 8.2-FPM
- **webserver**: Nginx web server
- **db**: MySQL 8.0 database

## Troubleshooting

### Database Connection Issues

If you encounter database connection errors like "Access denied for user 'arrahnu_user'", try:

1. **Complete reset (recommended):**
   ```bash
   ./docker-reset.sh
   ```

2. **Manual troubleshooting:**
   ```bash
   ./docker-db-troubleshoot.sh
   ```

3. **Manual reset:**
   ```bash
   docker-compose down
   docker volume rm arrahnu-auction-online_dbdata
   docker-compose up -d --build
   ```

### Permission Issues

If you encounter permission issues:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache
```

### General Issues

To rebuild containers:
```bash
docker-compose down
docker-compose up -d --build
```
