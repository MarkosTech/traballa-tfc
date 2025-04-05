<?php
/**
 * Traballa - Core Functions
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
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 */

if (!defined('INDEX_EXEC')) {
    exit('Direct access not allowed.');
}

// Function to get session object safely
function getSessionObject() {
    global $session, $pdo;
    
    // If session is already initialized, return it
    if (isset($session) && $session instanceof Session) {
        return $session;
    }
    
    // Otherwise, check if we can initialize it
    if (isset($pdo)) {
        require_once __DIR__ . '/Session.php';
        $session = new Session($pdo);
        return $session;
    }
    
    return null;
}

function sanitize($pdo, $data) {
  return htmlspecialchars(trim($data));
}

// Function to get SMTP settings from config
function getSMTPSettings() {
    return [
        'enabled' => defined('SMTP_ENABLED') ? SMTP_ENABLED : false,
        'host' => defined('SMTP_HOST') ? SMTP_HOST : '',
        'port' => defined('SMTP_PORT') ? SMTP_PORT : 587,
        'username' => defined('SMTP_USERNAME') ? SMTP_USERNAME : '',
        'password' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '',
        'encryption' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'tls',
        'from_email' => defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : '',
        'from_name' => defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Traballa'
    ];
}

// Function to check user role
function isAdmin() {
  $session = getSessionObject();
  if (!$session) return false;
  return $session->get('user_role') === 'admin';
}

function isProjectManager() {
  $session = getSessionObject();
  if (!$session) return false;
  return $session->get('user_role') === 'user';
}

function isEmployee() {
  $session = getSessionObject();
  if (!$session) return false;
  return $session->get('user_role') === 'employee';
}

// Function to check if user has management permissions (admin or project manager)
function hasManagementPermissions() {
  return isAdmin() || isProjectManager();
}