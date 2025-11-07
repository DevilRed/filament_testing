# Larafillment - Project & Employee Management System

## Project Overview
A modern, responsive project and employee management system built with Laravel and Filament PHP. Features a clean dashboard, CRUD operations, CSV exports, and dark mode support.

## Quick Installation

### Prerequisites
- PHP 8.2+
- Composer 2.5+
- Node.js 18+ (for asset compilation)

### Installation Steps

1. **Clone the repository**
```bash
git clone https://github.com/DevilRed/filament_testing.git
cd filament_testing
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Setup environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Run database migrations**
```bash
php artisan migrate
```

5. **Install and build frontend assets**
```bash
npm install && npm run build
```

6. **Start development server**
```bash
composer run dev
```

## Access Credentials
URL: http://localhost:8000/admin
Username: john_doe@gmail.com
Password: 123456

## Routes
Admin Panel: http://localhost:8000/admin
