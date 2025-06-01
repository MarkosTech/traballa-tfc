<?php
/**
 * Traballa - Add kanban tab
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
$name = sanitize($_POST['name']);
$user_id = (int)$session->get('user_id');

// Check if user has access to this project and is a manager
if (!canAccessProject($pdo, $user_id, $project_id)) {
    echo json_encode(['success' => false, 'error' => 'You do not have access to this project']);
    exit();
}

// Check if user is project manager
$manager_stmt = $pdo->prepare("SELECT is_manager FROM project_members WHERE project_id = ? AND user_id = ?");
$manager_stmt->execute([$project_id, $user_id]);

if ($manager_stmt->rowCount() === 0) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit();
}

$manager = $manager_stmt->fetch(PDO::FETCH_ASSOC);
if (!$manager['is_manager']) {
    echo json_encode(['success' => false, 'error' => 'Only project managers can create tabs']);
    exit();
}

// Add the tab
if (addKanbanTab($pdo, $project_id, $name)) {
    // Get the newly created tab to return its data
    $tab_stmt = $pdo->prepare("SELECT * FROM kanban_tabs WHERE project_id = ? AND name = ? ORDER BY id DESC LIMIT 1");
    $tab_stmt->execute([$project_id, $name]);
    
    if ($tab_stmt->rowCount() > 0) {
        $tab = $tab_stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'tab' => $tab,
            'message' => 'Tab added successfully'
        ]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Tab added successfully, but could not retrieve details']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error adding tab']);
}
