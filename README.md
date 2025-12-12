# Laravel API Versioning Application

A comprehensive Laravel API application demonstrating best practices for versioning REST APIs with two different API versions (v1 and v2) that showcase different levels of feature implementation and response formatting.

## 🎯 Features

### API Versioning
- **API v1**: Simplified endpoints with basic functionality
- **API v2**: Enhanced endpoints with advanced features like add, update, delete comments on the posts
- Easy version migration path for API consumers
- Consistent versioning structure across all endpoints

### Authentication
- User registration and login
- Token-based authentication using Laravel Sanctum
- Secure endpoint protection for authenticated users
- Logout functionality

### Core Resources
- **Posts**: Create, read, update, delete blog posts
- **Users**: Manage user profiles and view associated posts
- **Comments**: Track comments on posts (extensible)

### API Enhancements in V2
- Post comments add, update, delete

## 📋 Project Structure

```
laravel_api_versioning/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── V1/
│   │   │   │   │   ├── PostController.php
│   │   │   │   │   └── UserController.php
│   │   │   │   └── V2/
│   │   │   │       ├── PostController.php
│   │   │   │       └── UserController.php
│   │   │   └── Auth/
│   │   │       └── UserAuthController.php
│   │   
│   │   └── Resources/
│   │       ├── Api/
│   │       │   ├── V1/
│   │       │   │   ├── PostResource.php
│   │       │   │   └── UserResource.php
│   │       │   └── V2/
│   │       │       ├── PostResource.php
│   │       │       └── UserResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   └── Comment.php
│   └── Traits/
│       └── ApiResponser.php
├── routes/
│   ├── api.php (Main API routes)
│   ├── api_v1.php
│   └── api_v2.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── └── composer.json
```

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL or SQLite
- XAMPP (for local development)

### Setup Steps

1. **Clone or download the project**
```bash
cd laravel_api_versioning
```

2. **Install dependencies**
```bash
composer install
```

3. **Create environment file**
```bash
cp .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Configure database**
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api_versioning
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed sample data (optional)**
```bash
php artisan db:seed
```

8. **Start the development server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api/`

## 🔐 Authentication

### Register a User
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

## 📡 API Endpoints

### API v1 - Basic Endpoints

#### Posts
| Method | Endpoint | Auth Required | Description |
|--------|----------|---------------|-------------|
| GET | `/api/v1/posts` | No | List all posts (paginated) |
| GET | `/api/v1/posts/{id}` | No | Get a single post |
| POST | `/api/v1/posts` | Yes | Create a new post |
| PUT | `/api/v1/posts/{id}` | Yes | Update a post |
| DELETE | `/api/v1/posts/{id}` | Yes | Delete a post |

### API v2 - Enhanced Endpoints

#### Posts (with Advanced Features)
| Method | Endpoint | Auth Required | Description |
|--------|----------|---------------|-------------|
| GET | `/api/v2/posts` | No | List posts with filtering & sorting |
| GET | `/api/v2/posts/{id}` | No | Get post with detailed metadata |
| POST | `/api/v2/posts` | Yes | Create post with validation |
| PUT | `/api/v2/posts/{id}` | Yes | Full update of post |
| PATCH | `/api/v2/posts/{id}` | Yes | Partial update of post (NEW) |
| DELETE | `/api/v2/posts/{id}` | Yes | Delete post |


## 📝 Usage Examples

### Create a Post (V1)
```bash
curl -X POST http://localhost:8000/api/v1/posts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My First Post",
    "content": "This is the content of my first post",
    "published": true
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "My First Post",
    "content": "This is the content of my first post",
    "published": true,
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-12-12 10:30:45",
    "updated_at": "2025-12-12 10:30:45"
  }
}
```

```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "My First Post",
      "content": "This is the content of my first post",
      "status": "published",
      "published": true,
      "author": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "timestamps": {
        "created_at": "2025-12-12T10:30:45Z",
        "updated_at": "2025-12-12T10:30:45Z",
        "created_at_human": "5 minutes ago",
        "updated_at_human": "5 minutes ago"
      },
      "metadata": {
        "url": "http://localhost:8000/api/v2/posts/1",
        "type": "post"
      }
    }
  ],
  "meta": {
    "total": 1,
    "per_page": 10,
    "current_page": 1,
    "last_page": 1,
    "from": 1,
    "to": 1
  },
  "links": {
    "first": "http://localhost:8000/api/v2/posts?page=1",
    "last": "http://localhost:8000/api/v2/posts?page=1",
    "prev": null,
    "next": null
  }
}
```

### Partial Update Post (V2 PATCH)
```bash
curl -X PATCH http://localhost:8000/api/v2/posts/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title"
  }'
```

### Get User with Statistics (V2)
```bash
curl http://localhost:8000/api/v2/users/1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "profile": {
      "posts_count": 5,
      "published_posts": 4,
      "draft_posts": 1,
      "member_since": "2025-12-10"
    },
    "timestamps": {
      "created_at": "2025-12-10T08:15:30Z",
      "updated_at": "2025-12-12T10:30:45Z"
    },
    "metadata": {
      "url": "http://localhost:8000/api/v2/users/1",
      "type": "user",
      "posts_url": "http://localhost:8000/api/v2/posts?user_id=1"
    }
  },
  "stats": {
    "total_posts": 5,
    "published_posts": 4,
    "draft_posts": 1
  }
}
```

## 🛡️ Authorization

The application uses Laravel's built-in authorization system with a `PostPolicy` class to control:
- Users can only update/delete their own posts
- Public endpoints are accessible without authentication
- Protected endpoints require a valid API token

## 📝 Database Schema

### Users Table
- id
- name
- email
- password
- email_verified_at
- created_at
- updated_at

### Posts Table
- id
- user_id (foreign key)
- title
- content
- published (boolean)
- created_at
- updated_at

### Comments Table
- id
- post_id (foreign key)
- user_id (foreign key)
- content
- created_at
- updated_at

## 🐛 Debugging

Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

View logs:
```bash
tail -f storage/logs/laravel.log
```

## 📦 Dependencies

- **Laravel 11+**: Web framework
- **Laravel Sanctum**: API authentication
- **PHPUnit**: Testing framework
- **Faker**: Sample data generation

## 🚦 Request/Response Headers

Every API response includes:
```
X-API-Version: v1 or v2
X-API-Timestamp: 2025-12-12T10:30:45Z
Content-Type: application/json
```

## 🔗 Related Files

- [Routes Configuration](routes/api.php)
- [V1 Controllers](app/Http/Controllers/Api/V1/)
- [V2 Controllers](app/Http/Controllers/Api/V2/)
- [API Resources](app/Http/Resources/Api/)

## 📄 License

This is a sample application for educational purposes.

## 🤝 Contributing

This is a sample/learning project. Feel free to fork and modify for your own use.

## 📞 Support

For issues or questions about API versioning in Laravel:
1. Review the controller implementations
2. Inspect the resource classes for response formatting

---

**Last Updated:** December 12, 2025  
**Laravel Version:** 11.x  
**PHP Version:** 8.2+
