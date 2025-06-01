<?php
/**
 * Traballa - Calendar
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

if (!defined('INDEX_EXEC')) {
    exit('Direct access not allowed.');
}

// Include breadcrumb functionality
require_once __DIR__ . '/../includes/Breadcrumb.php';

require_once '../includes/Calendar.php';
require_once '../config/database.php';

try {
    $calendar = new Calendar($pdo);

    // Get current user ID and organization ID
    $user_id = $_SESSION['user_id'];
    $organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;

    // Get current month and year from query parameters or use current date
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    
    // Get projects and organizations for dropdowns
    $projects = $calendar->getProjects();
    $organizations = $calendar->getOrganizations();
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die("Database error: " . $e->getMessage());
    } else {
        die("An error occurred. Please try again later.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="/assets/css/calendar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        
        .modal-dialog {
            position: relative;
            width: auto;
            margin: 1.75rem auto;
            max-width: 500px;
        }
        
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            border-radius: 0.3rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.5);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1rem;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .btn-close {
            padding: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            background-color: transparent;
            border: 0;
        }
    </style>
</head>
<body class="calendar-page">
    <div class="calendar-container">
        <div class="calendar-main">
            <!-- Breadcrumbs -->
            <?php
            // Initialize breadcrumb with router if available
            global $router;
            $breadcrumb = new Breadcrumb($router);
            echo $breadcrumb->render(current_route());
            ?>
            
            <div class="calendar-header">
                <div class="calendar-nav">
                    <?php
                    $prevMonth = $month - 1;
                    $prevYear = $year;
                    if ($prevMonth < 1) {
                        $prevMonth = 12;
                        $prevYear--;
                    }

                    $nextMonth = $month + 1;
                    $nextYear = $year;
                    if ($nextMonth > 12) {
                        $nextMonth = 1;
                        $nextYear++;
                    }
                    ?>
                    <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <h2><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
                    <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="calendar-actions">
                    <a href="?month=<?php echo date('n'); ?>&year=<?php echo date('Y'); ?>" class="btn btn-primary">Today</a>
                    <button type="button" class="btn btn-success" id="addEventBtn">
                        <i class="fas fa-plus"></i> Add event
                    </button>
                </div>
            </div>
            
            <div class="calendar-hint">
                <i class="fas fa-info-circle"></i> Click on any day to add an event
            </div>
            
            <?php echo $calendar->generateCalendarHTML($month, $year, $user_id, $organization_id); ?>
        </div>
    </div>
    
    <!-- Add/Edit Event Modal -->
    <div class="modal" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="add-event-form" id="event-form">
                        <input type="hidden" name="action" value="add_event" id="form-action">
                        <input type="hidden" name="event_id" id="event-id">
                        
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_type">Event type</label>
                            <select id="event_type" name="event_type" class="form-control" required>
                                <option value="personal">Personal</option>
                                <option value="project">Project</option>
                                <option value="organization">Organization</option>
                            </select>
                        </div>
                        
                        <div class="form-group project-select" id="projectField" style="display: none;">
                            <label for="project_id">Project</label>
                            <select id="project_id" name="project_id" class="form-control">
                                <option value="">Select project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group organization-select" id="organizationField" style="display: none;">
                            <label for="organization_id">Organization</label>
                            <select id="organization_id" name="organization_id" class="form-control">
                                <option value="">Select organization</option>
                                <?php foreach ($organizations as $org): ?>
                                    <option value="<?php echo $org['id']; ?>" <?php echo ($org['id'] == $organization_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($org['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" id="start_date" name="start_date" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" id="end_date" name="end_date" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close-btn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEventBtn">Save event</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Details Modal -->
    <div class="modal" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="eventDetails">
                        <p id="detailsModalDescription"></p>
                        <div class="event-details">
                            <p><strong>Start:</strong> <span id="detailsModalStartDate"></span></p>
                            <p><strong>End:</strong> <span id="detailsModalEndDate"></span></p>
                            <p><strong>Type:</strong> <span id="detailsModalEventType"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-close-btn">Close</button>
                    <button type="button" class="btn btn-primary" id="editEventBtn">Edit</button>
                    <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/assets/js/calendar.js"></script>
</body>
</html>