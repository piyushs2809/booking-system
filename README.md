# BOOKING SYSTEM

## Made By PIYUSH SOLANKI

### Introduction

- This project is built using Laravel 12 and requires PHP 8.2. It includes:
- User authentication
- Email verification
- Queue-based email processing
- Restriction on login for unverified users
- Prevention of back navigation after logout for enhanced security
- High-performance Booking System with advanced conflict detection

### Prerequisites

| **Plugin** | **Version**|
| ------ | ------ |
| PHP | ^8.2.0 |
| Laravel | ^12.0 |
| MySQL | ~8.0 |


### Installation

##### Clone the repository
```
git clone https://github.com/piyushs2809/booking-system.git

```

##### create .env file 

```sh
cp .env.example .env
```
> ##### 1. Setting up your database details in .env



```sh
APP_URL=http://localhost/booking-system/public/
DB_DATABASE=DATABASE_NAME
DB_USERNAME=DATABASE_USER
DB_PASSWORD=DATABASE_PASSWORD

SANCTUM_EXPIRATION=30
SANCTUM_REFRESH_EXPIRATION=240

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=MAIL_Id
MAIL_PASSWORD=PASSWORD
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="MAIL_Id"
MAIL_FROM_NAME="${APP_NAME}"


QUEUE_CONNECTION=database

```
> ##### 2. Setup The Project

```sh
composer install
```

<br />

### Generate Application Key
```
php artisan key:generate
```

### Generate Application Key
```
php artisan migrate
```

### Queue Configuration
```
php artisan queue:work
```

### Notes
- Ensure mail credentials are correctly set in .env.