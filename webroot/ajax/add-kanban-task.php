<?php
/**
 * Traballa - Add kanban task
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

// Initialize our session handler
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

// Check if required parameters are provided
if (!isset($_POST['column_id']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$column_id = (int)$_POST['column_id'];
$tab_id = isset($_POST['tab_id']) ? (int)$_POST['tab_id'] : null;
$title = sanitize($_POST['title']);
$description = isset($_POST['description']) ? sanitize($_POST['description']) : '';
$assigned_to = isset($_POST['assigned_to']) && !empty($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
$due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
$status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'pending', 'completed']) ? $_POST['status'] : 'active';
$user_id = (int)$session->get('user_id');

// Get the project ID and tab ID from the column
$column_stmt = $pdo->prepare("SELECT project_id, tab_id FROM kanban_columns WHERE id = ?");
$column_stmt->execute([$column_id]);

if (!($column = $column_stmt->fetch(PDO::FETCH_ASSOC))) {
    echo json_encode(['success' => false, 'error' => 'Invalid column']);
    exit();
}

$project_id = (int)$column['project_id'];

// Use tab_id from column if not provided in POST
if ($tab_id === null) {
    $tab_id = (int)$column['tab_id'];
}

// Check if user has access to this project
if (!canAccessProject($pdo, $user_id, $project_id)) {
    echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
    exit();
}

// Add the task
if (addKanbanTaskWithTab($pdo, $column_id, $project_id, $tab_id, $title, $user_id, $description, $assigned_to, $due_date, $status)) {
    // Get the newly created task to return its data
    $task_stmt = $pdo->prepare("SELECT * FROM kanban_tasks WHERE column_id = ? AND title = ? ORDER BY id DESC LIMIT 1");
    $task_stmt->execute([$column_id, $title]);
    
    if ($task = $task_stmt->fetch(PDO::FETCH_ASSOC)) {
        
        // Get assigned user name if applicable
        $assigned_name = null;
        if (!empty($task['assigned_to'])) {
            $user_stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
            $user_stmt->execute([(int)$task['assigned_to']]);
            if ($user = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
                $assigned_name = $user['name'];
            }
        }
        
        // Return task data and HTML for insertion
        echo json_encode([
            'success' => true, 
            'task' => $task,
            'assigned_name' => $assigned_name,
            'message' => 'Task added successfully'
        ]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Task added successfully, but could not retrieve details']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error adding task']);
}