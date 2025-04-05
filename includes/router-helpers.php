<?php
/**
 * Traballa - Router Helper Functions
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/workhours-tfc
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