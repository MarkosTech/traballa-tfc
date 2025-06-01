<?php
/**
 * Traballa - Add kanban column
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
if (!isset($_POST['project_id']) || !isset($_POST['name'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$project_id = (int)$_POST['project_id'];
$tab_id = isset($_POST['tab_id']) ? (int)$_POST['tab_id'] : null;
$name = sanitize($_POST['name']);
$user_id = (int)$session->get('user_id');

// Check if user has access to this project
if (!canAccessProject($pdo, $user_id, $project_id)) {
    echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
    exit();
}

// If no tab_id provided, get the default tab for this project
if ($tab_id === null) {
    $tab_stmt = $pdo->prepare("SELECT id FROM kanban_tabs WHERE project_id = ? AND is_default = 1");
    $tab_stmt->execute([$project_id]);
    
    if ($tab_stmt->rowCount() > 0) {
        $tab = $tab_stmt->fetch(PDO::FETCH_ASSOC);
        $tab_id = (int)$tab['id'];
    } else {
        echo json_encode(['success' => false, 'error' => 'No default tab found for project']);
        exit();
    }
}

// Verify the tab belongs to the project
$tab_check_stmt = $pdo->prepare("SELECT id FROM kanban_tabs WHERE id = ? AND project_id = ?");
$tab_check_stmt->execute([$tab_id, $project_id]);

if ($tab_check_stmt->rowCount() === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid tab for this project']);
    exit();
}

// Add the column
if (addKanbanColumnWithTab($pdo, $project_id, $tab_id, $name)) {
    // Get the newly created column to return its data
    $column_stmt = $pdo->prepare("SELECT * FROM kanban_columns WHERE project_id = ? AND tab_id = ? AND name = ? ORDER BY id DESC LIMIT 1");
    $column_stmt->execute([$project_id, $tab_id, $name]);
    
    if ($column_stmt->rowCount() > 0) {
        $column = $column_stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'column' => $column,
            'message' => 'Column added successfully'
        ]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Column added successfully, but could not retrieve details']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error adding column']);
}