# Logics Template Application

Modern PHP application template with enterprise-grade features and best practices.

## 🚀 Features

- **Modern PHP 8.1+** with type hints and strict typing
- **MVC Architecture** with clean separation of concerns
- **Database Abstraction** with PDO and query builder
- **Authentication System** with CSRF protection
- **Session Management** with security features
- **Unified Notification System** for alerts and messages
- **Responsive Design** with Bootstrap 5
- **Testing Framework** with PHPUnit
- **Code Quality Tools** with PHPStan and CodeSniffer
- **CI/CD Pipeline** with GitHub Actions
- **Security Scanning** with automated vulnerability checks

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or SQLite 3
- Composer
- Web server (Apache/Nginx)

## 🛠️ Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd template
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp env.example .env
# Edit .env with your database settings
```

### 4. Database Setup

```bash
# Create database tables
php database/setup.php
```

### 5. Run Application

```bash
php -S localhost:8000
```

## 🧪 Testing

### Run All Tests

```bash
composer test
```

### Run with Coverage

```bash
composer test-coverage
```

### Run Specific Test Suite

```bash
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Integration
vendor/bin/phpunit tests/Feature
```

## 🔍 Code Quality

### PHPStan Static Analysis

```bash
composer stan
```

### CodeSniffer

```bash
composer cs-check
```

### Fix Code Style

```bash
composer cs-fix
```

### Run All Quality Checks

```bash
composer quality
```

## 🚀 CI/CD Pipeline

The application includes automated CI/CD pipeline with:

- **Automated Testing** on PHP 8.1, 8.2, 8.3
- **Code Quality Checks** with PHPStan and CodeSniffer
- **Security Scanning** with Composer audit
- **Coverage Reports** with Codecov integration
- **Automated Deployment** to production

## 📁 Project Structure

```
app/
├── config/          # Configuration files
├── controllers/     # MVC Controllers
├── core/           # Core framework classes
├── models/         # Data models
├── services/       # Business logic services
└── views/          # View templates

assets/
├── css/            # Stylesheets
├── js/             # JavaScript files
├── images/         # Static images
└── uploads/        # User uploads

tests/
├── Unit/           # Unit tests
├── Integration/    # Integration tests
└── Feature/        # Feature tests

.github/
└── workflows/      # GitHub Actions
```

## 🔧 Development

### Adding New Features

1. Create feature branch
2. Write tests first (TDD)
3. Implement feature
4. Run quality checks
5. Create pull request

### Code Standards

- Follow PSR-12 coding standards
- Use type hints for all methods
- Write comprehensive tests
- Document public APIs
- Keep functions small and focused

## 🛡️ Security

- CSRF protection on all forms
- SQL injection prevention with prepared statements
- XSS protection with output escaping
- Secure session management
- Input validation and sanitization
- Regular security audits

## 📊 Performance

- Optimized autoloading with Composer
- Database query optimization
- Asset compression and caching
- Lazy loading for heavy resources
- CDN-ready static assets

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Write tests for new features
4. Ensure all tests pass
5. Submit pull request

## 📄 License

MIT License - see LICENSE file for details.

## 🆘 Support

For support and questions:

- Create an issue on GitHub
- Contact: dev@logics-ti.com
- Documentation: [Wiki](link-to-wiki)

---

**Built with ❤️ by Logics Team**
