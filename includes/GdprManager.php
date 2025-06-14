<?php
/**
 * Traballa - GDPR Data Management
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * This class manages all GDPR-related functionality:
 * - User data access
 * - Data rectification
 * - Data erasure (right to be forgotten)
 * - Data portability
 * - Processing restrictions
 * - Objections to processing
 */

// Include email sender
require_once __DIR__ . '/EmailSender.php';

class GdprManager {
    private $pdo;
    private $emailSender;
    
    /**
     * Constructor
     * 
     * @param PDO $pdo PDO database connection
     * @param EmailSender $emailSender Optional EmailSender instance
     */
    public function __construct(PDO $pdo, $emailSender = null) {
        $this->pdo = $pdo;
        $this->emailSender = $emailSender ?: new EmailSender();
    }
    
    /**
     * Fetch all user data for GDPR access request (Art. 15)
     * 
     * @param int $userId User ID
     * @return array Associative array with all user data
     */
    public function fetchUserData($userId) {
        $userId = (int)$userId;
        $userData = [];
        
        // Get basic user information
        $userData['personal'] = $this->getUserPersonalData($userId);
        
        // Get user's work data
        $userData['work'] = $this->getUserWorkData($userId);
        
        // Get user's settings and preferences
        $userData['settings'] = $this->getUserSettings($userId);
        
        // Get user's access logs
        $userData['logs'] = $this->getUserAccessLogs($userId);
        
        // Get user's organization memberships
        $userData['organizations'] = $this->getUserOrganizations($userId);
        
        // Get user's project memberships
        $userData['projects'] = $this->getUserProjects($userId);
        
        return $userData;
    }
    
    /**
     * Get user's basic personal information
     * 
     * @param int $userId User ID
     * @return array User personal data
     */
    private function getUserPersonalData($userId) {
        $stmt = $this->pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$userData) {
            return [];
        }
        
