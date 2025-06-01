<?php
/**
 * Traballa - Delete kanban task
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

// Check if required parameters are provided
if (!isset($_POST['task_id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$task_id = (int)$_POST['task_id'];
$user_id = (int)$session->get('user_id');

// Get task information to check project access
$task_stmt = $pdo->prepare("SELECT kt.*, kc.project_id FROM kanban_tasks kt 
                           JOIN kanban_columns kc ON kt.column_id = kc.id 
                           WHERE kt.id = ?");
$task_stmt->execute([$task_id]);

if ($task_stmt->rowCount() === 0) {
    echo json_encode(['success' => false, 'error' => 'Task not found']);
    exit();
}

$task = $task_stmt->fetch(PDO::FETCH_ASSOC);
$project_id = (int)$task['project_id'];

// Check if user has access to this project
if (!canAccessProject($pdo, $user_id, $project_id)) {
    echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
    exit();
}

// Delete the task
if (deleteKanbanTask($pdo, $task_id)) {
    echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error deleting task']);
}