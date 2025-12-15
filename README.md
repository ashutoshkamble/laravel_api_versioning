# Laravel API Versioning - Blog Platform

A powerful, production-ready REST API for a comprehensive blog management platform with robust versioning, role-based access control, and comprehensive comment management. Built with Laravel 11 and utilizing best practices for API design, security, and maintainability.

## 🌟 Features

### Core Capabilities
- **Route-Based API Versioning**: Seamless management of V1 and V2 endpoints with clear separation of concerns
- **Blog Post CRUD Operations**: Complete create, read, update, and delete functionality with full authorization support
- **Advanced Search & Pagination**: Efficiently search and paginate through blog posts with customizable pagination
- **Comment Management System**: Nested comment CRUD operations with hierarchical control
- **Resource-Based Responses**: Structured API responses using Laravel Resource classes for consistent data formatting
- **Role-Based Access Control**: Three distinct user roles with granular permission management
- **Form Validation**: Custom request classes with comprehensive validation rules
- **Policy-Based Authorization**: Fine-grained authorization using Laravel Policies
- **Centralized Exception Handling**: Professional error handling with consistent response formats
- **Enum-Based Constants**: Type-safe enums for PostStatus and UserRole

### Technical Highlights
- **Master Controller Pattern**: Centralized `MasterApiController` with shared functionality
- **API Response Trait**: Reusable `ApiResponser` trait for consistent JSON responses
- **Database Seeders**: Automated seeding for quick development and testing
- **Trait-Based Patterns**: Effective use of PHP traits for code reusability
- **Clear Folder Structure**: Version-specific organization for scalability

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Laravel 11.9+
- Node.js (for frontend assets)

### Installation

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd laravel_api_versioning
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_api_versioning
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000/api`

## 🔐 Authentication

The API uses **Laravel Sanctum** for token-based authentication:

1. **Create Personal Access Token**
   ```bash
   php artisan tinker
   >>> $user = User::first();
   >>> $token = $user->createToken('api-token')->plainTextToken;
   >>> echo $token;
   ```

2. **Include Token in Requests**
   ```bash
   curl -H "Authorization: Bearer YOUR_TOKEN" \
        -H "Accept: application/json" \
        http://localhost:8000/api/v1/posts
   ```

## 👥 User Roles & Permissions

The system implements three distinct user roles with specific permissions:

| Role | Permissions | Description |
|------|-------------|-------------|
| **Admin** | Full access to all operations | Complete control over posts, comments, and user management |
| **Editor** | Create, edit own posts & all comments | Can create and manage their own blog posts; manage all comments |
| **Viewer** | Read-only access | Can view posts and comments; cannot create or modify content |

## 📚 API Endpoints

### V1 - Blog Post Management
**Base URL**: `http://localhost:8000/api/v1`

#### Posts Resource
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|----------------|
| GET | `/posts` | List all posts with pagination & search | No |
| POST | `/posts` | Create a new post | Yes (Editor+) |
| GET | `/posts/{id}` | Retrieve a specific post | No |
| PUT/PATCH | `/posts/{id}` | Update a post | Yes (Author/Admin) |
| DELETE | `/posts/{id}` | Delete a post | Yes (Author/Admin) |

**Query Parameters** (GET `/posts`):
- `page`: Page number (default: 1)
- `per_page`: Results per page (default: 15, max: 100)
- `search`: Search term for post title/content
- `status`: Filter by status (published, draft, archived)
- `sort`: Sort field (created_at, updated_at, title)
- `order`: Sort direction (asc, desc)

**Example Request**:
```bash
GET /api/v1/posts?page=1&per_page=10&search=laravel&status=published
Authorization: Bearer YOUR_TOKEN
```

**Example Response**:
```json
{
  "status": "Success",
  "message": "Posts retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Getting Started with Laravel",
      "content": "...",
      "status": "published",
      "author": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2025-12-15T10:30:00Z",
      "updated_at": "2025-12-15T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 25,
    "per_page": 10,
    "last_page": 3
  }
}
```

### V2 - Enhanced Features with Comments
**Base URL**: `http://localhost:8000/api/v2`

#### Posts Resource
Same endpoints as V1 with additional features

#### Comments Resource
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|----------------|
| GET | `/posts/{post}/comments` | List comments for a post | No |
| POST | `/posts/{post}/comments` | Add comment to a post | Yes |
| PATCH | `/comments/{id}` | Update a comment | Yes (Author/Admin) |
| DELETE | `/comments/{id}` | Delete a comment | Yes (Author/Admin) |

**Example Comment Request**:
```bash
POST /api/v2/posts/1/comments
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "content": "Great article, thanks for sharing!"
}
```

**Example Comment Response**:
```json
{
  "status": "Success",
  "message": "Comment created successfully",
  "data": {
    "id": 5,
    "content": "Great article, thanks for sharing!",
    "post_id": 1,
    "author": {
      "id": 2,
      "name": "Jane Smith"
    },
    "created_at": "2025-12-15T11:45:00Z"
  }
}
```

## 📋 Post Status Enum

The API uses enums for type-safe status management:

```php
enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
```

**Allowed Values**: `draft`, `published`, `archived`

## 🛡️ Error Handling

