<?php
/**
 * Traballa - Simple router
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */

class Router {
    private $routes = [];
    private $basePage = 'dashboard';
    private $pagesPath = '../pages/';
    private $session;
    
    public function __construct($session) {
        $this->session = $session;
        $this->setupRoutes();
    }
    
    /**
     * Setup all available routes
     */
    private function setupRoutes() {
        // Public routes (no authentication required)
        $this->addRoute('login', [
            'file' => 'login.php',
            'auth' => false,
            'title' => 'Login - Traballa'
        ]);
        
        $this->addRoute('register', [
            'file' => 'register.php',
            'auth' => false,
            'title' => 'Register - Traballa'
        ]);
        
        $this->addRoute('forgot-password', [
            'file' => 'forgot-password.php',
            'auth' => false,
            'title' => 'Forgot Password - Traballa'
        ]);
        
        $this->addRoute('reset-password', [
            'file' => 'reset-password.php',
            'auth' => false,
            'title' => 'Reset Password - Traballa'
        ]);
        
        $this->addRoute('gdpr', [
            'file' => 'gdpr.php',
            'auth' => false,
            'title' => 'GDPR - Traballa'
        ]);
        
        // Protected routes (authentication required)
        $this->addRoute('dashboard', [
            'file' => 'dashboard.php',
            'auth' => true,
            'title' => 'Dashboard - Traballa',
            'css' => ['pomodoro.css'],
            'js' => ['pomodoro.js']
        ]);
        
        $this->addRoute('profile', [
            'file' => 'profile.php',
            'auth' => true,
            'title' => 'Profile - Traballa'
        ]);
        
        $this->addRoute('work-hours', [
            'file' => 'work-hours.php',
            'auth' => true,
            'title' => 'Work Hours - Traballa'
        ]);
        
        $this->addRoute('reports', [
            'file' => 'reports.php',
            'auth' => true,
            'title' => 'Reports - Traballa'
        ]);
        
        $this->addRoute('calendar', [
            'file' => 'calendar.php',
            'auth' => true,
            'title' => 'Calendar - Traballa'
        ]);
        
        $this->addRoute('kanban', [
            'file' => 'kanban.php',
            'auth' => true,
            'title' => 'Kanban - Traballa'
        ]);
        
        $this->addRoute('settings', [
            'file' => 'settings.php',
            'auth' => true,
            'title' => 'Settings - Traballa'
        ]);
        
        // Management routes (require management permissions)
        $this->addRoute('projects', [
            'file' => 'projects.php',
            'auth' => true,
            'permission' => 'management',
            'title' => 'Projects - Traballa'
        ]);
        
        $this->addRoute('organizations', [
            'file' => 'organizations.php',
            'auth' => true,
            'permission' => 'management',
            'title' => 'Organizations - Traballa'
        ]);
        
        $this->addRoute('project-details', [
            'file' => 'project-details.php',
            'auth' => true,
            'title' => 'Project Details - Traballa'
        ]);
        
        $this->addRoute('organization-details', [
            'file' => 'organization-details.php',
            'auth' => true,
            'title' => 'Organization Details - Traballa'
        ]);
        
        // Admin routes (require admin permissions)
        $this->addRoute('users', [
            'file' => 'users.php',
            'auth' => true,
            'permission' => 'admin',
            'title' => 'Users - Traballa'
        ]);
        
        $this->addRoute('smtp-settings', [
            'file' => 'smtp-settings.php',
            'auth' => true,
            'permission' => 'admin',
            'title' => 'SMTP Settings - Traballa'
        ]);

        // Terms of service and privacy policy
        $this->addRoute('terms-of-service', [
            'file' => 'terms-of-service.php',
            'auth' => false,
            'title' => 'Terms of service - Traballa'
        ]);
        $this->addRoute('privacy-policy', [
            'file' => 'privacy-policy.php',
            'auth' => false,
            'title' => 'Privacy policy - Traballa'
        ]);

        // Landing page
        $this->addRoute('landing', [
            'file' => 'landing.php',
            'auth' => false,
            'title' => 'Traballa - Time tracking made easy',
            'css' => ['landing.css'],
            'js' => ['landing.js']
        ]);

        // Login, register, and other auth pages
        $this->addRoute('login', [
            'file' => 'login.php',
            'auth' => false,
            'title' => 'Login - Traballa'
        ]);
        $this->addRoute('register', [
            'file' => 'register.php',
            'auth' => false,
            'title' => 'Register - Traballa'
        ]);
        $this->addRoute('forgot-password', [
            'file' => 'forgot-password.php',
            'auth' => false,
            'title' => 'Forgot Password - Traballa'
        ]);
        $this->addRoute('reset-password', [
            'file' => 'reset-password.php',
            'auth' => false,
            'title' => 'Reset Password - Traballa'
        ]);
        
    }
    
    /**
     * Add a route to the router
     */
    public function addRoute($name, $config) {
        $this->routes[$name] = $config;
    }
    
    /**
     * Check if request comes from main website URL
     */
    private function isMainWebsite() {
        if (!defined('MAIN_WEBSITE_URL')) {
            return false;
        }
        
        $currentHost = $_SERVER['HTTP_HOST'] ?? '';
        $mainUrl = MAIN_WEBSITE_URL;
        
        // Remove protocol if accidentally included
        $mainUrl = preg_replace('/^https?:\/\//', '', $mainUrl);
        
        // Check exact match or www alias
        return $currentHost === $mainUrl || $currentHost === 'www.' . $mainUrl;
    }
    
    /**
     * Get the current route from URL
     */
    public function getCurrentRoute() {
        return isset($_GET['page']) ? $_GET['page'] : $this->basePage;
    }
    
