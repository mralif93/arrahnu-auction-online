# ArRahnu Auction Online - Development README

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- PostgreSQL 12+
- Node.js 16+
- Git

### Setup
```bash
# Clone and setup
git clone <repository-url>
cd arrahnu-auction-online
composer install
npm install
cp .env.example .env

# Configure database in .env
# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Start development servers
php artisan serve          # Laravel server
npm run dev               # Vite dev server
php artisan queue:work    # Queue worker
php artisan pail          # Log viewer
```

## 📁 Project Structure

```
app/
├── Console/Commands/     # Artisan commands
├── Http/Controllers/     # Web & API controllers
├── Models/              # Eloquent models
├── Services/            # Business logic
└── Traits/              # Reusable traits

database/
├── migrations/          # Database schema
├── seeders/            # Sample data
└── factories/           # Test data

routes/
├── web.php             # Web routes
└── api.php             # API routes

tests/                   # Test suites
docs/                    # Documentation
```

## 🔑 Key Features

- **Multi-role Users**: Maker, Checker, Bidder, Admin
- **Auction Management**: Complete lifecycle management
- **Collateral System**: Item tracking and valuation
- **Bidding Engine**: Real-time bidding with validation
- **Admin Dashboard**: Monitoring and analytics
- **RESTful API**: Mobile and third-party integration
- **Audit Trail**: Complete activity logging

## 🛠️ Development

### Commands
```bash
# Testing
php artisan test                 # Run all tests
php artisan test --coverage      # With coverage
php artisan test --parallel      # Parallel execution

# Database
php artisan migrate              # Run migrations
php artisan db:seed             # Seed database
php artisan migrate:rollback    # Rollback migrations

# Development
php artisan serve               # Start dev server
php artisan queue:work         # Process queues
php artisan pail               # View logs
npm run dev                    # Build assets
```

### Code Standards
- PSR-12 PHP coding standards
- Laravel conventions
- Comprehensive testing
- PHPDoc documentation

## 📚 Documentation

- [**Project Documentation**](PROJECT_DOCUMENTATION.md) - Comprehensive system overview
- [**Technical Architecture**](TECHNICAL_ARCHITECTURE.md) - System design and architecture
- [**Development Guide**](DEVELOPMENT_GUIDE.md) - Detailed development instructions
- [**API Documentation**](docs/API_DOCUMENTATION.md) - API endpoint reference

## 🔒 Security

- Role-based access control
- Multi-level approval workflows
- Account locking and IP tracking
- Complete audit logging
- Input validation and sanitization

## 📊 Monitoring

- Real-time API health checks
- Performance metrics
- Error tracking
- System resource monitoring
- Comprehensive audit trails

## 🚀 Deployment

### Production Checklist
- [ ] Environment configuration
- [ ] Database optimization
- [ ] Asset compilation
- [ ] Queue workers
- [ ] SSL certificates
- [ ] Monitoring setup

### Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Implement changes with tests
4. Submit pull request
5. Address review feedback

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For development questions:
- Check the documentation
- Review existing issues
- Create new issue with details
- Contact development team

---

**Happy Coding! 🎉**
