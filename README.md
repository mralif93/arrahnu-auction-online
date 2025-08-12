# ArRahnu Auction Online

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-12+-blue.svg)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🏛️ About

**ArRahnu Auction Online** is a comprehensive Laravel-based auction management system designed for Islamic pawnbroking (ArRahnu) operations. The system facilitates the auctioning of collateral items (typically gold and jewelry) that have been pledged as security for loans.

## ✨ Key Features

- **Multi-role User Management** with approval workflows (Maker, Checker, Bidder, Admin)
- **Complete Auction Management** from creation to completion
- **Collateral Management** with image support and valuations
- **Real-time Bidding System** with validation and tracking
- **Multi-branch Operations** with account segregation
- **Comprehensive Admin Dashboard** with monitoring and analytics
- **RESTful API** for mobile and third-party integrations
- **Audit Trail** for compliance and transparency

## �� Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- PostgreSQL 12 or higher
- Node.js 16 or higher

### Installation
```bash
# Clone the repository
git clone <repository-url>
cd arrahnu-auction-online

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### Development
```bash
# Start development servers
php artisan serve          # Laravel server
npm run dev               # Vite dev server
php artisan queue:work    # Queue worker
php artisan pail          # Log viewer

# Run tests
php artisan test
```

## 📚 Documentation

**All comprehensive documentation is available in the [`docs/`](docs/) folder:**

- **[📖 Documentation Index](docs/DOCUMENTATION_INDEX.md)** - Complete guide to all documentation
- **[🏗️ Project Documentation](docs/PROJECT_DOCUMENTATION.md)** - Comprehensive system overview
- **[📋 Project Summary](docs/PROJECT_SUMMARY.md)** - Quick project overview
- **[🏛️ Technical Architecture](docs/TECHNICAL_ARCHITECTURE.md)** - System design and architecture
- **[👨‍💻 Development Guide](docs/DEVELOPMENT_GUIDE.md)** - Detailed development instructions
- **[🚀 Development README](docs/README_DEVELOPMENT.md)** - Quick development reference

### Existing Documentation
The `docs/` folder also contains extensive existing documentation covering:
- API documentation and guides
- Mobile integration guides
- Admin system documentation
- User management features
- Testing documentation
- Implementation reports

## 🏗️ System Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Frontend  │    │  Mobile App     │    │  Third-party    │
│   (Blade Views) │    │  (Flutter)      │    │  Integrations   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │   Laravel API   │
                    │   (REST/JSON)   │
                    └─────────────────┘
                                 │
                    ┌─────────────────┐
                    │   Database      │
                    │   (PostgreSQL)   │
                    └─────────────────┘
```

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x, PHP 8.2+
- **Database**: PostgreSQL with UUID primary keys
- **Authentication**: Laravel Sanctum
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js
- **Testing**: PHPUnit
- **Development**: Laravel Sail (Docker)

## 🔒 Security Features

- Role-based access control (RBAC)
- Multi-level approval workflows
- Account locking and IP tracking
- Complete audit logging
- Input validation and sanitization
- CSRF protection and secure sessions

## 📊 Monitoring & Analytics

- Real-time API health monitoring
- Dashboard analytics and statistics
- System performance metrics
- Comprehensive audit trails
- Error tracking and debugging

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: Check the [`docs/`](docs/) folder first
- **Issues**: Create an issue with detailed information
- **Questions**: Review existing documentation or create an issue

## 🎯 Business Domain

The system operates in the Islamic finance sector, specifically ArRahnu (Islamic pawnbroking), where:
- Customers pledge gold/jewelry as collateral for loans
- If loans are not repaid, collateral items are auctioned
- Proceeds are used to settle outstanding loans
- Remaining funds are returned to customers

---

**For comprehensive documentation, start with the [Documentation Index](docs/DOCUMENTATION_INDEX.md) in the `docs/` folder.**

**Happy Coding! 🎉**
