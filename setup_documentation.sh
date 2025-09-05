#!/bin/bash

echo "🚀 Setting up API Documentation for INFRACTION-COMMUNE-BACKEND"
echo "================================================================"

# Check if Laravel is available
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi

echo "✅ Prerequisites check passed"

# Publish configuration (only if not already published)
echo "⚙️  Publishing configuration..."
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" --force || true

# Create storage directory if it doesn't exist
echo "📁 Creating storage directory..."
mkdir -p storage/api-docs

# Generate documentation
echo "📚 Generating initial documentation..."
php artisan l5-swagger:generate

# Clear cache (only in non-Docker environments)
if [ -z "$DOCKER_CONTAINER" ]; then
    echo "🧹 Clearing cache..."
    php artisan cache:clear
    php artisan config:clear
fi

echo ""
echo "🎉 Documentation setup completed!"
echo ""
echo "📖 Access your API documentation at:"
echo "   - Swagger UI: http://localhost:8000/api/documentation"
echo "   - JSON Spec: http://localhost:8000/docs/api-docs.json"
echo ""
echo "🔧 Next steps:"
echo "   1. Start your Laravel server: php artisan serve"
echo "   2. Visit the documentation URL"
echo "   3. Add more annotations to your controllers"
echo "   4. Run 'php artisan l5-swagger:generate' after changes"
echo ""
echo "📚 Read DOCUMENTATION_SETUP.md for detailed instructions"
