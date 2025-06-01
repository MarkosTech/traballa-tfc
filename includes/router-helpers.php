<?php
/**
 * Traballa - Router helpers
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
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */

if (!function_exists('route_url')) {
    /**
     * Generate URL for a route
     * 
     * @param string $route Route name
     * @param array $params URL parameters
     * @return string Generated URL
     */
    function route_url($route, $params = []) {
        global $router;
        
        if (isset($router) && $router instanceof Router) {
            return $router->url($route, $params);
        }
        
        // Fallback to old method if router not available
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
}

if (!function_exists('current_route')) {
    /**
     * Get current route name
     * 
     * @return string Current route name
     */
    function current_route() {
        global $router, $page;
        
        if (isset($router) && $router instanceof Router) {
            return $router->getCurrentRoute();
        }
        
        return $page ?? 'dashboard';
    }
}

if (!function_exists('is_current_route')) {
    /**
     * Check if given route is the current route
     * 
     * @param string $route Route name to check
     * @return bool True if current route
     */
    function is_current_route($route) {
        return current_route() === $route;
    }
}

if (!function_exists('route_exists')) {
    /**
     * Check if a route exists
     * 
     * @param string $route Route name
     * @return bool True if route exists
     */
    function route_exists($route) {
        global $router;
        
        if (isset($router) && $router instanceof Router) {
            return $router->routeExists($route);
        }
        
        return false;
    }
}

if (!function_exists('has_route_permission')) {
    /**
     * Check if user has permission to access a route
     * 
     * @param string $route Route name
     * @return bool True if user has permission
     */
    function has_route_permission($route) {
        global $router;
        
        if (isset($router) && $router instanceof Router) {
            return $router->hasPermission($route);
        }
        
        return false;
    }
}

if (!function_exists('navigation_items')) {
    /**
     * Get navigation items for current user
     * 
     * @return array Navigation items
     */
    function navigation_items() {
        global $router;
        
        if (isset($router) && $router instanceof Router) {
            return $router->getNavigationItems();
        }
        
        return [];
    }
}

if (!function_exists('breadcrumb')) {
    /**
     * Get breadcrumb instance
     * 
     * @return Breadcrumb Breadcrumb instance
     */
    function breadcrumb() {
        global $breadcrumb;
        
        if (!isset($breadcrumb)) {
            global $router;
            require_once __DIR__ . '/Breadcrumb.php';
            $breadcrumb = new Breadcrumb($router);
        }
        
        return $breadcrumb;
    }
}

if (!function_exists('render_breadcrumb')) {
    /**
     * Render breadcrumbs for current route
     * 
     * @param array $customItems Additional breadcrumb items
     * @return string HTML breadcrumbs
     */
    function render_breadcrumb($customItems = []) {
        $route = current_route();
        return breadcrumb()->render($route, $customItems);
    }
}

if (!function_exists('set_breadcrumb')) {
    /**
     * Set custom breadcrumbs for current route
     * 
     * @param array $breadcrumbs Breadcrumb items
     */
    function set_breadcrumb($breadcrumbs) {
        $route = current_route();
        breadcrumb()->set($route, $breadcrumbs);
    }
}

if (!function_exists('add_breadcrumb')) {
    /**
     * Add a breadcrumb item to current route
     * 
     * @param string $title Breadcrumb title
     * @param string $url Breadcrumb URL (optional)
     */
    function add_breadcrumb($title, $url = null) {
        $route = current_route();
        breadcrumb()->add($route, $title, $url);
    }
}