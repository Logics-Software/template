# Logics PHP MVC Template

A lightweight, modern PHP MVC template based on the Logics dashboard design. This template provides a solid foundation for building web applications with clean architecture, modern UI components, and multi-database support.

## Features

- **Modern MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Multi-Database Support**: MySQL, SQL Server, and PostgreSQL with transaction support
- **Responsive Design**: Bootstrap 5 with custom CSS for light/dark/auto themes
- **AJAX Support**: Built-in AJAX functionality for dynamic content
- **Authentication System**: Complete user authentication with session management
- **Dashboard**: Beautiful dashboard with charts and statistics
- **User Management**: Full CRUD operations for user management
- **Form Validation**: Server-side validation with error handling
- **Security**: CSRF protection, input sanitization, and secure sessions
- **Deployment Ready**: Easy deployment to subdomains or subfolders

## Requirements

- PHP 7.4 or higher
- MySQL 5.7+, SQL Server 2016+, or PostgreSQL 10+
- Apache with mod_rewrite enabled
- Composer (optional, for dependency management)

## Installation

### 1. Clone or Download

```bash
git clone https://github.com/your-repo/logics-php-mvc.git
cd logics-php-mvc
```

### 2. Configure Database

Edit `app/config/config.php` and update the database settings:

```php
define('DB_TYPE', 'mysql'); // mysql, sqlsrv, pgsql
define('DB_HOST', 'localhost');
define('DB_NAME', 'logics_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_PORT', 3306);
```

### 3. Create Database

Create the database and run migrations:

```bash
# Create database (replace with your database name)
mysql -u root -p -e "CREATE DATABASE logics_db;"

# Run migrations
php database/migrate.php

# Seed with sample data (optional)
php database/seed.php
```

### 4. Set Permissions

```bash
chmod 755 assets/
chmod 644 .htaccess
```

### 5. Access the Application

Open your browser and navigate to your application URL. The default login credentials are:

- **Email**: admin@logics.com
- **Password**: password

## Project Structure

```
logics-php-mvc/
├── app/
│   ├── config/
│   │   └── config.php          # Application configuration
│   ├── controllers/
│   │   ├── BaseController.php   # Base controller class
│   │   ├── AuthController.php   # Authentication controller
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   └── ApiController.php
│   ├── core/
│   │   ├── App.php              # Main application class
│   │   ├── Router.php           # URL routing
│   │   ├── Request.php          # HTTP request handling
│   │   ├── Response.php         # HTTP response handling
│   │   ├── View.php             # Template rendering
│   │   ├── Database.php         # Database abstraction
│   │   ├── Model.php            # Base model class
│   │   ├── Session.php          # Session management
│   │   ├── Validator.php        # Form validation
│   │   └── Autoloader.php       # Class autoloading
│   ├── models/
│   │   ├── User.php             # User model
│   │   └── Stats.php            # Statistics model
│   └── views/
│       ├── layouts/
│       │   └── main.php         # Main layout template
│       ├── auth/
│       │   ├── login.php        # Login page
│       │   └── register.php     # Registration page
│       ├── dashboard/
│       │   └── index.php        # Dashboard page
│       └── users/
│           ├── index.php        # Users list
│           └── create.php        # Create user form
├── assets/
│   ├── css/
│   │   └── style.css            # Custom styles
│   └── js/
│       └── app.js               # JavaScript functionality
├── database/
│   ├── migrations/              # Database migrations
│   ├── seeds/                   # Sample data
│   ├── migrate.php              # Migration runner
│   └── seed.php                 # Seeding runner
├── index.php                    # Application entry point
├── .htaccess                    # Apache configuration
├── composer.json                # Composer configuration
└── README.md                    # This file
```

## Configuration

### Database Configuration

The application supports three database types:

#### MySQL

```php
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'logics_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);
```

#### SQL Server

```php
define('DB_TYPE', 'sqlsrv');
define('DB_HOST', 'localhost');
define('DB_NAME', 'logics_db');
define('DB_USER', 'sa');
define('DB_PASS', 'your_password');
define('DB_PORT', 1433);
```

#### PostgreSQL

```php
define('DB_TYPE', 'pgsql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'logics_db');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_password');
define('DB_PORT', 5432);
```

### Theme Configuration

The application supports three theme modes:

- **Light**: Default light theme
- **Dark**: Dark theme for low-light environments
- **Auto**: Automatically switches based on system preference

Users can toggle themes using the theme selector in the navigation bar.

## Usage

### Creating Controllers

```php
class MyController extends BaseController
{
    public function index()
    {
        $this->view('my/index', [
            'title' => 'My Page',
            'data' => $this->getData()
        ]);
    }
}
```

### Creating Models

```php
class MyModel extends Model
{
    protected $table = 'my_table';
    protected $fillable = ['name', 'email', 'status'];

    public function findByStatus($status)
    {
        return $this->findAll('status = :status', ['status' => $status]);
    }
}
```

### Database Operations

```php
// Using the model
$userModel = new User();
$users = $userModel->findAll('status = :status', ['status' => 'active']);

// Using transactions
$userModel->beginTransaction();
try {
    $userModel->create($data);
    $userModel->commit();
} catch (Exception $e) {
    $userModel->rollback();
    throw $e;
}
```

### AJAX Requests

```javascript
// Making AJAX requests
Logics.ajaxRequest("/api/users", {
  method: "POST",
  body: JSON.stringify({ name: "John", email: "john@example.com" }),
}).then((data) => {
  if (data.success) {
    Logics.showAlert("User created successfully", "success");
  }
});
```

## Deployment

### Subdomain Deployment

1. Upload files to your subdomain directory
2. Update `APP_URL` in `config.php` if needed
3. Configure database settings
4. Run migrations: `php database/migrate.php`

### Subfolder Deployment

1. Upload files to a subfolder (e.g., `/myapp/`)
2. Update `.htaccess` if needed for subfolder routing
3. Update `APP_URL` in `config.php` to include the subfolder path
4. Configure database settings
5. Run migrations: `php database/migrate.php`

### Production Considerations

1. **Security**:

   - Change default encryption key
   - Use strong database passwords
   - Enable HTTPS
   - Set proper file permissions

2. **Performance**:

   - Enable PHP OPcache
   - Use a CDN for static assets
   - Configure proper caching headers

3. **Database**:
   - Use connection pooling
   - Optimize database queries
   - Set up database backups

## API Endpoints

### Authentication

- `GET /login` - Login page
- `POST /login` - Authenticate user
- `GET /logout` - Logout user
- `GET /register` - Registration page
- `POST /register` - Create new user

### Dashboard

- `GET /dashboard` - Dashboard page
- `GET /api/stats` - Get dashboard statistics

### Users

- `GET /users` - Users list
- `GET /users/create` - Create user form
- `POST /users` - Create user
- `GET /users/{id}` - View user
- `GET /users/{id}/edit` - Edit user form
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

### API

- `GET /api/theme` - Get current theme
- `POST /api/theme` - Set theme
- `GET /api/stats` - Get statistics
- `GET /api/users/search` - Search users

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:

- Create an issue on GitHub
- Email: support@logics.com
- Documentation: [docs.logics.com](https://docs.logics.com)

## Changelog

### Version 1.0.0

- Initial release
- MVC architecture
- Multi-database support
- Authentication system
- Dashboard with charts
- User management
- Theme support
- AJAX functionality
