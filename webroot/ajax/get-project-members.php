<?php
/**
 * Traballa - Get project members
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
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if project_id is provided
if (!isset($_GET['project_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Project ID is required']);
    exit();
}

$project_id = (int)$_GET['project_id'];

// Check if user has access to this project
if (!canAccessProject($pdo, $_SESSION['user_id'], $project_id)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You do not have access to this project']);
    exit();
}

// Get project members
$stmt = $pdo->prepare("SELECT u.id as user_id, u.name, u.email, u.role, pm.is_manager, pm.joined_at 
                       FROM users u 
                       JOIN project_members pm ON u.id = pm.user_id 
                       WHERE pm.project_id = ? 
                       ORDER BY pm.is_manager DESC, u.name ASC");
$stmt->execute([$project_id]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return members as JSON
header('Content-Type: application/json');
echo json_encode($members);

