# Municipal Infraction Management API – CI Setup Guide

[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15+-blue.svg)](https://postgresql.org)
[![CI/CD](https://img.shields.io/badge/CI/CD-GitHub%20Actions-blue.svg)](https://github.com/features/actions)
[![Tests](https://img.shields.io/badge/Tests-PHPUnit-green.svg)](https://phpunit.de)
[![License](https://img.shields.io/badge/License-Proprietary-orange.svg)](LICENSE)

A guide for setting up Continuous Integration (CI) pipeline for the Municipal Infraction Management API using GitHub Actions.

## ✨ CI Pipeline Features

-   🧪 **Automated Testing** - Test suite execution with PostgreSQL
-   🔍 **Code Quality** - PHP CS Fixer and PHPStan analysis
-   🔒 **Security Scanning** - Dependency vulnerability audits
-   🚀 **Build Optimization** - Production-ready artifact generation

## 🛠️ CI Pipeline Jobs

The pipeline includes four main jobs:

1. **Test** - Validates API endpoints and data integrity
2. **Code Quality** - Ensures code standards with PHP CS Fixer and PHPStan
3. **Security** - Scans for vulnerabilities and security issues
4. **Build** - Prepares production-ready artifacts

## 🚀 Quick Setup

### 1. Push Code

```bash
git add .
git commit -m "Add CI pipeline for municipal infraction API"
git push origin main
```

### 2. Environment Setup

Ensure `.env.example` exists:

```bash
cp .env .env.example
```

Update PostgreSQL settings in `.env.example`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=infraction_commune
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🚨 Troubleshooting

### Common Issues

-   **Tests fail in CI but pass locally**: Check PostgreSQL credentials and Laravel dependencies
-   **PostgreSQL connection issues**: Ensure DB exists and credentials match `.env`
-   **Build failures**: Verify PHP version and Laravel dependencies

### Debug Commands

```bash
# Run tests locally with same config as CI
php artisan test --env=testing

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📄 License

This project is proprietary software developed for GM-SOFT. All rights reserved.

---

**Built with ❤️ for efficient municipal data management**
