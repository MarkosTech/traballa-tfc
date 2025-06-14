<?php
/**
* Traballa Counter - Example Configuration File
* 
* Copy this file to config.php and update the values with your own settings.
*/

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'work_hours_counter');

// Application settings
define('APP_NAME', 'Traballa Counter');
define('APP_VERSION', '1.0.0');
define('TIMEZONE', 'America/New_York'); // Set your timezone
define('MAIN_WEBSITE_URL', 'example.com'); // Main website URL (without protocol)
define('SYSTEM_URL', 'none'); // System URL for dashboard redirects (without protocol) or none

// URL configuration - set to subfolder if installed in one
define('BASE_PATH', ''); // e.g., '/traballa' if installed in a subfolder
define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . BASE_PATH);

// Set timezone
date_default_timezone_set(TIMEZONE);

// SMTP configuration
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls'); // 'tls', 'ssl', or ''
define('SMTP_FROM_EMAIL', 'noreply@example.com');
define('SMTP_FROM_NAME', 'Traballa');

// Debug mode (set to false in production)
define('DEBUG_MODE', false);

// Error reporting
if (DEBUG_MODE) {
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
} else {
   error_reporting(0);
   ini_set('display_errors', 0);
}
