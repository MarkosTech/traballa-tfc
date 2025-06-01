<?php
/**
 * Traballa - Manage breaks
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
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/     

session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Handle GET requests (fetching break data)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get_breaks' && isset($_GET['work_hour_id'])) {
        $work_hour_id = (int)$_GET['work_hour_id'];
        
        // Verify that the user owns this work hour entry
        $check_stmt = $pdo->prepare("SELECT * FROM work_hours WHERE id = ? AND user_id = ?");
        $check_stmt->execute([$work_hour_id, $_SESSION['user_id']]);
        
        if ($check_stmt->rowCount() === 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }
        
        // Get active break
        $active_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? AND status = 'active' LIMIT 1");
        $active_stmt->execute([$work_hour_id]);
        $active_break = $active_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$active_break) {
            $active_break = null;
        }
        
        // Get break history
        $history_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? ORDER BY start_time DESC");
        $history_stmt->execute([$work_hour_id]);
        $break_history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'active_break' => $active_break,
            'break_history' => $break_history
        ]);
        exit();
    }
}

// Handle POST requests (starting/ending breaks)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'start_break' && isset($_POST['work_hour_id'])) {
        $work_hour_id = (int)$_POST['work_hour_id'];
        $break_type = sanitize($_POST['break_type']);
        $notes = isset($_POST['notes']) ? sanitize($_POST['notes']) : '';
        $current_time = date('Y-m-d H:i:s');
        
        // Verify that the user owns this work hour entry
        $check_stmt = $pdo->prepare("SELECT * FROM work_hours WHERE id = ? AND user_id = ? AND status = 'working'");
        $check_stmt->execute([$work_hour_id, $_SESSION['user_id']]);
        
        if ($check_stmt->rowCount() === 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid work hour entry or not currently working']);
            exit();
        }
        
        // Check if there's already an active break
        $active_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? AND status = 'active'");
        $active_stmt->execute([$work_hour_id]);
        
        if ($active_stmt->rowCount() > 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'You already have an active break']);
            exit();
        }
        
        // Start a new break
        $insert_stmt = $pdo->prepare("INSERT INTO breaks (work_hour_id, start_time, type, notes, status) 
                                     VALUES (?, ?, ?, ?, 'active')");
        
        if ($insert_stmt->execute([$work_hour_id, $current_time, $break_type, $notes])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Break started successfully']);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error starting break']);
            exit();
        }
    } elseif ($action === 'end_break' && isset($_POST['break_id'])) {
        $break_id = (int)$_POST['break_id'];
        $current_time = date('Y-m-d H:i:s');
        
        // Verify that the user owns this break
        $check_stmt = $pdo->prepare("SELECT b.* FROM breaks b 
                                    JOIN work_hours wh ON b.work_hour_id = wh.id 
                                    WHERE b.id = ? AND wh.user_id = ? AND b.status = 'active'");
        $check_stmt->execute([$break_id, $_SESSION['user_id']]);
        
        if ($check_stmt->rowCount() === 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid break or not active']);
            exit();
        }
        
        $break = $check_stmt->fetch(PDO::FETCH_ASSOC);
        $start_time = $break['start_time'];
        $duration = calculateHours($start_time, $current_time);
        
        // End the break
        $update_stmt = $pdo->prepare("UPDATE breaks SET 
                                     end_time = ?, 
                                     duration = ?, 
                                     status = 'completed' 
                                     WHERE id = ?");
        
        if ($update_stmt->execute([$current_time, $duration, $break_id])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Break ended successfully']);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error ending break']);
            exit();
        }
    }
}

// Default response for invalid requests
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit();

