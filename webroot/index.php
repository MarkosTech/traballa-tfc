<?php
/**
 * Traballa entry point
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

define('INDEX_EXEC', true); // Allow execution of child scripts
// server should keep session data for AT LEAST 8 hours
//ini_set('session.gc_maxlifetime', 28800);
// each client should remember their session id for EXACTLY 8 hours
//session_set_cookie_params(28800);
session_start();
require_once '../config/database.php';
require_once '../includes/Session.php';
require_once '../includes/functions.php';
require_once '../includes/Router.php';
require_once '../includes/router-helpers.php';

// Initialize our session handler
$session = new Session($pdo);

// Initialize router
$router = new Router($session);

// Check if this is an action request (AJAX or similar)
if ($router->isActionRequest()) {
    // For action requests, just resolve and include the page content without HTML structure
    $routeInfo = $router->resolve();
    
    // Handle redirects for action requests
    if (isset($routeInfo['redirect'])) {
        header("Location: " . $routeInfo['redirect']);
        exit();
    }
    
    if ($routeInfo['file']) {
        include $routeInfo['file'];
    }
    exit();
}

// Check if this is a standalone page that has its own HTML structure
$routeInfo = $router->resolve();
$standalonePage = isset($routeInfo['page']) && in_array($routeInfo['page'], [
    'landing', 
    'terms-of-service', 
    'login', 
    'register', 
    'reset-password',
    'terms-of-service',
    'privacy-policy',
    'user-docs',
    'logout'
]);

if ($standalonePage) {
    // Handle redirects for standalone pages
    if (isset($routeInfo['redirect'])) {
        header("Location: " . $routeInfo['redirect']);
        exit();
    }
    
    // Include the standalone page directly without HTML wrapper
    if ($routeInfo['file']) {
        include $routeInfo['file'];
    }
    exit();
}

// Handle redirects
if (isset($routeInfo['redirect'])) {
    header("Location: " . $routeInfo['redirect']);
    exit();
}

// Extract route information
$page = $routeInfo['page'];
$page_file = $routeInfo['file'];
$page_title = $routeInfo['title'];
$page_css = $routeInfo['css'];
$page_js = $routeInfo['js'];
$auth_required = $routeInfo['auth_required'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <?php 
  // Include page-specific CSS files
  foreach($page_css as $css_file): 
  ?>
  <link rel="stylesheet" href="assets/css/<?php echo $css_file; ?>">
  <?php endforeach; ?>
  <!-- Favicon -->
  <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>
<body>
    
  
  <?php if($auth_required && isset($_SESSION['user_id'])): ?>
      <!-- New Sidebar Layout -->
      <?php include '../includes/sidebar-layout.php'; ?>
  <?php else: ?>
  <div class="container mt-4">
      <?php
      // Include the page content for non-authenticated users
      include $page_file;
      ?>
  </div>
  <?php endif; ?>
  
  <?php include '../includes/workly.php'; ?>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Custom JS -->
  <script src="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/js/script.js"></script>
  <!-- Help System JS -->
  <script src="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/js/help-system.js"></script>
  <?php 
  // Include page-specific JS files
  foreach($page_js as $js_file): 
  ?>
  <script src="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/js/<?php echo $js_file; ?>"></script>
  <?php endforeach; ?>
</body>
</html>

