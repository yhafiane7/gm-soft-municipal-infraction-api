# API Documentation Setup Guide

## Overview

This guide explains how to set up and use the Swagger/OpenAPI documentation for the INFRACTION-COMMUNE-BACKEND project.

## Prerequisites

-   Laravel 9.x
-   PHP 8.0+
-   Composer

## Installation Steps

### 1. Install L5-Swagger Package

```bash
composer require "darkaonline/l5-swagger"
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### 3. Generate Documentation

```bash
php artisan l5-swagger:generate
```

## Accessing the Documentation

### Swagger UI

-   **URL**: `http://your-domain/api/documentation`
-   **Description**: Interactive API documentation interface

### JSON Format

-   **URL**: `http://your-domain/docs/api-docs.json`
-   **Description**: Raw OpenAPI specification in JSON format

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Api/
│   │   └── BaseApiController.php    # Base controller with common methods
│   ├── UserController.php           # User management with Swagger docs
│   ├── InfractionController.php    # Infraction management
│   ├── DecisionController.php      # Decision management
│   ├── CommuneController.php       # Commune management
│   ├── AgentController.php         # Agent management
│   ├── CategorieController.php     # Category management
│   └── ViolantController.php       # Violant management
├── Swagger/
│   └── Schemas.php                 # OpenAPI schema definitions
└── Models/                         # Eloquent models
```

## Adding Documentation to Controllers

### 1. Basic Method Documentation

```php
/**
 * @OA\Get(
 *     path="/api/resource",
 *     operationId="getResource",
 *     tags={"Resource"},
 *     summary="Get resource",
 *     description="Retrieve a resource",
 *     @OA\Response(
 *         response=200,
 *         description="Success"
 *     )
 * )
 */
public function index()
{
    // Your code here
}
```

### 2. Request Body Documentation

```php
/**
 * @OA\Post(
 *     path="/api/resource",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ResourceCreate")
 *     )
 * )
 */
```

### 3. Response Documentation

```php
/**
 * @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(ref="#/components/schemas/Resource")
 * )
 */
```

## Available Schemas

### User Management

-   `User` - Complete user model
-   `UserCreate` - User creation request

### Infraction Management

-   `Infraction` - Complete infraction model
-   `InfractionCreate` - Infraction creation request

### Commune Management

-   `Commune` - Complete commune model
-   `CommuneCreate` - Commune creation request

### Decision Management

-   `Decision` - Complete decision model
-   `DecisionCreate` - Decision creation request

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

### Customization

Edit `config/l5-swagger.php` to customize:

-   API title and description
-   Contact information
-   License details
-   UI appearance

## Best Practices

### 1. Consistent Documentation

-   Use consistent tags for related endpoints
-   Provide clear descriptions for each operation
-   Include examples in responses

### 2. Error Handling

-   Document all possible error responses
-   Use appropriate HTTP status codes
-   Provide meaningful error messages

### 3. Schema Reusability

-   Create reusable schemas for common data structures
-   Use `@OA\Property` for detailed field descriptions
-   Include validation rules in descriptions

## Testing Documentation

### 1. Generate Documentation

```bash
php artisan l5-swagger:generate
```

### 2. Test Endpoints

-   Use the Swagger UI to test API endpoints
-   Verify request/response schemas
-   Check error handling

### 3. Validate OpenAPI Spec

-   Use online OpenAPI validators
-   Ensure compliance with OpenAPI 3.0 specification

## Troubleshooting

### Common Issues

#### 1. Documentation Not Generating

-   Check if `storage/api-docs` directory exists
-   Verify L5-Swagger configuration
-   Check for syntax errors in annotations

#### 2. Routes Not Accessible

-   Ensure routes are properly registered
-   Check middleware configuration
-   Verify URL paths in annotations

#### 3. Schema Not Displaying

-   Check schema class names
-   Verify namespace declarations
-   Ensure proper OpenAPI syntax

### Debug Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Regenerate documentation
php artisan l5-swagger:generate

# Check routes
php artisan route:list
```

## Next Steps

### 1. Complete Controller Documentation

-   Add Swagger annotations to remaining controllers
-   Document all API endpoints
-   Include comprehensive error responses

### 2. Add Authentication

-   Document authentication requirements
-   Include security schemes
-   Add authorization headers

### 3. Enhanced Schemas

-   Create more detailed data models
-   Add validation examples
-   Include business logic descriptions

### 4. Testing

-   Write tests for documented endpoints
-   Validate API responses
-   Ensure documentation accuracy

## Resources

-   [OpenAPI Specification](https://swagger.io/specification/)
-   [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)
-   [Laravel Documentation](https://laravel.com/docs)
-   [Swagger UI](https://swagger.io/tools/swagger-ui/)

## Support

For issues or questions:

-   Check the troubleshooting section
-   Review OpenAPI specification
-   Consult Laravel documentation
-   Create an issue in the project repository
