<?php
/**
 * Traballa - Simple router
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/workhours-tfc
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
    }
    
    /**
     * Add a route to the router
     */
    public function addRoute($name, $config) {
        $this->routes[$name] = $config;
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
            'test_action',
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

        ];
        
        return $icons[$route] ?? 'fas fa-file';
    }
}
