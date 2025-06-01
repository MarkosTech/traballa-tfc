<?php
/**
 * Traballa - Breadcrumb manager
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

class Breadcrumb {
    private $items = [];
    private $router;
    
    public function __construct($router = null) {
        $this->router = $router;
        $this->setupDefaultBreadcrumbs();
    }
    
    /**
     * Setup default breadcrumbs for common routes
     */
    private function setupDefaultBreadcrumbs() {
        $breadcrumbs = [
            'dashboard' => [
                ['title' => 'Dashboard', 'url' => null]
            ],
            'projects' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Projects', 'url' => null]
            ],
            'project-details' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Projects', 'url' => 'projects'],
                ['title' => 'Project Details', 'url' => null]
            ],
            'organizations' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Organizations', 'url' => null]
            ],
            'organization-details' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Organizations', 'url' => 'organizations'],
                ['title' => 'Organization Details', 'url' => null]
            ],
            'users' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Users', 'url' => null]
            ],
            'work-hours' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Work Hours', 'url' => null]
            ],
            'reports' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Reports', 'url' => null]
            ],
            'calendar' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Calendar', 'url' => null]
            ],
            'kanban' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Kanban', 'url' => null]
            ],
            'profile' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Profile', 'url' => null]
            ],
            'settings' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'Settings', 'url' => null]
            ],
            'gdpr' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => 'GDPR', 'url' => null]
            ],
            '404' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => '404 Not Found', 'url' => null]
            ],
            '403' => [
                ['title' => 'Dashboard', 'url' => 'dashboard'],
                ['title' => '403 Forbidden', 'url' => null]
            ]
        ];
        
        $this->items = $breadcrumbs;
    }
    
    /**
     * Get breadcrumbs for a specific route
     */
    public function get($route, $customItems = []) {
        $breadcrumbs = $this->items[$route] ?? [
            ['title' => 'Dashboard', 'url' => 'dashboard'],
            ['title' => ucfirst(str_replace('-', ' ', $route)), 'url' => null]
        ];
        
        // Merge with custom items if provided
        if (!empty($customItems)) {
            $breadcrumbs = array_merge($breadcrumbs, $customItems);
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Set custom breadcrumbs for a route
     */
    public function set($route, $breadcrumbs) {
        $this->items[$route] = $breadcrumbs;
    }
    
    /**
     * Add a breadcrumb item to a route
     */
    public function add($route, $title, $url = null) {
        if (!isset($this->items[$route])) {
            $this->items[$route] = [];
        }
        
        $this->items[$route][] = ['title' => $title, 'url' => $url];
    }
    
    /**
     * Render breadcrumbs as HTML
     */
    public function render($route, $customItems = []) {
        $breadcrumbs = $this->get($route, $customItems);
        
        if (empty($breadcrumbs)) {
            return '';
        }
        
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb">';
        
        foreach ($breadcrumbs as $index => $item) {
            $isLast = ($index === count($breadcrumbs) - 1);
            
            if ($isLast || $item['url'] === null) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">';
                $html .= htmlspecialchars($item['title']);
                $html .= '</li>';
            } else {
                $html .= '<li class="breadcrumb-item">';
                $url = $this->router ? $this->router->url($item['url']) : $item['url'];
                $html .= '<a href="' . htmlspecialchars($url) . '">';
                $html .= htmlspecialchars($item['title']);
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * Render breadcrumbs with custom styling
     */
    public function renderCustom($route, $customItems = [], $separator = ' / ') {
        $breadcrumbs = $this->get($route, $customItems);
        
        if (empty($breadcrumbs)) {
            return '';
        }
        
        $html = '<div class="custom-breadcrumb">';
        $items = [];
        
        foreach ($breadcrumbs as $index => $item) {
            $isLast = ($index === count($breadcrumbs) - 1);
            
            if ($isLast || $item['url'] === null) {
                $items[] = '<span class="breadcrumb-current">' . htmlspecialchars($item['title']) . '</span>';
            } else {
                $url = $this->router ? $this->router->url($item['url']) : $item['url'];
                $items[] = '<a href="' . htmlspecialchars($url) . '" class="breadcrumb-link">' . htmlspecialchars($item['title']) . '</a>';
            }
        }
        
        $html .= implode($separator, $items);
        $html .= '</div>';
        
        return $html;
    }
}
