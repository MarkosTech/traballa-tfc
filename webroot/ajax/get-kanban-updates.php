<?php
/**
 * Traballa - Get kanban updates
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

define('INDEX_EXEC', true);
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/database.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.', 'status' => 'unauthorized']);
    exit();
}

// Check if project ID is provided
if (!isset($_GET['project_id'])) {
    echo json_encode(['error' => 'Project ID is required.']);
    exit();
}

$project_id = (int)$_GET['project_id'];

// Check if user has access to this project
if (!canAccessProject($pdo, $_SESSION['user_id'], $project_id)) {
    echo json_encode(['error' => 'You do not have permission to access this project.']);
    exit();
}

// If full_data parameter is set, return complete kanban data
if (isset($_GET['full_data']) && $_GET['full_data'] === 'true') {
    $tab_id = isset($_GET['tab_id']) ? (int)$_GET['tab_id'] : null;
    
    if ($tab_id !== null) {
        // Get data for specific tab
        $tabs = getKanbanTabs($pdo, $project_id);
        $columns = getKanbanColumnsWithTabs($pdo, $project_id, $tab_id);
        $members = getProjectMembers($pdo, $project_id);
        
        echo json_encode([
            'success' => true,
            'tabs' => $tabs,
            'columns' => $columns,
            'members' => $members,
            'current_tab_id' => $tab_id,
            'timestamp' => time()
        ]);
    } else {
        // Get all data (for backward compatibility)
        $tabs = getKanbanTabs($pdo, $project_id);
        $columns = getKanbanColumns($pdo, $project_id);
        $members = getProjectMembers($pdo, $project_id);
        
        echo json_encode([
            'success' => true,
            'tabs' => $tabs,
            'columns' => $columns,
            'members' => $members,
            'timestamp' => time()
        ]);
    }
    exit();
}

// Check for updates since the last_update timestamp
if (isset($_GET['last_update'])) {
    $last_update = (int)$_GET['last_update'];
    $current_time = time();
    
    // Query to check if there have been any updates to the kanban board since the last check
    $sql = "SELECT MAX(last_updated) as last_change FROM (
                SELECT MAX(updated_at) as last_updated FROM kanban_columns WHERE project_id = ? AND updated_at > FROM_UNIXTIME(?)
                UNION 
                SELECT MAX(updated_at) as last_updated FROM kanban_tasks WHERE column_id IN (SELECT id FROM kanban_columns WHERE project_id = ?) AND updated_at > FROM_UNIXTIME(?)
            ) as updates";
    
    try {        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$project_id, $last_update, $project_id, $last_update]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $last_change = $result['last_change'] ?? null;
        
        $hasUpdates = !empty($last_change);
        
        echo json_encode([
            'success' => true,
            'hasUpdates' => $hasUpdates,
            'timestamp' => $current_time
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
    exit();
}

// Default response if no specific action is requested
echo json_encode(['error' => 'Invalid request.']);