The API implements centralized exception handling with consistent error responses:

**Error Response Format**:
```json
{
  "status": "Error",
  "message": "Resource not found",
  "data": null
}
```

**Common HTTP Status Codes**:
| Code | Meaning | Example |
|------|---------|---------|
| 200 | OK | Successful GET, PUT, PATCH |
| 201 | Created | Successful POST |
| 204 | No Content | Successful DELETE |
| 400 | Bad Request | Invalid input data |
| 401 | Unauthorized | Missing/invalid token |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation error |
| 500 | Internal Server Error | Server error |

**Validation Error Example**:
```json
{
  "status": "Error",
  "message": "Validation failed",
  "data": {
    "title": ["The title field is required"],
    "content": ["The content must be at least 10 characters"]
  }
}
```

## 📁 Project Structure

```
laravel_api_versioning/
├── app/
│   ├── Enums/
│   │   ├── PostStatus.php          # Post status enum
│   │   └── UserRole.php            # User role enum
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── V1/             # V1 controllers
│   │   │   │   └── V2/             # V2 controllers
│   │   │   └── MasterApiController # Base controller with shared logic
│   │   ├── Middleware/             # API middleware
│   │   ├── Requests/               # Form validation classes
│   │   └── Resources/              # API resource classes
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   └── Comment.php
│   ├── Policies/
│   │   └── PostPolicy.php          # Authorization policies
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Traits/
│       └── ApiResponser.php        # Response formatting trait
├── bootstrap/
│   ├── app.php                     # Main application bootstrap
│   └── providers.php               # Service provider bootstrap
├── database/
│   ├── factories/                  # Model factories for testing
│   ├── migrations/                 # Database migrations
│   └── seeders/                    # Database seeders
├── routes/
│   ├── api.php                     # Main API route file
│   ├── api_v1.php                  # V1 specific routes
│   └── api_v2.php                  # V2 specific routes
├── tests/
│   ├── Feature/                    # Feature tests
│   └── Unit/                       # Unit tests
├── composer.json                   # PHP dependencies
├── package.json                    # Node dependencies
└── phpunit.xml                     # PHPUnit configuration
```

## 🔧 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'viewer',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Posts Table
```sql
CREATE TABLE posts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    user_id BIGINT (Foreign Key),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Comments Table
```sql
CREATE TABLE comments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    content TEXT,
    post_id BIGINT (Foreign Key),
    user_id BIGINT (Foreign Key),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🧪 Testing

Run the test suite using PHPUnit:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Api/PostTest.php

# Run with coverage report
php artisan test --coverage
```

## 📚 Key Classes & Traits

### ApiResponser Trait
Centralized response handling for all API endpoints:
```php
// Success response
return $this->successResponse($data, 'Resource created successfully', 201);

// Error response
return $this->errorResponse('Validation failed', 422);
```

### MasterApiController
Base controller extending Laravel's Controller with shared API logic

### Form Requests
Type-safe form validation using custom Request classes:
- `StorePostRequest`
- `UpdatePostRequest`
- `StoreCommentRequest`
- `UpdateCommentRequest`

### Policies
Authorization rules using Laravel Policies:
- `PostPolicy` - Controls post access based on user roles

## 🔄 API Versioning Strategy

The API implements route-based versioning:

- **V1 Endpoint**: `/api/v1/*` - Blog post management
- **V2 Endpoint**: `/api/v2/*` - Post management + comments

This approach allows:
- Gradual migration of clients to newer versions
- Support for legacy clients
- Clear separation of features per version

## 📖 Best Practices Implemented

✅ RESTful endpoint design  
✅ Consistent response formatting  
✅ Comprehensive error handling  
✅ Role-based access control  
✅ Input validation and sanitization  
✅ Type-safe enums for constants  
✅ Trait-based code reusability  
✅ Resource classes for API responses  
✅ Policy-based authorization  
✅ Comprehensive database seeding  

## 🚦 Development Workflow

### Creating New Endpoints

1. Create Controller in appropriate version folder (`app/Http/Controllers/Api/V{n}/`)
2. Extend `MasterApiController` for consistency
3. Create Form Request class for validation
4. Create Resource class for response formatting
5. Create Policy class if authorization is needed
6. Register routes in `routes/api_v{n}.php`

### Adding New User Roles

1. Update `UserRole` enum in `app/Enums/UserRole.php`
2. Update database seeder
3. Add migration to update users table
4. Update policy logic

## 📝 Environment Variables

Key environment variables for API configuration:

```env
# Application
APP_NAME="Blog API Versioning"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api_versioning
DB_USERNAME=root
DB_PASSWORD=

# Sanctum (Authentication)
SANCTUM_STATEFUL_DOMAINS=localhost:8000

# API Pagination
PAGINATION_PER_PAGE=15
```

## 🤝 Contributing

1. Create a feature branch: `git checkout -b feature/your-feature`
2. Make your changes with meaningful commits
3. Write or update tests as needed
4. Run tests to ensure everything passes: `php artisan test`
5. Submit a pull request with a clear description

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For issues, questions, or contributions, please open an issue in the repository or contact the development team.

---

**Last Updated**: December 2025  
**Laravel Version**: 11.9+  
**PHP Version**: 8.2+
