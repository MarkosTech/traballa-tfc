<?php
/**
 * Traballa - Update kanban task
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
// Include necessary files
require_once '../../config/database.php';
require_once '../../includes/Session.php';
require_once '../../includes/functions.php';

// Initialize session handler
$session = new Session($pdo);

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!$session->get('user_id')) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Validate CSRF token for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRFTOKEN'] ?? '';
    if (!validate_csrf_token($token)) {
        echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
        exit();
    }
}

$user_id = (int)$session->get('user_id');

// Check if action is provided
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'error' => 'Missing action parameter']);
    exit();
}

$action = $_POST['action'];

switch ($action) {
    case 'update_task':
        if (!isset($_POST['task_id']) || !isset($_POST['title'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
            exit();
        }
        
        $task_id = (int)$_POST['task_id'];
        $title = sanitize($_POST['title']);
        $description = isset($_POST['description']) ? sanitize($_POST['description']) : '';
        $assigned_to = isset($_POST['assigned_to']) && !empty($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        $status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'pending', 'completed']) ? $_POST['status'] : 'active';
        
        // Get the project ID from the task
        $task_stmt = $pdo->prepare("SELECT kt.project_id FROM kanban_tasks kt WHERE kt.id = ?");
        $task_stmt->execute([$task_id]);
        
        if (!($task = $task_stmt->fetch(PDO::FETCH_ASSOC))) {
            echo json_encode(['success' => false, 'error' => 'Invalid task']);
            exit();
        }
        
        $project_id = (int)$task['project_id'];
        
        // Check if user has access to this project
        if (!canAccessProject($pdo, $user_id, $project_id)) {
            echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
            exit();
        }
        
        // Update the task
        if (updateKanbanTask($pdo, $task_id, $title, $description, $assigned_to, $due_date, $status)) {
            // Update the project last update timestamp
            $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?")->execute([$project_id]);
            
            echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating task']);
        }
        break;
        
    case 'move_task':
        if (!isset($_POST['task_id']) || !isset($_POST['column_id'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
            exit();
        }
        
        $task_id = (int)$_POST['task_id'];
        $column_id = (int)$_POST['column_id'];
        $status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'pending', 'completed']) ? $_POST['status'] : 'active';
        
        // Get the project ID from the task
        $task_query = "SELECT kt.project_id 
                      FROM kanban_tasks kt 
                      WHERE kt.id = ?";
        $task_stmt = $pdo->prepare($task_query);
        $task_stmt->execute([$task_id]);
        
        if (!($task = $task_stmt->fetch(PDO::FETCH_ASSOC))) {
            echo json_encode(['success' => false, 'error' => 'Invalid task']);
            exit();
        }
        
        $project_id = (int)$task['project_id'];
        
        // Check if user has access to this project
        if (!canAccessProject($pdo, $user_id, $project_id)) {
            echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
            exit();
        }
        
        // Verify that the column belongs to the same project
        $column_stmt = $pdo->prepare("SELECT project_id FROM kanban_columns WHERE id = ?");
        $column_stmt->execute([$column_id]);
        
        if (!($column = $column_stmt->fetch(PDO::FETCH_ASSOC))) {
            echo json_encode(['success' => false, 'error' => 'Invalid column']);
            exit();
        }
        
        if ((int)$column['project_id'] !== $project_id) {
            echo json_encode(['success' => false, 'error' => 'Column does not belong to the task project']);
            exit();
        }
        
        // Get max position in the target column
        $pos_stmt = $pdo->prepare("SELECT COALESCE(MAX(position), 0) + 1 as next_position FROM kanban_tasks WHERE column_id = ?");
        $pos_stmt->execute([$column_id]);
        $pos_row = $pos_stmt->fetch(PDO::FETCH_ASSOC);
        $position = (int)$pos_row['next_position'];
        
        // Update task column
        $update_stmt = $pdo->prepare("UPDATE kanban_tasks 
                        SET column_id = ?, 
                            position = ?,
                            status = ?, 
                            updated_at = NOW() 
                        WHERE id = ?");
                        
        if ($update_stmt->execute([$column_id, $position, $status, $task_id])) {
            // Update the project last update timestamp
            $update_project_stmt = $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?");
            $update_project_stmt->execute([$project_id]);
            
            // Return the updated task data
            $task_stmt = $pdo->prepare("SELECT * FROM kanban_tasks WHERE id = ?");
            $task_stmt->execute([$task_id]);
            $task = $task_stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Task moved successfully',
                'task' => $task
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error moving task']);
        }
        break;
        
    case 'update_task_order':
        if (!isset($_POST['column_id']) || !isset($_POST['task_order'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
            exit();
        }
        
        $column_id = (int)$_POST['column_id'];
        $task_order = json_decode($_POST['task_order'], true);
        
        if (!$task_order || !isset($task_order['active']) || !isset($task_order['completed'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid task order data']);
            exit();
        }
        
        // Get the project ID from the column
        $column_stmt = $pdo->prepare("SELECT project_id FROM kanban_columns WHERE id = ?");
        $column_stmt->execute([$column_id]);
        
        if ($column_stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid column']);
            exit();
        }
        
        $column = $column_stmt->fetch(PDO::FETCH_ASSOC);
        $project_id = (int)$column['project_id'];
        
        // Check if user has access to this project
        if (!canAccessProject($pdo, $user_id, $project_id)) {
            echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
            exit();
        }
        
        // Update task positions for active tasks
        $position = 1;
        foreach ($task_order['active'] as $task_id) {
            $task_id = (int)$task_id;
            
            // Verify that the task belongs to the column and project
            $task_stmt = $pdo->prepare("SELECT id FROM kanban_tasks WHERE id = ? AND column_id = ? AND project_id = ?");
            $task_stmt->execute([$task_id, $column_id, $project_id]);
            
            if ($task_stmt->rowCount() > 0) {
                $update_stmt = $pdo->prepare("UPDATE kanban_tasks 
                                SET position = ?, 
                                    status = 'active',
                                    updated_at = NOW() 
                                WHERE id = ?");
                $update_stmt->execute([$position, $task_id]);
                $position++;
            }
        }
        
        // Update task positions for completed tasks (they come after active tasks)
        foreach ($task_order['completed'] as $task_id) {
            $task_id = (int)$task_id;
            
            // Verify that the task belongs to the column and project
            $task_stmt = $pdo->prepare("SELECT id FROM kanban_tasks WHERE id = ? AND column_id = ? AND project_id = ?");
            $task_stmt->execute([$task_id, $column_id, $project_id]);
            
            if ($task_stmt->rowCount() > 0) {
                $update_stmt = $pdo->prepare("UPDATE kanban_tasks 
                                SET position = ?, 
                                    status = 'completed',
                                    updated_at = NOW() 
                                WHERE id = ?");
                $update_stmt->execute([$position, $task_id]);
                $position++;
            }
        }
        
        // Update the project last update timestamp
        $update_project_stmt = $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?");
        $update_project_stmt->execute([$project_id]);
        
        echo json_encode(['success' => true, 'message' => 'Task order updated successfully']);
        break;
        
    case 'update_column_order':
        if (!isset($_POST['column_order'])) {
            echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
            exit();
        }
        
        $column_order = json_decode($_POST['column_order'], true);
        
        if (!$column_order || !is_array($column_order) || empty($column_order)) {
            echo json_encode(['success' => false, 'error' => 'Invalid column order data']);
            exit();
        }
        
        // Get first column to determine project
        $first_column_id = (int)$column_order[0];
        $column_stmt = $pdo->prepare("SELECT project_id FROM kanban_columns WHERE id = ?");
        $column_stmt->execute([$first_column_id]);
        
        if ($column_stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid column']);
            exit();
        }
        
        $column = $column_stmt->fetch(PDO::FETCH_ASSOC);
        $project_id = (int)$column['project_id'];
        
        // Check if user has access to this project
        if (!canAccessProject($pdo, $user_id, $project_id)) {
            echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
            exit();
        }
        
        // Update column positions
        $position = 1;
        foreach ($column_order as $column_id) {
            $column_id = (int)$column_id;
            
            // Verify that the column belongs to the project
            $column_check_stmt = $pdo->prepare("SELECT id FROM kanban_columns WHERE id = ? AND project_id = ?");
            $column_check_stmt->execute([$column_id, $project_id]);
            
            if ($column_check_stmt->rowCount() > 0) {
                $update_stmt = $pdo->prepare("UPDATE kanban_columns 
                                SET position = ? 
                                WHERE id = ?");
                $update_stmt->execute([$position, $column_id]);
                $position++;
            }
        }
        
        // Update the project last update timestamp
        $update_project_stmt = $pdo->prepare("UPDATE projects SET last_kanban_update = NOW() WHERE id = ?");
        $update_project_stmt->execute([$project_id]);
        
        echo json_encode(['success' => true, 'message' => 'Column order updated successfully']);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}