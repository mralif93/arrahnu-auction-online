# Development Guide - ArRahnu Auction Online

## Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- PostgreSQL 12 or higher
- Node.js 16 or higher
- Git

### Initial Setup
```bash
# Clone the repository
git clone <repository-url>
cd arrahnu-auction-online

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Set DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Build frontend assets
npm run build
```

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev

# Start queue worker (in another terminal)
php artisan queue:work

# Start log viewer (in another terminal)
php artisan pail
```

## Project Structure

### Key Directories
```
app/
├── Console/Commands/          # Artisan commands
├── Http/
│   ├── Controllers/          # Web and API controllers
│   ├── Middleware/           # Custom middleware
│   ├── Requests/             # Form request validation
│   └── Resources/            # API resource transformers
├── Models/                   # Eloquent models
├── Services/                 # Business logic services
├── Notifications/            # Email/SMS notifications
├── Providers/                # Service providers
└── Traits/                   # Reusable traits

database/
├── migrations/               # Database schema changes
├── seeders/                  # Sample data population
└── factories/                # Model data generation

resources/
├── views/                    # Blade templates
├── css/                      # Stylesheets
└── js/                       # JavaScript files

routes/
├── web.php                   # Web routes
├── api.php                   # API routes
└── console.php               # Console routes

tests/
├── Feature/                  # Feature tests
└── Unit/                     # Unit tests
```

## Development Workflow

### 1. Feature Development
```bash
# Create a new feature branch
git checkout -b feature/new-feature

# Make your changes
# Add tests for new functionality
php artisan test

# Commit your changes
git add .
git commit -m "Add new feature: description"

# Push to remote
git push origin feature/new-feature

# Create pull request
```

### 2. Database Changes
```bash
# Create a new migration
php artisan make:migration create_new_table

# Edit the migration file
# Run the migration
php artisan migrate

# If you need to rollback
php artisan migrate:rollback

# Create a seeder for sample data
php artisan make:seeder NewTableSeeder

# Run seeders
php artisan db:seed --class=NewTableSeeder
```

### 3. Model Development
```bash
# Create a new model
php artisan make:model NewModel

# Create model with migration
php artisan make:model NewModel -m

# Create model with factory and seeder
php artisan make:model NewModel -mf

# Create model with all components
php artisan make:model NewModel -mfs
```

### 4. Controller Development
```bash
# Create a new controller
php artisan make:controller NewController

# Create API controller
php artisan make:controller Api/NewController

# Create resource controller
php artisan make:controller NewController --resource

# Create API resource controller
php artisan make:controller Api/NewController --api
```

## Coding Standards

### PHP Standards
- Follow PSR-12 coding standards
- Use strict typing where possible
- Use meaningful variable and method names
- Add PHPDoc comments for public methods

### Laravel Conventions
- Use Laravel naming conventions
- Follow Laravel directory structure
- Use Laravel facades appropriately
- Implement proper error handling

### Example Code Structure
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Example;
use App\Http\Requests\ExampleRequest;
use App\Http\Resources\ExampleResource;
use Illuminate\Http\JsonResponse;

class ExampleController extends Controller
{
    /**
     * Display a listing of examples.
     */
    public function index(): JsonResponse
    {
        $examples = Example::paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => ExampleResource::collection($examples),
            'pagination' => [
                'current_page' => $examples->currentPage(),
                'last_page' => $examples->lastPage(),
                'per_page' => $examples->perPage(),
                'total' => $examples->total(),
            ]
        ]);
    }

    /**
     * Store a newly created example.
     */
    public function store(ExampleRequest $request): JsonResponse
    {
        try {
            $example = Example::create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Example created successfully',
                'data' => new ExampleResource($example)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create example',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

## Testing Guidelines

### Test Structure
```php
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Example;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_examples()
    {
        Example::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/api/examples');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination'
            ]);
    }

    /** @test */
    public function it_can_create_example()
    {
        $data = [
            'name' => 'Test Example',
            'description' => 'Test Description'
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/examples', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Example created successfully'
            ]);

        $this->assertDatabaseHas('examples', $data);
    }
}
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel

# Run tests with specific filter
php artisan test --filter test_method_name
```

## API Development

### API Response Format
All API responses should follow this format:
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... },
    "pagination": { ... },
    "errors": null
}
```

