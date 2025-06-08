<?php
/**
 * Traballa - Work hours
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

// Default date range (current month)
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');

// Get current organization ID
$organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;

// Process date filter and other filters
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
   $start_date = $_GET['start_date'];
   $end_date = $_GET['end_date'];
}

// Get project filter
$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;

// Get user's projects for filter dropdown
if ($organization_id) {
   $user_projects = getUserProjects($pdo, $_SESSION['user_id'], $organization_id);
} else {
   $user_projects = getUserProjects($pdo, $_SESSION['user_id']);
}

// Process edit Traballa form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Validate CSRF token
    check_csrf();
    
    $action = $_POST['action'];
    
    if ($action === 'edit_work_hours' && isset($_POST['work_id'])) {
        $work_id = (int)$_POST['work_id'];
        $clock_in = $_POST['clock_in'];
        $clock_out = $_POST['clock_out'];
        $notes = $_POST['notes']; // We'll use prepared statements, no need for sanitize()
        
        // Validate that the user owns this work hour entry
        $stmt = $pdo->prepare("SELECT * FROM work_hours WHERE id = :work_id AND user_id = :user_id");
        $stmt->execute([
            ':work_id' => $work_id, 
            ':user_id' => $_SESSION['user_id']
        ]);
        
        if ($stmt->rowCount() > 0) {
            // Calculate total hours
            $total_hours = calculateHours($clock_in, $clock_out);
            
            // Update Traballa
            $update_stmt = $pdo->prepare("UPDATE work_hours SET 
                                clock_in = :clock_in, 
                                clock_out = :clock_out, 
                                total_hours = :total_hours,
                                notes = :notes
                                WHERE id = :work_id");
            
            $update_result = $update_stmt->execute([
                ':clock_in' => $clock_in,
                ':clock_out' => $clock_out,
                ':total_hours' => $total_hours,
                ':notes' => $notes,
                ':work_id' => $work_id
            ]);
            
            if ($update_result) {
                $success = "Traballa updated successfully";
            } else {
                $error = "Error updating Traballa";
            }
        } else {
            $error = "You don't have permission to edit this entry";
        }
    } elseif ($action === 'delete_work_hours' && isset($_POST['work_id'])) {
        $work_id = (int)$_POST['work_id'];
        
        // Validate that the user owns this work hour entry
        $stmt = $pdo->prepare("SELECT * FROM work_hours WHERE id = :work_id AND user_id = :user_id");
        $stmt->execute([
            ':work_id' => $work_id, 
            ':user_id' => $_SESSION['user_id']
        ]);
        
        if ($stmt->rowCount() > 0) {
            // Delete Traballa
            $delete_stmt = $pdo->prepare("DELETE FROM work_hours WHERE id = :work_id");
            $delete_result = $delete_stmt->execute([':work_id' => $work_id]);
            
            if ($delete_result) {
                $success = "Traballa deleted successfully";
            } else {
                $error = "Error deleting Traballa";
            }
        } else {
            $error = "You don't have permission to delete this entry";
        }
    }
}

// Get Traballa for the selected period with filters
$work_hours = getUserWorkHours($pdo, $_SESSION['user_id'], $start_date, $end_date, $project_id, $organization_id);

// Calculate totals
$total_hours = 0;
foreach ($work_hours as $work) {
   if ($work['status'] === 'completed') {
       $total_hours += $work['total_hours'];
   }
}
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
echo $breadcrumb->render(current_route());
?>

<div class="content-header">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Work Hours</h1>
        <button class="btn btn-outline-info help-btn" data-help-context="work-hours">
            <i class="fas fa-question-circle me-1"></i>Help
        </button>
    </div>
</div>
<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filter Card -->
<div class="card mb-4">
   <div class="card-body">
       <form method="get" action="" class="row g-3">
           <input type="hidden" name="page" value="work-hours">
           <div class="col-md-3">
               <label for="start_date" class="form-label">Start date</label>
               <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
           </div>
           <div class="col-md-3">
               <label for="end_date" class="form-label">End date</label>
               <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
           </div>
           <div class="col-md-3">
               <label for="project_id" class="form-label">Project</label>
               <select class="form-select" id="project_id" name="project_id">
                   <option value="">All projects</option>
                   <?php foreach ($user_projects as $project): ?>
                       <option value="<?php echo (int)$project['id']; ?>" <?php echo ($project_id == $project['id']) ? 'selected' : ''; ?>>
                           <?php echo sanitize_output($project['name']); ?>
                       </option>
                   <?php endforeach; ?>
               </select>
           </div>
           <div class="col-md-3 d-flex align-items-end">
               <button type="submit" class="btn btn-primary me-2">
                   <i class="fas fa-filter me-1"></i> Filter
               </button>
               <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/work-hours' : 'index.php?page=work-hours'; ?>" class="btn btn-outline-secondary">
                   <i class="fas fa-redo me-1"></i> Reset
               </a>
           </div>
       </form>
   </div>
</div>

<!-- Summary Card -->
<div class="card mb-4">
   <div class="card-body">
       <div class="row">
           <div class="col-md-6">
               <h5 class="card-title">Summary</h5>
               <p class="card-text">
                   Period: <strong><?php echo date('M d, Y', strtotime($start_date)); ?></strong> to 
                   <strong><?php echo date('M d, Y', strtotime($end_date)); ?></strong>
               </p>
               <?php if ($organization_id): ?>
                   <p class="card-text">
                       Organization: <strong><?php echo getOrganizationById($pdo, $organization_id)['name']; ?></strong>
                   </p>
               <?php endif; ?>
               <?php if ($project_id): ?>
                   <p class="card-text">
                       Project: <strong><?php echo getProjectById($pdo, $project_id)['name']; ?></strong>
                   </p>
               <?php endif; ?>
           </div>
           <div class="col-md-6 text-md-end">
               <h5 class="card-title">Total hours</h5>
               <p class="card-text display-6"><?php echo number_format($total_hours, 1); ?></p>
           </div>
       </div>
   </div>
</div>

<!-- Work hours -->
<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
       <h5 class="mb-0">Work hours log</h5>
       <button class="btn btn-sm btn-outline-primary" onclick="exportTableToCSV('work_hours.csv')">
           <i class="fas fa-download me-1"></i> Export CSV
       </button>
   </div>
   <div class="card-body">
       <div class="table-responsive">
           <table class="table table-hover" id="workHoursTable">
               <thead>
                   <tr>
                       <th>Date</th>
                       <th>Project</th>
                       <th>Organization</th>
                       <th>Clock in</th>
                       <th>Clock out</th>
                       <th>Hours</th>
                       <th>Status</th>
                       <th>Actions</th>
                   </tr>
               </thead>
               <tbody>
                   <?php if (empty($work_hours)): ?>
                       <tr>
                           <td colspan="8" class="text-center">No Traballa found for the selected period.</td>
                       </tr>
                   <?php else: ?>
                       <?php foreach ($work_hours as $work): ?>
                           <tr>
                               <td><?php echo date('M d, Y', strtotime($work['clock_in'])); ?></td>
                               <td><?php echo sanitize_output($work['project_name']); ?></td>
                               <td><?php echo sanitize_output($work['organization_name']); ?></td>
                               <td><?php echo date('h:i A', strtotime($work['clock_in'])); ?></td>
                               <td>
                                   <?php if ($work['status'] === 'completed'): ?>
                                       <?php echo date('h:i A', strtotime($work['clock_out'])); ?>
                                   <?php else: ?>
                                       <span class="badge bg-warning">In progress</span>
                                   <?php endif; ?>
                               </td>
                               <td>
                                   <?php if ($work['status'] === 'completed'): ?>
                                       <?php echo number_format($work['total_hours'], 1); ?>
                                   <?php else: ?>
                                       -
                                   <?php endif; ?>
                               </td>
                               <td>
                                   <?php if ($work['status'] === 'completed'): ?>
                                       <span class="badge bg-success">Completed</span>
                                   <?php else: ?>
                                       <span class="badge bg-primary">Working</span>
                                   <?php endif; ?>
                               </td>
                               <td>
                                   <?php if ($work['status'] === 'completed'): ?>
                                       <div class="btn-group">
                                           <button type="button" class="btn btn-sm btn-outline-info break-btn" 
                                                  data-bs-toggle="modal" data-bs-target="#breakModal"
                                                  data-id="<?php echo (int)$work['id']; ?>"
                                            >
                                               <i class="fas fa-coffee me-1"></i>
                                           </button>
                                           <button type="button" class="btn btn-sm btn-outline-primary edit-hours-btn" 
                                                  data-bs-toggle="modal" data-bs-target="#editHoursModal"
                                                  data-id="<?php echo (int)$work['id']; ?>"
                                                  data-clock-in="<?php echo sanitize_attribute($work['clock_in']); ?>"
                                                  data-clock-out="<?php echo sanitize_attribute($work['clock_out']); ?>"
                                                  data-notes="<?php echo isset($work['notes']) ? sanitize_attribute($work['notes']) : ''; ?>"
                                            >
                                               <i class="fas fa-edit"></i>
                                           </button>
                                           <button type="button" class="btn btn-sm btn-outline-danger delete-hours-btn"
                                                  data-bs-toggle="modal" data-bs-target="#deleteHoursModal"
                                                  data-id="<?php echo (int)$work['id']; ?>"
                                                  data-date="<?php echo sanitize_attribute(date('M d, Y', strtotime($work['clock_in']))); ?>"
                                                  data-project="<?php echo sanitize_attribute($work['project_name']); ?>"
                                            >
                                               <i class="fas fa-trash"></i>
                                           </button>
                                       </div>
                                   <?php else: ?>
                                       <button type="button" class="btn btn-sm btn-outline-warning break-btn"
                                               data-bs-toggle="modal" data-bs-target="#breakModal"
                                               data-id="<?php echo (int)$work['id']; ?>">
                                           <i class="fas fa-coffee"></i>
                                       </button>
                                   <?php endif; ?>
                               </td>
                           </tr>
                       <?php endforeach; ?>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
   </div>
</div>

<!-- Edit Hours Modal -->
<div class="modal fade" id="editHoursModal" tabindex="-1" aria-labelledby="editHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHoursModalLabel">Edit traballa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="action" value="edit_work_hours">
                    <input type="hidden" name="work_id" id="edit_work_id">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="edit_clock_in" class="form-label">Clock in</label>
                        <input type="datetime-local" class="form-control" id="edit_clock_in" name="clock_in" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_clock_out" class="form-label">Clock out</label>
                        <input type="datetime-local" class="form-control" id="edit_clock_out" name="clock_out" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Hours Modal -->
<div class="modal fade" id="deleteHoursModal" tabindex="-1" aria-labelledby="deleteHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteHoursModalLabel">Delete traballa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Traballa entry?</p>
                <p><strong>Date:</strong> <span id="delete_date"></span></p>
                <p><strong>Project:</strong> <span id="delete_project"></span></p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone.
                </div>
                <form method="post" action="">
                    <input type="hidden" name="action" value="delete_work_hours">
                    <input type="hidden" name="work_id" id="delete_work_id">
                    <?php echo csrf_field(); ?>
                    
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Break Modal -->
<div class="modal fade" id="breakModal" tabindex="-1" aria-labelledby="breakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="breakModalLabel">Break history</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div id="breakHistorySection" class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody id="breakHistoryBody">
                                <!-- Break history will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="activeBreakSection" class="mt-4 d-none">
                    <h6>Active break</h6>
                    <div class="alert alert-permanent alert-dismissible alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1"><strong>Type:</strong> <span id="active_break_type"></span></p>
                                <p class="mb-1"><strong>Started:</strong> <span id="active_break_start"></span></p>
                                <p class="mb-0"><strong>Duration:</strong> <span id="active_break_duration">00:00:00</span></p>
                            </div>
                            <button id="endBreakBtn" data-bs-dismiss="alert" class="btn btn-warning">End break</button>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/work-hours.js"></script>