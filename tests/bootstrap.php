<?php
/**
 * PHPUnit Bootstrap File
 * 
 * This file is executed before each test run to set up the environment
 */

// Prevent direct access checks in included files
if (!defined('INDEX_EXEC')) {
    define('INDEX_EXEC', true);
}
if (!defined('PHPUNIT_TESTSUITE')) {
    define('PHPUNIT_TESTSUITE', true);
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');

// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Mock $_SERVER variables for testing
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = 'off';
}

// Start output buffering to catch any unwanted output
ob_start();
