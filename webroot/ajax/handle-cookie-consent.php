<?php
/**
 * Traballa - Cookie Consent AJAX Handler
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 */

// Define execution constant to prevent direct access to included files
define('INDEX_EXEC', true);

// Include necessary files
require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';
require_once dirname(dirname(dirname(__FILE__))) . '/includes/Session.php';
require_once dirname(dirname(dirname(__FILE__))) . '/includes/GdprManager.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Make sure this is a POST request with JSON content
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$postData = file_get_contents('php://input');
$requestData = json_decode($postData, true);

if (!$requestData || !isset($requestData['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

$action = $requestData['action'];
$response = ['success' => false];

// Process cookie consent based on action
if ($action === 'accept_all') {
    // User accepted all cookies
    $cookieConsent = [
        'essential' => true,
        'functional' => true,
        'analytics' => true,
        'marketing' => true,
        'timestamp' => time(),
        'version' => '1.0'
    ];
    $response['success'] = true;
} elseif ($action === 'save_preferences') {
    // User saved specific preferences
    $cookieConsent = [
        'essential' => true, // Essential cookies are always required
        'functional' => isset($requestData['preferences']['functional']) ? true : false,
        'analytics' => isset($requestData['preferences']['analytics']) ? true : false,
        'marketing' => isset($requestData['preferences']['marketing']) ? true : false,
        'timestamp' => time(),
        'version' => '1.0'
    ];
    $response['success'] = true;
} elseif ($action === 'reject_all') {
    // User rejected all optional cookies
    $cookieConsent = [
        'essential' => true, // Essential cookies are always required
        'functional' => false,
        'analytics' => false,
        'marketing' => false,
        'timestamp' => time(),
        'version' => '1.0'
    ];
    $response['success'] = true;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// Save consent in a cookie for 6 months (15552000 seconds)
setcookie('cookie_consent', json_encode($cookieConsent), time() + 15552000, '/', '', isset($_SERVER['HTTPS']), true);

// Add cookie data to the response
$response['cookieConsent'] = $cookieConsent;

// Send response
echo json_encode($response);
exit;
