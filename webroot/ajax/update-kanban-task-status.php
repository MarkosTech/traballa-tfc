<?php
/**
 * Traballa - Update kanban task status
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
// Update kanban task status
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Check if this is an AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

try {
    $task_id = $_POST['task_id'] ?? '';
    $status = $_POST['status'] ?? '';
    
    if (empty($task_id) || empty($status)) {
        echo json_encode(['success' => false, 'error' => 'Task ID and status are required']);
        exit;
    }
    
    // Validate status
    $valid_statuses = ['active', 'pending', 'completed'];
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }
    
    // Get task details first to verify user has access - using PDO
    
    // Check if user has access to this task (through project membership)
    $check_access_query = "
        SELECT kt.*, kc.tab_id, kt2.project_id 
        FROM kanban_tasks kt 
        JOIN kanban_columns kc ON kt.column_id = kc.id 
        JOIN kanban_tabs kt2 ON kc.tab_id = kt2.id 
        JOIN project_members pm ON kt2.project_id = pm.project_id 
        WHERE kt.id = ? AND pm.user_id = ?
    ";
    
    $stmt = $pdo->prepare($check_access_query);
    $stmt->execute([$task_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'Task not found or access denied']);
        exit;
    }
    
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Update task status
    $update_query = "UPDATE kanban_tasks SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    
    if ($stmt->execute([$status, $task_id])) {
        echo json_encode([
            'success' => true, 
            'message' => 'Task status updated successfully',
            'task_id' => $task_id,
            'status' => $status
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update task status']);
    }
    
} catch (Exception $e) {
    error_log("Error updating task status: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred while updating task status']);
}
?>
