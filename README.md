# Municipal Infraction Management API

[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-green.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-Proprietary-orange.svg)](LICENSE)

A comprehensive REST API for municipal infraction management built with Laravel. Developed for GM-SOFT to provide efficient data management for local government operations.

## 🏛️ About

**Municipal Infraction Management API** is a robust backend system designed for local governments to efficiently track, manage, and process municipal violations. The system provides complete CRUD operations, geolocation tracking, and data validation for all municipal entities.

## ✨ Features

-   🔐 **Authentication System** - Laravel Sanctum for secure API access
-   📍 **Geolocation Services** - Latitude/longitude tracking for infractions and communes
-   📊 **Data Management** - Complete CRUD operations for 7 core entities
-   🔍 **Advanced Validation** - Comprehensive input validation with custom rules
-   🗄️ **Database Integrity** - Foreign key relationships with cascade delete protection
-   📱 **RESTful API** - Standard HTTP methods with JSON responses

## 🛠️ Tech Stack

-   **Backend Framework**: Laravel 9.x
-   **Programming Language**: PHP 8.0+
-   **Database**: MySQL 5.7+ / PostgreSQL 10+
-   **Authentication**: Laravel Sanctum
-   **API**: RESTful endpoints

## 📊 Data Entities

-   **Infractions** - Main violation records with geolocation coordinates
-   **Agents** - Law enforcement personnel with unique identification
-   **Violators** - Individuals who committed violations
-   **Communes** - Municipal areas with administrative boundaries
-   **Categories** - Infraction classification system with severity degrees
-   **Decisions** - Outcomes and rulings for infractions
-   **Users** - System user management and authentication

## 📋 API Endpoints

| Method   | Endpoint               | Description             |
| -------- | ---------------------- | ----------------------- |
| `GET`    | `/api/infraction`      | List all infractions    |
| `POST`   | `/api/infraction`      | Create new infraction   |
| `GET`    | `/api/infraction/{id}` | Get specific infraction |
| `PUT`    | `/api/infraction/{id}` | Update infraction       |
| `DELETE` | `/api/infraction/{id}` | Delete infraction       |

Similar endpoints available for: `/api/agent`, `/api/commune`, `/api/categorie`, `/api/violant`, `/api/decision`, `/api/user`

## 🚀 Quick Start

### 1. Clone and Install

```bash
git clone https://github.com/yhafiane7/gm-soft-municipal-infraction-api.git
cd gm-soft-municipal-infraction-api
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
# Configure your database credentials in .env
php artisan migrate
```

### 3. Start Server

```bash
php artisan serve
```

Visit `http://localhost:8000/api/test` to test the API.

## 📝 Usage Examples

### Creating an Infraction

```bash
curl -X POST http://localhost:8000/api/infraction \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Traffic Violation",
    "date": "2024-01-15",
    "adresse": "123 Main Street",
    "commune_id": 1,
    "violant_id": 1,
    "agent_id": 1,
    "categorie_id": 1,
    "latitude": 34.0522,
    "longitude": -118.2437
  }'
```

### Creating an Agent

```bash
curl -X POST http://localhost:8000/api/agent \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Smith",
    "prenom": "John",
    "tel": "1234567890",
    "cin": "AB123456789"
  }'
```

## 🔒 Data Validation

### Key Validation Rules

-   **Infractions**: Required fields include name, date, address, coordinates, and all foreign key references
-   **Agents**: Required name, phone (unique), and national ID (unique)
-   **Communes**: Required administrative data and coordinates
-   **All entities**: Foreign key existence validation and data type checking

## 🏢 Project Context

This API was developed for **GM-SOFT** to provide municipal governments with a comprehensive solution for managing infractions and violations. The system enables efficient tracking, geolocation-based management, and data-driven decision making for local governments.

## 🧪 Testing

```bash
php artisan test
```

## 🚀 Deployment

### Production Build

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
```

### Server Requirements

-   PHP 8.0+
-   MySQL 5.7+ or PostgreSQL 10+
-   Composer
-   Web server (Apache/Nginx)

## 📄 License

This project is proprietary software developed for GM-SOFT. All rights reserved.

## 🙏 Acknowledgments

-   Laravel team for the amazing framework
-   GM-SOFT for the project opportunity

---

**Built with ❤️ for efficient municipal data management**
