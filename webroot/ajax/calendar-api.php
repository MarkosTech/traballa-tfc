<?php
/**
 * Traballa - Calendar API
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
require_once '../../includes/Calendar.php';
require_once '../../config/database.php';
require_once '../../includes/Session.php';
require_once '../../includes/functions.php';

// Set JSON response header
header('Content-Type: application/json');

try {
    // Initialize session handler
    $session = new Session($pdo);
    
    // Check if user is authenticated
    if (!$session->get('user_id')) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
        exit();
    }
    
    $calendar = new Calendar($pdo);

    // Get current user ID and organization ID
    $user_id = $session->get('user_id');
    $organization_id = $session->get('current_organization_id');

    // Handle calendar event actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Basic CSRF protection - check referer
        if (!isset($_SERVER['HTTP_REFERER']) || 
            !str_contains($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid request origin']);
            exit();
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF token mismatch']);
            exit();
        }
        
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add_event':
                    // Validate required fields
                    if (empty($_POST['title']) || empty($_POST['start_date']) || empty($_POST['end_date']) || empty($_POST['event_type'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                        break;
                    }
                    
                    try {
                        $result = $calendar->addEvent(
                            sanitize_output($_POST['title']),
                            sanitize_output($_POST['description'] ?? ''),
                            sanitize($_POST['start_date']),
                            sanitize($_POST['end_date']),
                            sanitize($_POST['event_type']),
                            $user_id,
                            isset($_POST['project_id']) ? (int)$_POST['project_id'] : null,
                            isset($_POST['organization_id']) ? (int)$_POST['organization_id'] : $organization_id
                        );
                        echo json_encode(['success' => true, 'message' => 'Event added successfully']);
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Error adding event: ' . $e->getMessage()]);
                    }
                    break;
                    
                case 'update_event':
                    // Validate required fields
                    if (empty($_POST['event_id']) || empty($_POST['title']) || empty($_POST['start_date']) || empty($_POST['end_date']) || empty($_POST['event_type'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                        break;
                    }
                    
                    $event_id = (int)$_POST['event_id'];
                    
                    // Verify user owns this event or has permission to edit it
                    $event = $calendar->getEventById($event_id);
                    if (!$event || ($event['created_by'] != $user_id && !isAdmin())) {
                        http_response_code(403);
                        echo json_encode(['success' => false, 'message' => 'You do not have permission to edit this event']);
                        break;
                    }
                    
                    try {
                        $result = $calendar->updateEvent(
                            $event_id,
                            sanitize($_POST['title']),
                            sanitize($_POST['description'] ?? ''),
                            sanitize($_POST['start_date']),
                            sanitize($_POST['end_date']),
                            sanitize($_POST['event_type']),
                            $user_id,
                            isset($_POST['project_id']) ? (int)$_POST['project_id'] : null,
                            isset($_POST['organization_id']) ? (int)$_POST['organization_id'] : $organization_id
                        );
                        echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Error updating event: ' . $e->getMessage()]);
                    }
                    break;
                    
                case 'delete_event':
                    if (empty($_POST['event_id'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Event ID is required']);
                        break;
                    }
                    
                    $event_id = (int)$_POST['event_id'];
                    
                    // Verify user owns this event or has permission to delete it
                    $event = $calendar->getEventById($event_id);
                    if (!$event || ($event['created_by'] != $user_id && !isAdmin())) {
                        http_response_code(403);
                        echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this event']);
                        break;
                    }
                    
                    try {
                        $result = $calendar->deleteEvent($event_id);
                        echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Error deleting event: ' . $e->getMessage()]);
                    }
                    break;
                    
                case 'get_event':
                    if (empty($_POST['event_id'])) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Event ID is required']);
                        break;
                    }
                    
                    $event_id = (int)$_POST['event_id'];
                    
                    try {
                        $event = $calendar->getEventById($event_id);
                        if ($event) {
                            // Check if user has permission to view this event
                            if ($event['created_by'] != $user_id && !isAdmin()) {
                                http_response_code(403);
                                echo json_encode(['success' => false, 'message' => 'You do not have permission to view this event']);
                                break;
                            }
                            echo json_encode($event);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'message' => 'Event not found']);
                        }
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Error fetching event: ' . $e->getMessage()]);
                    }
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid action']);
                    break;
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No action specified']);
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_event' && isset($_GET['event_id'])) {
        // Alternative GET method for event details
        if (empty($_GET['event_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Event ID is required']);
            exit();
        }
        
        $event_id = (int)$_GET['event_id'];
        
        try {
            $event = $calendar->getEventById($event_id);
            if ($event) {
                // Check if user has permission to view this event
                if ($event['created_by'] != $user_id && !isAdmin()) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'You do not have permission to view this event']);
                    exit();
                }
                echo json_encode($event);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Event not found']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error fetching event: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    if (DEBUG_MODE) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } else {
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}
?>
