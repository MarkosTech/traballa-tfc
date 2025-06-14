<?php
/**
 * Traballa - GDPR Data Deletion Scheduler
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * This script processes scheduled data deletion requests
 * according to GDPR Art. 17 (Right to erasure/Right to be forgotten)
 * 
 * This file should be executed by a daily cron job
 */

// Define execution context
define('INDEX_EXEC', true);

// Set up required includes
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/GdprManager.php';

// Initialize GDPR Manager
$gdprManager = new GdprManager($pdo);

// Log execution
$logMessage = date('Y-m-d H:i:s') . " | GDPR Deletion Scheduler started\n";

// Process any scheduled deletions (both in session and database)
$deletionCount = $gdprManager->processScheduledDeletions(); 
$deletionCount += $gdprManager->checkDatabaseDeletionRequests();

// Count deleted users
$deletedCount = 0;

// Process deletion requests from database
try {
    // Get all pending deletion requests that have passed their scheduled date
    $stmt = $pdo->prepare("
        SELECT u.id, u.email, u.name, d.reason, d.scheduled_deletion_date 
        FROM users u
        INNER JOIN gdpr_deletion_requests d ON u.id = d.user_id
        WHERE d.status = 'pending' AND d.scheduled_deletion_date <= CURRENT_DATE
    ");
    $stmt->execute();
    
    while ($request = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userId = $request['id'];
        $userEmail = $request['email'];
        $userName = $request['name'];
        
        // Log the deletion
        $logMessage .= date('Y-m-d H:i:s') . " | Processing deletion for user $userId ($userEmail)\n";
        
        // Execute the deletion
        if ($gdprManager->executeUserDeletion($userId)) {
            $deletedCount++;
            $logMessage .= date('Y-m-d H:i:s') . " | Successfully deleted user $userId\n";
            
            // Update the request status
            $updateStmt = $pdo->prepare("
                UPDATE gdpr_deletion_requests 
                SET status = 'completed', completed_at = CURRENT_TIMESTAMP
                WHERE user_id = ?
            ");
            $updateStmt->execute([$userId]);
        } else {
            $logMessage .= date('Y-m-d H:i:s') . " | Failed to delete user $userId\n";
        }
    }
} catch (PDOException $e) {
    $logMessage .= date('Y-m-d H:i:s') . " | Error: " . $e->getMessage() . "\n";
}

// If we are running from command line, output results
if (php_sapi_name() === 'cli') {
    echo $logMessage;
    echo "Processed $deletedCount deletion requests\n";
}

// Log execution completion
$logMessage .= date('Y-m-d H:i:s') . " | GDPR Deletion Scheduler completed. $deletedCount accounts processed.\n";

// Save log
if (defined('GDPR_LOG_FILE')) {
    file_put_contents(GDPR_LOG_FILE, $logMessage, FILE_APPEND);
} else {
    file_put_contents(__DIR__ . '/logs/gdpr_deletion.log', $logMessage, FILE_APPEND);
}

// Create directory for logs if it doesn't exist
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}
