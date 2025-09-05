# 🚀 Deployment Guide

## 📋 Overview

This guide covers deploying the Municipal Infraction Management API to Render using Docker.

## 🐳 Docker Setup

### Files Created:

-   `Dockerfile` - Main container configuration
-   `docker-compose.yml` - Local development setup
-   `render.yaml` - Render deployment configuration
-   `.dockerignore` - Docker build optimization

## 🚀 Render Deployment

### Option 1: Using Render Dashboard (Recommended)

1. **Connect Repository**

    - Go to [Render Dashboard](https://dashboard.render.com)
    - Click "New +" → "Web Service"
    - Connect your GitHub repository

2. **Configure Service**

    - **Name**: `infraction-commune-api`
    - **Environment**: `Docker`
    - **Dockerfile Path**: `./Dockerfile`
    - **Branch**: `main`

3. **Environment Variables**

    ```
    APP_ENV=production
    APP_DEBUG=false
    DB_CONNECTION=pgsql
    DB_HOST=<from-database-service>
    DB_PORT=<from-database-service>
    DB_DATABASE=<from-database-service>
    DB_USERNAME=<from-database-service>
    DB_PASSWORD=<from-database-service>
    ```

4. **Create Database**
    - Go to "New +" → "PostgreSQL"
    - **Name**: `infraction-commune-db`
    - **Database**: `infraction_commune`
    - **User**: `postgres`
    - **Password**: `password`

### Option 2: Using render.yaml

1. **Push to Repository**

    ```bash
    git add .
    git commit -m "Add Docker deployment configuration"
    git push origin main
    ```

2. **Deploy via Render CLI**

    ```bash
    # Install Render CLI
    npm install -g @render/cli

    # Login to Render
    render login

    # Deploy
    render deploy
    ```

## 🏗️ Local Development

### Using Docker Compose

```bash
# Start services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# View logs
docker-compose logs -f app
```

### Access Points

-   **API**: http://localhost:8000
-   **Database**: localhost:5432
-   **Swagger Docs**: http://localhost:8000/api/documentation

## 🔧 Configuration

### Environment Variables

| Variable        | Description             | Default              |
| --------------- | ----------------------- | -------------------- |
| `APP_ENV`       | Application environment | `production`         |
| `APP_DEBUG`     | Debug mode              | `false`              |
| `DB_CONNECTION` | Database driver         | `pgsql`              |
| `DB_HOST`       | Database host           | `postgres`           |
| `DB_PORT`       | Database port           | `5432`               |
| `DB_DATABASE`   | Database name           | `infraction_commune` |
| `DB_USERNAME`   | Database user           | `postgres`           |
| `DB_PASSWORD`   | Database password       | `password`           |

### Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Generate API documentation
php artisan l5-swagger:generate
```

## 📊 Monitoring

### Health Checks

-   **Endpoint**: `GET /`
-   **Interval**: 30 seconds
-   **Timeout**: 3 seconds

### Logs

-   **Application**: `storage/logs/laravel.log`
-   **Apache**: `/var/log/apache2/error.log`

## 🚨 Troubleshooting

### Common Issues

1. **Database Connection Failed**

    - Check database service is running
    - Verify environment variables
    - Check network connectivity

2. **Permission Denied**

    - Ensure storage directories are writable
    - Check file permissions

3. **Composer Dependencies**
    - Run `composer install` in container
    - Check composer.lock file

### Debug Commands

```bash
# Check container logs
docker-compose logs app

# Access container shell
docker-compose exec app bash

# Check PHP configuration
docker-compose exec app php -m

# Test database connection
docker-compose exec app php artisan tinker
```

## 🔄 CI/CD Integration

### GitHub Actions

The existing CI pipeline will:

-   Run tests with PostgreSQL
-   Generate code coverage
-   Build Docker image (if configured)
-   Deploy to Render (if configured)

### Manual Deployment

```bash
# Build and push to registry
docker build -t your-registry/infraction-commune-api .
docker push your-registry/infraction-commune-api

# Deploy to Render
render deploy
```

## 📈 Performance Optimization

### Production Optimizations

-   **Composer**: `--no-dev --optimize-autoloader`
-   **Laravel**: `config:cache`, `route:cache`, `view:cache`
-   **Apache**: Mod rewrite enabled
-   **PHP**: OPcache enabled (if available)

### Resource Limits

-   **Memory**: 512MB (starter plan)
-   **CPU**: 0.5 vCPU (starter plan)
-   **Storage**: 1GB (starter plan)

---

**Ready for deployment! 🚀**
