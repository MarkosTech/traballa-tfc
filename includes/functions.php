<?php
/**
 * Traballa - Core functions
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

// Function to sanitize user input
function sanitize($data) {
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Enhanced XSS protection functions
 */

// Sanitize for HTML output with proper encoding
function sanitize_output($data, $allow_html = false) {
    if ($data === null || $data === '') {
        return '';
    }
    
    $data = trim($data);
    
    if ($allow_html) {
        // Allow only safe HTML tags and remove dangerous attributes
        return filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Sanitize for HTML attributes
function sanitize_attribute($data) {
    if ($data === null || $data === '') {
        return '';
    }
    return htmlspecialchars(trim($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Sanitize for JavaScript output
function sanitize_js($data) {
    if ($data === null || $data === '') {
        return '';
    }
    return json_encode(trim($data), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

// Sanitize URLs
function sanitize_url($url) {
    if ($url === null || $url === '') {
        return '';
    }
    return filter_var(trim($url), FILTER_SANITIZE_URL);
}

// Validate and sanitize email
function sanitize_email($email) {
    if ($email === null || $email === '') {
        return '';
    }
    $email = trim($email);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
}

// Deep sanitize arrays (for form data)
function sanitize_array($data) {
    if (!is_array($data)) {
        return sanitize($data);
    }
    
    $sanitized = [];
    foreach ($data as $key => $value) {
        $clean_key = sanitize($key);
        if (is_array($value)) {
            $sanitized[$clean_key] = sanitize_array($value);
        } else {
            $sanitized[$clean_key] = sanitize($value);
        }
    }
    return $sanitized;
}

// Strip dangerous tags and attributes
function strip_dangerous_tags($data) {
    if ($data === null || $data === '') {
        return '';
    }
    
    // Remove script tags and other dangerous elements
    $dangerous_tags = [
        'script', 'iframe', 'object', 'embed', 'applet', 'meta', 'link',
        'style', 'form', 'input', 'button', 'textarea', 'select', 'option'
    ];
    
    foreach ($dangerous_tags as $tag) {
        $data = preg_replace('/<' . $tag . '[^>]*>.*?<\/' . $tag . '>/si', '', $data);
        $data = preg_replace('/<' . $tag . '[^>]*\/>/si', '', $data);
        $data = preg_replace('/<' . $tag . '[^>]*>/si', '', $data);
    }
    
    // Remove dangerous attributes
    $data = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $data);
    $data = preg_replace('/\s*javascript\s*:/i', '', $data);
    $data = preg_replace('/\s*vbscript\s*:/i', '', $data);
    $data = preg_replace('/\s*data\s*:/i', '', $data);
    
    return $data;
}

/**
 * CSRF Protection Functions
 */

// Generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Generate CSRF hidden input field
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . sanitize_attribute($token) . '">';
}

// Validate CSRF for forms
function check_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
            die('CSRF token mismatch. Please refresh the page and try again.');
        }
    }
}

/**
 * Content Security Policy Helper
 */
function set_security_headers() {
    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Content Type Options
    header('X-Content-Type-Options: nosniff');
    
    // Frame Options
    header('X-Frame-Options: SAMEORIGIN');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy
    $csp = "default-src 'self'; " .
           "script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com 'unsafe-inline'; " .
           "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com 'unsafe-inline'; " .
           "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
           "img-src 'self' data: https:; " .
           "connect-src 'self'; " .
           "frame-ancestors 'self'; " .
           "base-uri 'self'; " .
           "form-action 'self';";
    
    header('Content-Security-Policy: ' . $csp);
}

/**
 * Generate dashboard redirect URL based on SYSTEM_URL configuration
 * 
 * @param string $fallback_url Default URL to use if SYSTEM_URL is not defined or is 'none'
 * @return string The redirect URL to use
 */
function getDashboardUrl($fallback_url = 'index.php') {
    // Check if SYSTEM_URL is defined and not 'none'
    if (defined('SYSTEM_URL') && SYSTEM_URL !== 'none' && !empty(trim(SYSTEM_URL))) {
        $system_url = trim(SYSTEM_URL);
        
        // Add protocol if not present
        if (!preg_match('/^https?:\/\//', $system_url)) {
            // Use HTTPS by default for security
            $system_url = 'https://' . $system_url;
        }
        
        return $system_url;
    }
    
    // Return fallback URL if SYSTEM_URL is not configured or is 'none'
    return $fallback_url;
}

/**
 * Generate login redirect URL based on SYSTEM_URL configuration
 * 
 * @param string $fallback_url Default URL to use if SYSTEM_URL is not defined or is 'none'
 * @return string The login URL to use
 */
function getLoginUrl($fallback_url = 'login.php') {
    // Check if SYSTEM_URL is defined and not 'none'
    if (defined('SYSTEM_URL') && SYSTEM_URL !== 'none' && !empty(trim(SYSTEM_URL))) {
        $system_url = trim(SYSTEM_URL);
        
        // Add protocol if not present
        if (!preg_match('/^https?:\/\//', $system_url)) {
            // Use HTTPS by default for security
            $system_url = 'https://' . $system_url;
        }
        
        // Append login.php to the system URL
        return rtrim($system_url, '/') . '/login.php';
    }
    
    // Return fallback URL if SYSTEM_URL is not configured or is 'none'
    return $fallback_url;
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
        'from_name' => defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Traballa Counter'
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

// Function to get user details by ID
function getUserById($pdo, $user_id) {
  $user_id = (int)$user_id;
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
}

// Function to format timestamp
function formatDateTime($timestamp) {
  return date('M d, Y h:i A', strtotime($timestamp));
}

// Function to calculate hours between two timestamps
function calculateHours($clock_in, $clock_out) {
  $start = strtotime($clock_in);
  $end = strtotime($clock_out);
  $diff = $end - $start;
  return round($diff / 3600, 2); // Convert seconds to hours with 2 decimal places
}

// Function to get current work status
function getCurrentWorkStatus($pdo, $user_id) {
  $user_id = (int)$user_id;
  $stmt = $pdo->prepare("SELECT wh.*, p.name as project_name, o.name as organization_name
            FROM work_hours wh 
            JOIN projects p ON wh.project_id = p.id 
            JOIN organizations o ON p.organization_id = o.id
            WHERE wh.user_id = ? AND wh.status = 'working' 
            ORDER BY wh.id DESC LIMIT 1");
  $stmt->execute([$user_id]);
  
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
}

// Function to get user work hours for a specific user
function getUserWorkHours($pdo, $user_id, $start_date = null, $end_date = null, $project_id = null, $organization_id = null) {
  $user_id = (int)$user_id;
  
  $query = "SELECT wh.*, p.name as project_name, o.name as organization_name
            FROM work_hours wh 
            JOIN projects p ON wh.project_id = p.id 
            JOIN organizations o ON p.organization_id = o.id
            WHERE wh.user_id = ?";
  
  $params = [$user_id];
  
  if ($start_date && $end_date) {
      $query .= " AND DATE(wh.clock_in) BETWEEN ? AND ?";
      $params[] = $start_date;
      $params[] = $end_date;
  }
  
  if ($project_id) {
      $project_id = (int)$project_id;
      $query .= " AND wh.project_id = ?";
      $params[] = $project_id;
  }
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " AND p.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $query .= " ORDER BY wh.clock_in DESC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all users (for admin)
function getAllUsers($pdo) {
  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY name");
  $stmt->execute();
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get work summary for dashboard
function getWorkSummary($pdo, $user_id, $period = 'week', $project_id = null, $organization_id = null) {
  $user_id = (int)$user_id;
  
  switch ($period) {
      case 'today':
          $date_filter = "DATE(wh.clock_in) = CURDATE()";
          break;
      case 'week':
          $date_filter = "YEARWEEK(wh.clock_in, 1) = YEARWEEK(CURDATE(), 1)";
          break;
      case 'month':
          $date_filter = "MONTH(wh.clock_in) = MONTH(CURDATE()) AND YEAR(wh.clock_in) = YEAR(CURDATE())";
          break;
      default:
          $date_filter = "DATE(wh.clock_in) = CURDATE()";
  }
  
  $query = "SELECT SUM(wh.total_hours) as total_hours 
            FROM work_hours wh 
            JOIN projects p ON wh.project_id = p.id
            WHERE wh.user_id = ? AND wh.status = 'completed' AND $date_filter";
  
  $params = [$user_id];
  
  if ($project_id) {
      $project_id = (int)$project_id;
      $query .= " AND wh.project_id = ?";
      $params[] = $project_id;
  }
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " AND p.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['total_hours'] ? $result['total_hours'] : 0;
}

// Function to get all projects
function getAllProjects($pdo, $organization_id = null) {
  $query = "SELECT p.*, o.name as organization_name 
            FROM projects p
            JOIN organizations o ON p.organization_id = o.id";
  
  $params = [];
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " WHERE p.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $query .= " ORDER BY p.name";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get project by ID
function getProjectById($pdo, $project_id) {
  $project_id = (int)$project_id;
  $stmt = $pdo->prepare("SELECT p.*, o.name as organization_name 
            FROM projects p
            JOIN organizations o ON p.organization_id = o.id
            WHERE p.id = ?");
  $stmt->execute([$project_id]);
  
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
}

// Function to get projects for a specific user
function getUserProjects($pdo, $user_id, $organization_id = null) {
  $user_id = (int)$user_id;
  
  $query = "SELECT p.*, o.name as organization_name, pm.is_manager 
            FROM projects p 
            JOIN project_members pm ON p.id = pm.project_id 
            JOIN organizations o ON p.organization_id = o.id
            WHERE pm.user_id = ?";
  
  $params = [$user_id];
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " AND p.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $query .= " ORDER BY p.name";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to check if user is a member of a project
function isProjectMember($pdo, $user_id, $project_id) {
  $user_id = (int)$user_id;
  $project_id = (int)$project_id;
  
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM project_members WHERE user_id = ? AND project_id = ?");
  $stmt->execute([$user_id, $project_id]);
  
  return $stmt->fetchColumn() > 0;
}

// Function to check if user is a manager of a project
function isProjectManagerOf($pdo, $user_id, $project_id) {
  $user_id = (int)$user_id;
  $project_id = (int)$project_id;
  
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM project_members WHERE user_id = ? AND project_id = ? AND is_manager = 1");
  $stmt->execute([$user_id, $project_id]);
  
  return $stmt->fetchColumn() > 0;
}

// Function to get project members
function getProjectMembers($pdo, $project_id) {
  $project_id = (int)$project_id;
  
  $stmt = $pdo->prepare("SELECT u.*, pm.is_manager 
            FROM users u 
            JOIN project_members pm ON u.id = pm.user_id 
            WHERE pm.project_id = ? 
            ORDER BY pm.is_manager DESC, u.name ASC");
  $stmt->execute([$project_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get project managers
function getProjectManagers($pdo, $project_id) {
  $project_id = (int)$project_id;
  
  $stmt = $pdo->prepare("SELECT u.* 
            FROM users u 
            JOIN project_members pm ON u.id = pm.user_id 
            WHERE pm.project_id = ? AND pm.is_manager = 1 
            ORDER BY u.name");
  $stmt->execute([$project_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get project statistics
function getProjectStatistics($pdo, $project_id) {
  $project_id = (int)$project_id;
  
  // Total hours
  $stmt = $pdo->prepare("SELECT SUM(total_hours) as total_hours FROM work_hours 
            WHERE project_id = ? AND status = 'completed'");
  $stmt->execute([$project_id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $total_hours = $result['total_hours'] ? $result['total_hours'] : 0;
  
  // Member count
  $stmt = $pdo->prepare("SELECT COUNT(*) as member_count FROM project_members WHERE project_id = ?");
  $stmt->execute([$project_id]);
  $member_count = $stmt->fetchColumn();
  
  // Active sessions
  $stmt = $pdo->prepare("SELECT COUNT(*) as active_sessions FROM work_hours 
            WHERE project_id = ? AND status = 'working'");
  $stmt->execute([$project_id]);
  $active_sessions = $stmt->fetchColumn();
  
  return [
      'total_hours' => $total_hours,
      'member_count' => $member_count,
      'active_sessions' => $active_sessions
  ];
}

// Function to get non-project members (for adding to project)
function getNonProjectMembers($pdo, $project_id, $organization_id = null) {
  $project_id = (int)$project_id;
  
  $query = "SELECT u.* FROM users u";
  $params = [$project_id];
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " JOIN organization_members om ON u.id = om.user_id AND om.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $query .= " WHERE u.id NOT IN (
                SELECT user_id FROM project_members WHERE project_id = ?
            ) 
            ORDER BY u.name";
  
  // Reorder params: organization_id first if exists, then project_id
  if ($organization_id) {
      $params = [$organization_id, $project_id];
  } else {
      $params = [$project_id];
  }
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to check if user can access a project
function canAccessProject($pdo, $user_id, $project_id) {
  // Admins can access all projects
  if (isAdmin()) {
      return true;
  }
  
  // Project managers and employees can only access projects they are members of
  return isProjectMember($pdo, $user_id, $project_id);
}

// Function to get user's active projects (for clock-in)
function getUserActiveProjects($pdo, $user_id, $organization_id = null) {
  $user_id = (int)$user_id;
  
  $query = "SELECT p.*, o.name as organization_name, pm.is_manager 
            FROM projects p 
            JOIN project_members pm ON p.id = pm.project_id 
            JOIN organizations o ON p.organization_id = o.id
            WHERE pm.user_id = ? AND p.status = 'active'";
  
  $params = [$user_id];
  
  if ($organization_id) {
      $organization_id = (int)$organization_id;
      $query .= " AND p.organization_id = ?";
      $params[] = $organization_id;
  }
  
  $query .= " ORDER BY o.name, p.name";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get all organizations
function getAllOrganizations($pdo) {
  $stmt = $pdo->prepare("SELECT * FROM organizations ORDER BY name");
  $stmt->execute();
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get organization by ID
function getOrganizationById($pdo, $organization_id) {
  $organization_id = (int)$organization_id;
  $stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = ?");
  $stmt->execute([$organization_id]);
  
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
}

// Function to get user's organizations
function getUserOrganizations($pdo, $user_id) {
  $user_id = (int)$user_id;
  $stmt = $pdo->prepare("SELECT o.*, om.is_admin 
            FROM organizations o 
            JOIN organization_members om ON o.id = om.organization_id 
            WHERE om.user_id = ? 
            ORDER BY o.name");
  $stmt->execute([$user_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrganizationProjects($pdo, $organization_id) {
  $organization_id = (int)$organization_id;
  $stmt = $pdo->prepare("SELECT * FROM projects WHERE organization_id = ? ORDER BY name");
  $stmt->execute([$organization_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to check if user is a member of an organization
function isOrganizationMember($pdo, $user_id, $organization_id) {
  $user_id = (int)$user_id;
  $organization_id = (int)$organization_id;
  
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM organization_members WHERE user_id = ? AND organization_id = ?");
  $stmt->execute([$user_id, $organization_id]);
  
  return $stmt->fetchColumn() > 0;
}

// Function to check if user is an admin of an organization
function isOrganizationAdmin($pdo, $user_id, $organization_id) {
  $user_id = (int)$user_id;
  $organization_id = (int)$organization_id;
  
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM organization_members WHERE user_id = ? AND organization_id = ? AND is_admin = 1");
  $stmt->execute([$user_id, $organization_id]);
  
  return $stmt->fetchColumn() > 0;
}

// Function to get organization members
function getOrganizationMembers($pdo, $organization_id) {
  $organization_id = (int)$organization_id;
  
  $stmt = $pdo->prepare("SELECT u.*, om.is_admin, u.role
            FROM users u 
            JOIN organization_members om ON u.id = om.user_id 
            WHERE om.organization_id = ? 
            ORDER BY om.is_admin DESC, u.name ASC");
  $stmt->execute([$organization_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get organization statistics
function getOrganizationStatistics($pdo, $organization_id) {
  $organization_id = (int)$organization_id;
  
  // Total hours
  $stmt = $pdo->prepare("SELECT SUM(wh.total_hours) as total_hours 
            FROM work_hours wh 
            JOIN projects p ON wh.project_id = p.id 
            WHERE p.organization_id = ? AND wh.status = 'completed'");
  $stmt->execute([$organization_id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $total_hours = $result['total_hours'] ? $result['total_hours'] : 0;
  
  // Member count
  $stmt = $pdo->prepare("SELECT COUNT(*) as member_count FROM organization_members WHERE organization_id = ?");
  $stmt->execute([$organization_id]);
  $member_count = $stmt->fetchColumn();
  
  // Project count
  $stmt = $pdo->prepare("SELECT COUNT(*) as project_count FROM projects WHERE organization_id = ?");
  $stmt->execute([$organization_id]);
  $project_count = $stmt->fetchColumn();
  
  // Active sessions
  $stmt = $pdo->prepare("SELECT COUNT(*) as active_sessions 
            FROM work_hours wh 
            JOIN projects p ON wh.project_id = p.id 
            WHERE p.organization_id = ? AND wh.status = 'working'");
  $stmt->execute([$organization_id]);
  $active_sessions = $stmt->fetchColumn();
  
  return [
      'total_hours' => $total_hours,
      'member_count' => $member_count,
      'project_count' => $project_count,
      'active_sessions' => $active_sessions
  ];
}

// Function to check if user can access an organization
function canAccessOrganization($pdo, $user_id, $organization_id) {
  // Admins can access all organizations
  if (isAdmin()) {
      return true;
  }
  
  // Project managers and employees can only access organizations they are members of
  return isOrganizationMember($pdo, $user_id, $organization_id);
}

// Function to get non-organization members (for adding to organization)
function getNonOrganizationMembers($pdo, $organization_id) {
  $organization_id = (int)$organization_id;
  
  $stmt = $pdo->prepare("SELECT * FROM users 
            WHERE id NOT IN (
                SELECT user_id FROM organization_members WHERE organization_id = ?
            ) 
            ORDER BY name");
  $stmt->execute([$organization_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kanban Functions
function getKanbanColumns($pdo, $project_id) {
  $project_id = (int)$project_id;
  
  $stmt = $pdo->prepare("SELECT * FROM kanban_columns 
            WHERE project_id = ? 
            ORDER BY position ASC");
  $stmt->execute([$project_id]);
  
  $columns = [];
  while ($column = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // Get tasks for this column
      $column['tasks'] = getKanbanTasks($pdo, $column['id']);
      $columns[] = $column;
  }
  
  return $columns;
}

function getKanbanTasks($pdo, $column_id) {
  $column_id = (int)$column_id;
  
  $stmt = $pdo->prepare("SELECT * FROM kanban_tasks 
            WHERE column_id = ? 
            ORDER BY position ASC");
  $stmt->execute([$column_id]);
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createDefaultKanbanColumns($pdo, $project_id) {
  $project_id = (int)$project_id;
  
  $default_columns = [
      ['name' => 'Por hacer', 'position' => 0],
      ['name' => 'En progreso', 'position' => 1],
      ['name' => 'Completado', 'position' => 2]
  ];
  
  $stmt = $pdo->prepare("INSERT INTO kanban_columns (project_id, name, position) VALUES (?, ?, ?)");
  
  foreach ($default_columns as $column) {
      $stmt->execute([$project_id, $column['name'], $column['position']]);
  }
  
  return true;
}

function addKanbanColumn($pdo, $project_id, $name) {
  $project_id = (int)$project_id;
  
  // Get the highest position
  $stmt = $pdo->prepare("SELECT MAX(position) as max_pos FROM kanban_columns WHERE project_id = ?");
  $stmt->execute([$project_id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $position = $result['max_pos'] ? $result['max_pos'] + 1 : 0;
  
  $stmt = $pdo->prepare("INSERT INTO kanban_columns (project_id, name, position) VALUES (?, ?, ?)");
  return $stmt->execute([$project_id, $name, $position]);
}

function updateKanbanColumn($pdo, $column_id, $name) {
  $column_id = (int)$column_id;
  
  $stmt = $pdo->prepare("UPDATE kanban_columns SET name = ? WHERE id = ?");
  return $stmt->execute([$name, $column_id]);
}

function deleteKanbanColumn($pdo, $column_id) {
  $column_id = (int)$column_id;
  
  // Tasks will be deleted automatically due to ON DELETE CASCADE constraint
  $stmt = $pdo->prepare("DELETE FROM kanban_columns WHERE id = ?");
  return $stmt->execute([$column_id]);
}

function addKanbanTask($pdo, $column_id, $project_id, $title, $created_by, $description = '', $assigned_to = null, $due_date = null, $status = 'active') {
    // Get the position for the new task (will be added at the end)
    $stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 as next_position FROM kanban_tasks WHERE column_id = ?");
    $stmt->execute([$column_id]);
    $position = $stmt->fetchColumn();

    // Default status to active if invalid
    if (!in_array($status, ['active', 'pending', 'completed'])) {
        $status = 'active';
    }

    $stmt = $pdo->prepare("INSERT INTO kanban_tasks (column_id, project_id, title, description, created_by, assigned_to, due_date, position, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    $success = $stmt->execute([$column_id, $project_id, $title, $description, $created_by, $assigned_to, $due_date, $position, $status]);
    
    if ($success) {
        // Update the last modified timestamp for the project
        $stmt = $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?");
        $stmt->execute([$project_id]);
    }
    
    return $success;
}

function updateKanbanTask($pdo, $task_id, $title, $description = '', $assigned_to = null, $due_date = null, $status = 'active') {
    // Default status to active if invalid
    if (!in_array($status, ['active', 'pending', 'completed'])) {
        $status = 'active';
    }
    
    $stmt = $pdo->prepare("UPDATE kanban_tasks SET title = ?, description = ?, assigned_to = ?, due_date = ?, status = ?, updated_at = NOW() WHERE id = ?");
    
    return $stmt->execute([$title, $description, $assigned_to, $due_date, $status, $task_id]);
}

function deleteKanbanTask($pdo, $task_id) {
  $task_id = (int)$task_id;
  
  $stmt = $pdo->prepare("DELETE FROM kanban_tasks WHERE id = ?");
  return $stmt->execute([$task_id]);
}

// Admin Dashboard Helper Functions

// Function to get total users count
function getTotalUsers($pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'];
}

// Function to get total organizations count
function getTotalOrganizations($pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM organizations");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'];
}

// Function to get users active today (users who have clocked in today)
function getActiveUsersToday($pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT user_id) as total FROM work_hours WHERE DATE(clock_in) = CURDATE()");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'];
}

// Function to get total Traballa today across all users
function getTotalTraballaToday($pdo) {
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total FROM work_hours WHERE DATE(clock_in) = CURDATE() AND status = 'completed'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return round($result['total'], 2);
}

// Function to get recent user registrations
function getRecentRegistrations($pdo, $limit = 5) {
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get system activity (recent Traballa)
function getSystemActivity($pdo, $limit = 10) {
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT wh.*, u.name as user_name, p.name as project_name, o.name as organization_name
              FROM work_hours wh
              JOIN users u ON wh.user_id = u.id
              JOIN projects p ON wh.project_id = p.id
              JOIN organizations o ON p.organization_id = o.id
              ORDER BY wh.clock_in DESC
              LIMIT ?");
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get recent users with their activity
function getRecentUsersWithActivity($pdo, $limit = 10) {
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT u.id, u.name, u.email, u.role, u.created_at,
                     COALESCE(SUM(wh.total_hours), 0) as total_hours,
                     COUNT(wh.id) as work_sessions,
                     MAX(wh.clock_in) as last_activity
              FROM users u
              LEFT JOIN work_hours wh ON u.id = wh.user_id AND wh.status = 'completed'
              GROUP BY u.id, u.name, u.email, u.role, u.created_at
              ORDER BY u.created_at DESC
              LIMIT ?");
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get user statistics for dashboard
function getUserStatistics($pdo, $user_id) {
    $user_id = (int)$user_id;
    
    $stats = [
        'total_hours' => 0,
        'today_hours' => 0,
        'week_hours' => 0,
        'month_hours' => 0,
        'total_sessions' => 0,
        'projects_count' => 0
    ];
    
    // Total hours
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total FROM work_hours WHERE user_id = ? AND status = 'completed'");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['total_hours'] = round($result['total'], 2);
    
    // Today's hours
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total FROM work_hours WHERE user_id = ? AND status = 'completed' AND DATE(clock_in) = CURDATE()");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['today_hours'] = round($result['total'], 2);
    
    // Week's hours
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total FROM work_hours WHERE user_id = ? AND status = 'completed' AND YEARWEEK(clock_in, 1) = YEARWEEK(CURDATE(), 1)");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['week_hours'] = round($result['total'], 2);
    
    // Month's hours
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total FROM work_hours WHERE user_id = ? AND status = 'completed' AND MONTH(clock_in) = MONTH(CURDATE()) AND YEAR(clock_in) = YEAR(CURDATE())");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['month_hours'] = round($result['total'], 2);
    
    // Total sessions
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM work_hours WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['total_sessions'] = $result['total'];
    
    // Projects count
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT project_id) as total FROM project_members WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['projects_count'] = $result['total'];
    
    return $stats;
}

// Kanban Tabs Functions
function getKanbanTabs($pdo, $project_id) {
    $project_id = (int)$project_id;
    
    $stmt = $pdo->prepare("SELECT * FROM kanban_tabs 
              WHERE project_id = ? 
              ORDER BY position ASC");
    $stmt->execute([$project_id]);
    
    $tabs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no tabs exist, create default "Main" tab
    if (empty($tabs)) {
        createDefaultKanbanTab($pdo, $project_id);
        return getKanbanTabs($pdo, $project_id);
    }
    
    return $tabs;
}

function createDefaultKanbanTab($pdo, $project_id) {
    $project_id = (int)$project_id;
    
    $stmt = $pdo->prepare("INSERT INTO kanban_tabs (project_id, name, position, is_default) VALUES (?, 'Main', 0, TRUE)");
    
    if ($stmt->execute([$project_id])) {
        return $pdo->lastInsertId();
    }
    
    return false;
}

function addKanbanTab($pdo, $project_id, $name) {
    $project_id = (int)$project_id;
    
    // Get the highest position
    $stmt = $pdo->prepare("SELECT MAX(position) as max_pos FROM kanban_tabs WHERE project_id = ?");
    $stmt->execute([$project_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $position = $result['max_pos'] ? $result['max_pos'] + 1 : 0;
    
    $stmt = $pdo->prepare("INSERT INTO kanban_tabs (project_id, name, position, is_default) VALUES (?, ?, ?, FALSE)");
    
    if ($stmt->execute([$project_id, $name, $position])) {
        return $pdo->lastInsertId();
    }
    
    return false;
}

function updateKanbanTab($pdo, $tab_id, $name) {
    $tab_id = (int)$tab_id;
    
    // Don't allow changing the name of the default tab
    $stmt = $pdo->prepare("UPDATE kanban_tabs SET name = ? WHERE id = ? AND is_default = FALSE");
    
    return $stmt->execute([$name, $tab_id]);
}

function deleteKanbanTab($pdo, $tab_id) {
    $tab_id = (int)$tab_id;
    
    // Don't allow deleting the default tab
    $stmt = $pdo->prepare("DELETE FROM kanban_tabs WHERE id = ? AND is_default = FALSE");
    
    return $stmt->execute([$tab_id]);
}

function getKanbanColumnsWithTabs($pdo, $project_id, $tab_id = null) {
    $project_id = (int)$project_id;
    
    if ($tab_id === null) {
        // Get default tab
        $stmt = $pdo->prepare("SELECT id FROM kanban_tabs WHERE project_id = ? AND is_default = TRUE LIMIT 1");
        $stmt->execute([$project_id]);
        $tab = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tab) {
            $tab_id = $tab['id'];
        } else {
            // Create default tab if it doesn't exist
            $tab_id = createDefaultKanbanTab($pdo, $project_id);
        }
    }
    
    $tab_id = (int)$tab_id;
    
    $stmt = $pdo->prepare("SELECT * FROM kanban_columns 
              WHERE project_id = ? AND tab_id = ?
              ORDER BY position ASC");
    $stmt->execute([$project_id, $tab_id]);
    
    $columns = [];
    while ($column = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Get tasks for this column
        $column['tasks'] = getKanbanTasksForTab($pdo, $column['id'], $tab_id);
        $columns[] = $column;
    }
    
    return $columns;
}

function getKanbanTasksForTab($pdo, $column_id, $tab_id) {
    $column_id = (int)$column_id;
    $tab_id = (int)$tab_id;
    
    $stmt = $pdo->prepare("SELECT * FROM kanban_tasks 
              WHERE column_id = ? AND tab_id = ?
              ORDER BY position ASC");
    $stmt->execute([$column_id, $tab_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addKanbanColumnWithTab($pdo, $project_id, $tab_id, $name) {
    $project_id = (int)$project_id;
    $tab_id = (int)$tab_id;
    
    // Get the highest position for this tab
    $stmt = $pdo->prepare("SELECT MAX(position) as max_pos FROM kanban_columns 
              WHERE project_id = ? AND tab_id = ?");
    $stmt->execute([$project_id, $tab_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $position = $result['max_pos'] ? $result['max_pos'] + 1 : 0;
    
    $stmt = $pdo->prepare("INSERT INTO kanban_columns (project_id, tab_id, name, position) VALUES (?, ?, ?, ?)");
    
    return $stmt->execute([$project_id, $tab_id, $name, $position]);
}

function addKanbanTaskWithTab($pdo, $column_id, $project_id, $tab_id, $title, $created_by, $description = '', $assigned_to = null, $due_date = null, $status = 'active') {
    $column_id = (int)$column_id;
    $project_id = (int)$project_id;
    $tab_id = (int)$tab_id;
    $created_by = (int)$created_by;
    
    // Get the position for the new task (will be added at the end)
    $stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 as next_position 
                           FROM kanban_tasks WHERE column_id = ? AND tab_id = ?");
    $stmt->execute([$column_id, $tab_id]);
    $position = $stmt->fetchColumn();

    // Default status to active if invalid
    if (!in_array($status, ['active', 'pending', 'completed'])) {
        $status = 'active';
    }

    $stmt = $pdo->prepare("INSERT INTO kanban_tasks (column_id, project_id, tab_id, title, description, created_by, assigned_to, due_date, position, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    $success = $stmt->execute([$column_id, $project_id, $tab_id, $title, $description, $created_by, $assigned_to, $due_date, $position, $status]);
    
    if ($success) {
        // Update the last modified timestamp for the project
        $stmt = $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?");
        $stmt->execute([$project_id]);
    }
    
    return $success;
}

function createDefaultKanbanColumnsForTab($pdo, $project_id, $tab_id) {
    $project_id = (int)$project_id;
    $tab_id = (int)$tab_id;
    
    $default_columns = [
        ['name' => 'Por hacer', 'position' => 0],
        ['name' => 'En progreso', 'position' => 1],
        ['name' => 'Completado', 'position' => 2]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO kanban_columns (project_id, tab_id, name, position) VALUES (?, ?, ?, ?)");
    
    foreach ($default_columns as $column) {
        $stmt->execute([$project_id, $tab_id, $column['name'], $column['position']]);
    }
    
    return true;
}

