# 📊 Traballa - Work Tracking & Project Management System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

A comprehensive work tracking and project management system developed as part of a Final Course Project (TFC). Traballa provides organizations with powerful tools to track work hours, manage projects, organize teams, and boost productivity through integrated task management and time tracking features.

## ✨ Features

### 🔐 **Authentication & Security**
- Secure user authentication with session management
- Role-based access control (Admin, Manager, Member)
- Password reset functionality with email notifications

### ⏱️ **Time Tracking**
- Real-time work hour tracking with clock in/out functionality
- Break management system
- Comprehensive time reporting and analytics
- Export capabilities for payroll and billing
- Overtime calculation and monitoring

### 📋 **Project Management**
- Multi-organization support
- Project creation and management
- Team assignment and role management
- Project status tracking (Active, On Hold, Completed)
- Detailed project analytics and reporting

### 📊 **Kanban Board**
- Interactive drag-and-drop task management
- Customizable columns and workflows
- Task assignment and due date tracking
- Real-time collaboration features
- Mobile-optimized touch interface
- Task status management (Active, Pending, Completed)

### 📅 **Calendar Integration**
- Event scheduling and management
- Work hour visualization
- Project deadline tracking
- Personal and organization events
- Calendar export functionality

### 🍅 **Productivity Tools**
- Built-in Pomodoro timer
- Productivity tracking and statistics
- Work session management
- Break reminders and optimization

### 🤖 **AI Assistant (Workly)**
- Intelligent work tracking assistant
- Natural language queries for work data
- Quick actions and shortcuts
- Productivity insights and recommendations

### 📱 **Modern Interface**
- Responsive design for all devices
- Dark/light theme support
- Intuitive sidebar navigation
- Real-time notifications
- Mobile-first approach

## 🛠️ Technology Stack

- **Backend**: PHP 8.0+ with PDO
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5.3, JavaScript ES6+
- **Icons**: Font Awesome 6
- **Email**: PHPMailer for notifications
- **Architecture**: MVC pattern with custom routing

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx web server
- Composer for dependency management
- mod_rewrite enabled (optional, for clean URLs)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/markostech/traballa-tfc.git
   cd traballa-tfc
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Database setup**
   - Create a MySQL database
   - Import the database schema (see `config/database.sql`)
   - Copy `config/config.example.php` to `config/config.php`
   - Update database credentials in `config/config.php`

4. **Web server configuration**
   - Point document root to `webroot/` directory
   - Ensure Apache mod_rewrite is enabled for clean URLs
   - Set appropriate file permissions

5. **Email configuration (optional)**
   - Configure SMTP settings in `config/config.php`
   - Required for password reset and notifications

## 📁 Project Structure

```
traballa-tfc/
├── config/             # Configuration files
├── includes/           # Core functionality and helpers
│   ├── functions.php   # Database and utility functions
│   ├── Router.php      # Custom routing system
│   ├── Session.php     # Session management
│   └── ...
├── pages/              # Application pages
│   ├── dashboard.php   # Main dashboard
│   ├── kanban.php      # Kanban board
│   ├── projects.php    # Project management
│   └── ...
├── webroot/            # Public web directory
│   ├── assets/         # CSS, JS, images
│   ├── ajax/          # AJAX endpoints
│   ├── index.php      # Application entry point
│   └── ...
└── vendor/            # Composer dependencies
```

## 🎯 Usage

### For Organizations
1. **Setup**: Create organization accounts and configure user roles
2. **Projects**: Create projects and assign team members
3. **Tracking**: Monitor work hours and project progress
4. **Reports**: Generate comprehensive analytics and reports

### For Teams
1. **Collaboration**: Use Kanban boards for task management
2. **Time Management**: Track work hours with integrated timers
3. **Communication**: Coordinate through calendar events and notifications
4. **Productivity**: Utilize Pomodoro timers and productivity tools

### For Individuals
1. **Personal Tracking**: Monitor your own work hours and productivity
2. **Task Management**: Organize personal tasks and projects
3. **Analytics**: Review performance metrics and trends
4. **Goal Setting**: Set and track productivity goals

## 🔧 Configuration

Key configuration options in `config/config.php`:

```php
// Database settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'traballa_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Application settings
define('APP_NAME', 'Traballa');
define('APP_URL', 'https://your-domain.com');
define('DEBUG_MODE', false);

// Email settings (optional)
define('SMTP_HOST', 'your-smtp-server');
define('SMTP_USERNAME', 'your-email');
define('SMTP_PASSWORD', 'your-password');
```

## 🎨 Customization

- **Themes**: Modify CSS files in `webroot/assets/css/`
- **Features**: Extend functionality in `includes/functions.php`
- **Pages**: Add new pages in the `pages/` directory
- **Routing**: Configure routes in `includes/Router.php`

## 🔍 API Endpoints

The system includes AJAX endpoints for dynamic functionality:

- `/ajax/add-kanban-task.php` - Task creation
- `/ajax/update-kanban-task.php` - Task updates
- `/ajax/calendar-api.php` - Calendar operations
- `/ajax/manage-breaks.php` - Break management

## 🧪 Testing

The system includes comprehensive error handling and validation:

- Input sanitization and validation
- SQL injection prevention with prepared statements
- XSS protection with output escaping

## 📊 Performance

- **Responsive Design**: Optimized for mobile and desktop
- **Caching**: Efficient database queries with minimal overhead
- **Progressive Enhancement**: Works without JavaScript for core features
- **Optimized Assets**: Minified CSS and JS for production

## 🛡️ Security Features

- Secure session management
- SQL injection prevention
- XSS protection
- Role-based access control
- Secure password hashing

## 🌍 Internationalization

- Multi-language support (English/Spanish)
- Localized date and time formats
- Currency and number formatting
- Timezone support

## 📈 Analytics & Reporting

- Comprehensive work hour reports
- Project progress tracking
- Team productivity metrics
- Exportable data formats
- Visual charts and graphs

## 🤝 Contributing

This project is part of a Final Course Project (TFC). While it's primarily academic, contributions and suggestions are welcome:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Marcos Núñez Fernández**
- GitHub: [@markostech](https://github.com/markostech)
- Project: Final Course Project (TFC)

## 🔗 Links

- **Repository**: [https://github.com/markostech/traballa-tfc](https://github.com/markostech/traballa-tfc)
- **License**: [MIT License](https://opensource.org/licenses/MIT)

## 🙏 Acknowledgments

- Built with the technologies shown on DAW (Web applications development)
- Designed for educational and professional use
- Open source and freely available

---

*Traballa - Making work tracking simple, efficient, and productive.* ⚡