        return [
            'id' => $userData['id'],
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'created_at' => $userData['created_at']
        ];
    }
    
    /**
     * Get user's work data
     * 
     * @param int $userId User ID
     * @return array User work data
     */
    private function getUserWorkData($userId) {
        // Get total tracked hours
        $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(total_hours), 0) as total_hours 
                                    FROM work_hours 
                                    WHERE user_id = ? AND status = 'completed'");
        $stmt->execute([$userId]);
        $totalHours = $stmt->fetchColumn();
        
        // Get user's projects
        $stmt = $this->pdo->prepare("SELECT p.id, p.name 
                                    FROM projects p 
                                    JOIN project_members pm ON p.id = pm.project_id 
                                    WHERE pm.user_id = ?");
        $stmt->execute([$userId]);
        $projects = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
        
        // Get tasks count
        $stmt = $this->pdo->prepare("SELECT COUNT(*) 
                                    FROM kanban_tasks 
                                    WHERE assigned_to = ? OR created_by = ?");
        $stmt->execute([$userId, $userId]);
        $tasksCount = $stmt->fetchColumn();
        
        return [
            'tracked_hours' => round($totalHours, 2),
            'projects' => $projects,
            'tasks' => $tasksCount
        ];
    }
    
    /**
     * Get user's settings and preferences
     * 
     * @param int $userId User ID
     * @return array User settings data
     */
    private function getUserSettings($userId) {
        // This is a placeholder - in a real app you'd get this from a user_settings or preferences table
        // For now we'll return some default values
        return [
            'language' => 'es',
            'timezone' => 'Europe/Madrid',
            'notifications' => true
        ];
    }
    
    /**
     * Get user's access logs
     * 
     * @param int $userId User ID
     * @return array Access logs
     */
    private function getUserAccessLogs($userId) {
        // Get access logs from user_sessions table if it exists
        $accessLogs = [];
        
        try {
            $stmt = $this->pdo->prepare("SELECT session_id, created_at 
                                        FROM user_sessions 
                                        WHERE session_data LIKE ? 
                                        ORDER BY created_at DESC LIMIT 10");
            $stmt->execute(['%"user_id";i:' . $userId . '%']);
            
            while ($log = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $accessLogs[] = [
                    'date' => $log['created_at'],
                    'ip' => 'Not stored' // For privacy we might not store IP addresses
                ];
            }
        } catch (PDOException $e) {
            // Table might not exist or have different structure
            $accessLogs = [];
        }
        
        return [
            'access_logs' => $accessLogs
        ];
    }
    
    /**
     * Get user's organization memberships
     * 
     * @param int $userId User ID
     * @return array Organization memberships
     */
    private function getUserOrganizations($userId) {
        $stmt = $this->pdo->prepare("SELECT o.id, o.name, om.is_admin 
                                    FROM organizations o 
                                    JOIN organization_members om ON o.id = om.organization_id 
                                    WHERE om.user_id = ?");
        $stmt->execute([$userId]);
        
        $organizations = [];
        while ($org = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $organizations[] = [
                'id' => $org['id'],
                'name' => $org['name'],
                'role' => $org['is_admin'] ? 'Admin' : 'Member'
            ];
        }
        
        return $organizations;
    }
    
    /**
     * Get user's project memberships
     * 
     * @param int $userId User ID
     * @return array Project memberships
     */
    private function getUserProjects($userId) {
        $stmt = $this->pdo->prepare("SELECT p.id, p.name, pm.is_manager 
                                    FROM projects p 
                                    JOIN project_members pm ON p.id = pm.project_id 
                                    WHERE pm.user_id = ?");
        $stmt->execute([$userId]);
        
        $projects = [];
        while ($proj = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = [
                'id' => $proj['id'],
                'name' => $proj['name'],
                'role' => $proj['is_manager'] ? 'Manager' : 'Member'
            ];
        }
        
        return $projects;
    }
    
    /**
     * Update user data (Art. 16 - Right to rectification)
     * 
     * @param int $userId User ID
     * @param array $fields Fields to update
     * @return bool Success status
     */
    public function updateUserData($userId, $fields) {
        if (empty($fields)) {
            return false;
        }
        
        $userId = (int)$userId;
        $allowedFields = ['name', 'email', 'language', 'timezone', 'notifications'];
        $updates = [];
        $params = [];
        
        foreach ($fields as $field => $value) {
            // Only allow updating specific fields
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        // Add the user ID to the parameters
        $params[] = $userId;
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Initialize data deletion process (Art. 17 - Right to be forgotten)
     * 
     * @param int $userId User ID
     * @param string $reason Optional reason for deletion
     * @return bool Success status
     */
    public function initiateDataDeletion($userId, $reason = '') {
        $userId = (int)$userId;
        
        try {
            // Get user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return false;
            }
            
            // Instead of storing in a table, flag the user for deletion in the session
            // In a real system, you might want to add a field to the users table for this purpose
            $_SESSION['deletion_request'] = [
                'user_id' => $userId,
                'requested_at' => date('Y-m-d H:i:s'),
                'scheduled_deletion' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'reason' => $reason,
                'status' => 'pending'
            ];
            
            // Send email notification about deletion request (to both user and admin)
            $userEmail = $user['email'];
            $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';
            $subject = 'Account Deletion Request - Traballa';
            
            // Variables for the email template
            $userName = $user['name'];
            
            // Use email templates if they exist
            $templatePath = __DIR__ . '/../templates/emails/deletion_request.php';
            $htmlTemplatePath = __DIR__ . '/../templates/emails/deletion_request_html.php';
            
            if (file_exists($templatePath)) {
                // Capture plain text email content
                ob_start();
                include $templatePath;
                $message = ob_get_clean();
                
                // Capture HTML email content if available
                $htmlMessage = null;
                if (file_exists($htmlTemplatePath)) {
                    ob_start();
                    include $htmlTemplatePath;
                    $htmlMessage = ob_get_clean();
                }
                
                // Send email with the templates
                $this->sendEmail($userEmail, $subject, $message, $htmlMessage);
            } else {
                // Fallback to basic email if templates don't exist
                $message = "Dear {$user['name']},\n\n";
                $message .= "We have received your request to delete your Traballa account. Your account is scheduled for deletion on " . date('Y-m-d', strtotime('+30 days')) . ".\n\n";
                $message .= "If this was not requested by you, please log in to cancel this request or contact support immediately.\n\n";
                $message .= "Thank you for using Traballa.";
                
                $this->sendEmail($userEmail, $subject, $message);
            }
            
            // Log the action for auditing purposes
            $this->logGdprAction($userId, 'erasure_request', 'User requested account deletion');
            
            return true;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Log an erasure request (for audit trail)
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function logErasureRequest($userId) {
        try {
            // Get user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return false;
            }
            
            // Instead of storing in a table, we'll use emails/logging
            $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';
            $subject = 'GDPR Log: Erasure Request';
            $message = "GDPR Action Log\n";
            $message .= "------------------------\n";
            $message .= "User ID: {$userId}\n";
            $message .= "User Email: {$user['email']}\n";
            $message .= "Action: Erasure Request\n";
            $message .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
            $message .= "Details: User requested account deletion\n";
            
            $this->sendEmail($adminEmail, $subject, $message);
            

            
            return true;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Cancel a pending data deletion request
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function cancelDeletionRequest($userId) {
        $userId = (int)$userId;
        
        try {
            // Since we're not using a database table, just clear the session data
            if (isset($_SESSION['deletion_request']) && $_SESSION['deletion_request']['user_id'] == $userId) {
                unset($_SESSION['deletion_request']);
                
                // Get user data
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    // Send confirmation email
                    $userEmail = $user['email'];
                    $subject = 'Account Deletion Request Cancelled - Traballa';
                    $message = "Dear {$user['name']},\n\n";
                    $message .= "Your request to delete your Traballa account has been cancelled successfully. ";
                    $message .= "No further action is required, and your account will remain active.\n\n";
                    $message .= "If you did not cancel this request, please contact support immediately.\n\n";
                    $message .= "Thank you for continuing to use Traballa.";
                    
                    // In a real system, send this email
                    // mail($userEmail, $subject, $message);
                }
                
                // Log the action for auditing purposes
                $this->logGdprAction($userId, 'erasure_cancelled', 'User cancelled account deletion');
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Check if a user has a pending deletion request
     * 
     * @param int $userId User ID
     * @return array|bool Deletion request info or false if none exists
     */
    public function getPendingDeletionRequest($userId) {
        $userId = (int)$userId;
        
        // Check session for deletion request
        if (isset($_SESSION['deletion_request']) && $_SESSION['deletion_request']['user_id'] == $userId) {
            return $_SESSION['deletion_request'];
        }
        
        return false;
    }
    
    /**
     * Set processing restrictions for a user (Art. 18)
     * 
     * @param int $userId User ID
     * @param array $restrictions Type of restrictions to apply
     * @return bool Success status
     */
    public function setProcessingRestrictions($userId, $restrictions) {
        $userId = (int)$userId;
        
        if (empty($restrictions)) {
            return false;
        }
        
        try {
            // Store restrictions in session
            $_SESSION['processing_restrictions'] = [
                'user_id' => $userId,
                'restrictions' => $restrictions,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Get user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Send confirmation email
                $userEmail = $user['email'];
                $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';
                $subject = 'Data Processing Restrictions Applied - Traballa';
                $message = "Dear {$user['name']},\n\n";
                $message .= "As requested, we have applied the following restrictions to the processing of your data:\n";
                $message .= "- " . implode("\n- ", $restrictions) . "\n\n";
                $message .= "These settings are now in effect. You can update these preferences at any time in your GDPR Data Management page.\n\n";
                $message .= "Thank you for using Traballa.";
                
                // In a real system, send these emails
                // mail($userEmail, $subject, $message);
                
                $adminMessage = "User {$user['name']} (ID: $userId) has set the following data processing restrictions:\n";
                $adminMessage .= "- " . implode("\n- ", $restrictions) . "\n";
                // mail($adminEmail, 'ADMIN: ' . $subject, $adminMessage);
            }
            
            // Log the action for auditing purposes
            $this->logGdprAction($userId, 'processing_restricted', 
                               'User restricted data processing: ' . implode(', ', $restrictions));
            
            return true;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Get active processing restrictions for a user
     * 
     * @param int $userId User ID
     * @return array List of active restrictions
     */
    public function getProcessingRestrictions($userId) {
        $userId = (int)$userId;
        
        // Check session for processing restrictions
        if (isset($_SESSION['processing_restrictions']) && $_SESSION['processing_restrictions']['user_id'] == $userId) {
            return $_SESSION['processing_restrictions']['restrictions'];
        }
        
        return [];
    }
    
    /**
     * Register user objection to specific processing (Art. 21)
     * 
     * @param int $userId User ID
     * @param string $reason Reason for objection
     * @param array $processingTypes Types of processing being objected to
     * @return bool Success status
     */
    public function registerObjection($userId, $reason, $processingTypes) {
        $userId = (int)$userId;
        
        if (empty($processingTypes)) {
            return false;
        }
        
        try {
            // Store objections in session
            $_SESSION['objections'] = [
                'user_id' => $userId,
                'reason' => $reason,
                'processing_types' => $processingTypes,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Get user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Send confirmation email to user and notification to admin
                $userEmail = $user['email'];
                $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';
                $subject = 'Objection to Data Processing - Traballa';
                
                $message = "Dear {$user['name']},\n\n";
                $message .= "We have received your objection to the following types of data processing:\n";
                $message .= "- " . implode("\n- ", $processingTypes) . "\n\n";
                $message .= "Your reason: $reason\n\n";
                $message .= "Your objection has been logged and will be reviewed within 30 days. ";
                $message .= "You will be notified about the outcome of our review.\n\n";
                $message .= "Thank you for using Traballa.";
                
                // In a real system, send these emails
                // mail($userEmail, $subject, $message);
                
                $adminMessage = "User {$user['name']} (ID: $userId) has objected to the following types of data processing:\n";
                $adminMessage .= "- " . implode("\n- ", $processingTypes) . "\n";
                $adminMessage .= "Reason: $reason\n";
                // mail($adminEmail, 'ADMIN: ' . $subject, $adminMessage);
            }
            
            // Log the action for auditing purposes
            $this->logGdprAction($userId, 'objection_registered', 
                               'User objected to processing: ' . implode(', ', $processingTypes));
            
            return true;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Get active objections for a user
     * 
     * @param int $userId User ID
     * @return array List of active objections
     */
    public function getObjections($userId) {
        $userId = (int)$userId;
        
        // Check session for objections
        if (isset($_SESSION['objections']) && $_SESSION['objections']['user_id'] == $userId) {
            $objections = [];
            foreach ($_SESSION['objections']['processing_types'] as $type) {
                $objections[] = [
                    'processing_type' => $type,
                    'reason' => $_SESSION['objections']['reason'],
                    'created_at' => $_SESSION['objections']['created_at']
                ];
            }
            return $objections;
        }
        
        return [];
    }
    
    /**
     * Generate data export for portability (Art. 20)
     * 
     * @param int $userId User ID
     * @param string $format Format to export (json, csv)
     * @param array $dataTypes Types of data to include
     * @return string|bool Path to exported file or false on failure
     */
    public function generateUserDataExport($userId, $format = 'json', $dataTypes = ['all']) {
        $userId = (int)$userId;
        
        // Get all user data
        $userData = $this->fetchUserData($userId);
        
        // Filter data if needed
        if (!in_array('all', $dataTypes)) {
            $filteredData = [];
            foreach ($dataTypes as $type) {
                if (isset($userData[$type])) {
                    $filteredData[$type] = $userData[$type];
                }
            }
            $userData = $filteredData;
        }
        
        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'traballa_export_');
        
        if ($format === 'json') {
            // JSON export
            file_put_contents($tempFile, json_encode($userData, JSON_PRETTY_PRINT));
        } else {
            // CSV export
            $csvContent = "field,value\n";
            
            // Recursive function to flatten nested arrays
            $flattenForCSV = function($data, $prefix = '') use (&$csvContent, &$flattenForCSV) {
                foreach ($data as $key => $value) {
                    $fullKey = $prefix ? "$prefix.$key" : $key;
                    
                    if (is_array($value)) {
                        $flattenForCSV($value, $fullKey);
                    } else {
                        // Format the value for CSV
                        if (is_bool($value)) {
                            $value = $value ? 'true' : 'false';
                        }
                        $csvContent .= "$fullKey,\"" . str_replace('"', '""', $value) . "\"\n";
                    }
                }
            };
            
            $flattenForCSV($userData);
            file_put_contents($tempFile, $csvContent);
        }

        return $tempFile;
    }
    
    /**
     * Log GDPR-related actions for audit trail
     * 
     * @param int $userId User ID
     * @param string $actionType Type of action
     * @param string $details Details about the action
     * @return bool Success status
     */
    public function logGdprAction($userId, $actionType, $details) {
        try {
            // Get user data
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return false;
            }
            
            // Store log in session
            if (!isset($_SESSION['gdpr_logs'])) {
                $_SESSION['gdpr_logs'] = [];
            }
            
            // Send email notification to admin
            $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';
            $subject = 'GDPR Action Log: ' . ucfirst($actionType);
            $message = "GDPR Action Log\n";
            $message .= "------------------------\n";
            $message .= "User: {$user['name']} (ID: $userId)\n";
            $message .= "Email: {$user['email']}\n";
            $message .= "Action: $actionType\n";
            $message .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
            $message .= "Details: $details\n";
            
            // Send email notification
            $this->sendEmail($adminEmail, $subject, $message);
            
            return true;
        } catch (PDOException $e) {
            // Handle errors in a real system
            return false;
        }
    }
    
    /**
     * Send an email using the EmailSender class
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $message Email message (plain text)
     * @param string $htmlMessage HTML version of the message (optional)
     * @return boolean Success or failure
     */
    private function sendEmail($to, $subject, $message, $htmlMessage = null) {
        // Add GDPR compliant footer to all emails
        $message .= $this->emailSender->getGdprFooter();
        
        if ($htmlMessage) {
            $htmlMessage .= nl2br($this->emailSender->getGdprFooter());
        }
        
        return $this->emailSender->send($to, $subject, $message, $htmlMessage);
    }
    
}