    /**
     * Check if a route exists
     */
    public function routeExists($route) {
        return isset($this->routes[$route]);
    }
    
    /**
     * Get route configuration
     */
    public function getRoute($route) {
        return $this->routes[$route] ?? null;
    }
    
    /**
     * Get all routes
     */
    public function getRoutes() {
        return $this->routes;
    }
    
    /**
     * Check if user has permission to access a route
     */
    public function hasPermission($route) {
        $routeConfig = $this->getRoute($route);
        if (!$routeConfig) {
            return false;
        }
        
        // Check authentication
        if ($routeConfig['auth'] && !$this->session->get('user_id')) {
            return false;
        }
        
        // Check specific permissions
        if (isset($routeConfig['permission'])) {
            switch ($routeConfig['permission']) {
                case 'admin':
                    return isAdmin();
                case 'management':
                    return hasManagementPermissions();
                default:
                    return true;
            }
        }
        
        return true;
    }
    
    /**
     * Resolve the current route and return page info
     */
    public function resolve() {
        $currentRoute = $this->getCurrentRoute();
        
        // Special handling for home page (dashboard) when not logged in from main website
        if ($currentRoute === 'dashboard' && !$this->session->get('user_id') && $this->isMainWebsite()) {
            $currentRoute = 'landing';
        }
        
        // Check if route exists
        if (!$this->routeExists($currentRoute)) {
            return $this->get404Page();
        }
        
        $routeConfig = $this->getRoute($currentRoute);
        
        // Check permissions
        if (!$this->hasPermission($currentRoute)) {
            // If not authenticated, redirect to login
            if ($routeConfig['auth'] && !$this->session->get('user_id')) {
                return $this->getRedirectInfo('landing.php');
            }
            
            // If authenticated but no permission, show 403
            return $this->get403Page();
        }
        
        // Check if file exists
        $filePath = $this->pagesPath . $routeConfig['file'];
        if (!file_exists($filePath)) {
            error_log("Page file not found: $filePath");
            return $this->get404Page();
        }
        
        return [
            'page' => $currentRoute,
            'file' => $filePath,
            'config' => $routeConfig,
            'title' => $routeConfig['title'] ?? 'Traballa',
            'css' => $routeConfig['css'] ?? [],
            'js' => $routeConfig['js'] ?? [],
            'auth_required' => $routeConfig['auth'] ?? false
        ];
    }
    
    /**
     * Get 404 page info
     */
    private function get404Page() {
        return [
            'page' => '404',
            'file' => $this->pagesPath . '404.php',
            'config' => ['auth' => false],
            'title' => '404 - Page Not Found',
            'css' => [],
            'js' => [],
            'auth_required' => false
        ];
    }
    
    /**
     * Get 403 page info
     */
    private function get403Page() {
        return [
            'page' => '403',
            'file' => $this->pagesPath . '403.php',
            'config' => ['auth' => true],
            'title' => '403 - Access Denied',
            'css' => [],
            'js' => [],
            'auth_required' => true
        ];
    }
    
    /**
     * Get redirect info
     */
    private function getRedirectInfo($url) {
        return [
            'redirect' => $url,
            'page' => null,
            'file' => null,
            'config' => null
        ];
    }
    
    /**
     * Check if current request is an action request
     */
    public function isActionRequest() {
        $no_navbar_actions = [
            'end_break',
            'start_break',
            'clock_out',
            'clock_in',
            'get_event',
        ];
        
        return (isset($_POST['action']) && in_array($_POST['action'], $no_navbar_actions)) || 
               (isset($_GET['action']) && in_array($_GET['action'], $no_navbar_actions));
    }
    
    /**
     * Generate URL for a route
     */
    public function url($route, $params = []) {
        // Check if using mod_rewrite (friendly URLs)
        $useFriendlyUrls = function_exists('apache_get_modules') && 
                          in_array('mod_rewrite', apache_get_modules());
        
        if ($useFriendlyUrls && $route !== 'dashboard') {
            $url = '/' . $route;
        } else if ($route === 'dashboard') {
            $url = '/';
        } else {
            $url = 'index.php?page=' . $route;
        }
        
        // Add parameters
        if (!empty($params)) {
            $separator = $useFriendlyUrls ? '?' : '&';
            $url .= $separator . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * Get navigation menu items
     */
    public function getNavigationItems() {
        $items = [];
        
        foreach ($this->routes as $route => $config) {
            // Skip non-menu items
            if (in_array($route, ['login', 'register', 'forgot-password', 'reset-password', 'gdpr', '404', '403'])) {
                continue;
            }
            
            // Skip if user doesn't have permission
            if (!$this->hasPermission($route)) {
                continue;
            }
            
            $items[$route] = [
                'name' => $route,
                'title' => str_replace(' - Traballa', '', $config['title']),
                'url' => $this->url($route),
                'icon' => $this->getRouteIcon($route)
            ];
        }
        
        return $items;
    }
    
    /**
     * Get icon for a route
     */
    private function getRouteIcon($route) {
        $icons = [
            'dashboard' => 'fas fa-tachometer-alt',
            'kanban' => 'fas fa-columns',
            'work-hours' => 'fas fa-clock',
            'reports' => 'fas fa-chart-bar',
            'calendar' => 'fas fa-calendar-alt',
            'projects' => 'fas fa-project-diagram',
            'organizations' => 'fas fa-building',
            'users' => 'fas fa-users',
            'profile' => 'fas fa-id-card',
            'settings' => 'fas fa-cog',
        ];
        
        return $icons[$route] ?? 'fas fa-file';
    }
}