### Error Handling
```php
// Validation errors
return response()->json([
    'success' => false,
    'message' => 'Validation failed',
    'errors' => $validator->errors()
], 422);

// Business logic errors
return response()->json([
    'success' => false,
    'message' => 'Business rule violation',
    'error' => 'Specific error message'
], 400);

// System errors
return response()->json([
    'success' => false,
    'message' => 'Internal server error',
    'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
], 500);
```

### API Authentication
```php
// Protect routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
});

// Check user permissions
if (!$user->canApprove($model)) {
    return response()->json([
        'success' => false,
        'message' => 'Insufficient permissions'
    ], 403);
}
```

## Database Development

### Migration Best Practices
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->uuid('created_by_user_id')->nullable();
            $table->timestampsTz();

            // Foreign keys
            $table->foreign('created_by_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Indexes
            $table->index('status');
            $table->index('created_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examples');
    }
};
```

### Model Relationships
```php
// One-to-Many
public function examples(): HasMany
{
    return $this->hasMany(Example::class);
}

// Many-to-One
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

// One-to-One
public function profile(): HasOne
{
    return $this->hasOne(Profile::class);
}

// Many-to-Many
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class);
}
```

## Frontend Development

### Blade Templates
```php
{{-- Extend layout --}}
@extends('layouts.app')

{{-- Define content section --}}
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">{{ $title }}</h1>
        
        @if($examples->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($examples as $example)
                    @include('examples.partials.card', ['example' => $example])
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No examples found.</p>
        @endif
    </div>
@endsection
```

### Alpine.js Components
```html
<div x-data="exampleComponent()" class="p-4">
    <button @click="toggleForm" class="btn btn-primary">
        <span x-text="showForm ? 'Cancel' : 'Add New'"></span>
    </button>
    
    <div x-show="showForm" x-transition class="mt-4">
        <form @submit.prevent="submitForm">
            <input x-model="form.name" type="text" placeholder="Name" required>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>

<script>
function exampleComponent() {
    return {
        showForm: false,
        form: {
            name: ''
        },
        toggleForm() {
            this.showForm = !this.showForm;
        },
        async submitForm() {
            // Form submission logic
        }
    }
}
</script>
```

## Debugging and Troubleshooting

### Common Issues

#### 1. Database Connection Issues
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();

# Clear config cache
php artisan config:clear
```

#### 2. Route Issues
```bash
# List all routes
php artisan route:list

# Clear route cache
php artisan route:clear
```

#### 3. View Issues
```bash
# Clear view cache
php artisan view:clear

# Check view compilation
php artisan view:cache
```

#### 4. Cache Issues
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Debug Tools
```bash
# Laravel Debugbar (development)
composer require barryvdh/laravel-debugbar --dev

# Laravel Telescope (development)
composer require laravel/telescope --dev

# Log viewer
php artisan pail
```

## Performance Optimization

### Database Optimization
```php
// Use eager loading to avoid N+1 queries
$users = User::with(['addresses', 'bids'])->get();

// Use database indexes
// Add indexes to frequently queried columns

// Use database transactions for multiple operations
DB::transaction(function () {
    // Multiple database operations
});
```

### Caching Strategies
```php
// Cache expensive operations
$result = Cache::remember('key', 3600, function () {
    return ExpensiveOperation::execute();
});

// Cache database queries
$users = Cache::remember('users', 3600, function () {
    return User::all();
});
```

### Asset Optimization
```bash
# Build production assets
npm run build

# Optimize images
# Use WebP format where possible
# Implement lazy loading
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database
- [ ] Set up SSL certificates
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up queue workers
- [ ] Configure logging
- [ ] Set up monitoring

### Deployment Commands
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Clear caches if needed
php artisan optimize:clear
```

## Contributing Guidelines

### Code Review Process
1. Create feature branch
2. Implement changes with tests
3. Ensure code follows standards
4. Submit pull request
5. Address review feedback
6. Merge after approval

### Commit Message Format
```
type(scope): description

Examples:
feat(auth): add two-factor authentication
fix(api): resolve user profile endpoint error
docs(readme): update installation instructions
style(views): improve button styling consistency
refactor(models): simplify user role checking
test(auth): add login validation tests
```

### Pull Request Template
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] Added new tests for new functionality
- [ ] Updated existing tests if needed

## Checklist
- [ ] Code follows project standards
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No breaking changes introduced
```
