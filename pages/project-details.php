<?php
/**
 * Traballa - Project details
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

// Check if project ID is provided
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Project ID is required.</div>';
    exit();
}

$project_id = (int)$_GET['id'];

// Get project details
$project = getProjectById($pdo, $project_id);

if (!$project) {
    echo '<div class="alert alert-danger">Project not found.</div>';
    exit();
}

// Check if user has access to this project
if (!canAccessProject($pdo, $_SESSION['user_id'], $project_id)) {
    echo '<div class="alert alert-danger">You do not have permission to access this project.</div>';
    exit();
}

// Get project statistics
$stats = getProjectStatistics($pdo, $project_id);

// Get project members
$members = getProjectMembers($pdo, $project_id);

// Get project managers
$managers = getProjectManagers($pdo, $project_id);

// Check if current user is a manager of this project
$is_project_manager = isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id);

// Get Traballa for this project
$work_hours_query = "SELECT wh.*, u.name as user_name 
                    FROM work_hours wh 
                    JOIN users u ON wh.user_id = u.id 
                    WHERE wh.project_id = $project_id 
                    ORDER BY wh.clock_in DESC 
                    LIMIT 10";
$work_hours_stmt = $pdo->prepare($work_hours_query);
$work_hours_stmt->execute([$project_id]);
$work_hours = [];

while ($row = $work_hours_stmt->fetch(PDO::FETCH_ASSOC)) {
    $work_hours[] = $row;
}
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
// Add project name as the final breadcrumb item
$customItems = [['title' => $project['name'], 'url' => null]];
echo $breadcrumb->render(current_route(), $customItems);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo $project['name']; ?></h1>
    <div>
        <?php if ($is_project_manager || isAdmin()): ?>
            <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Projects
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProjectModal">
                <i class="fas fa-edit me-1"></i> Edit Project
            </button>
        <?php else: ?>
            <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to Projects
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Project Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Organization:</div>
                    <div class="col-md-9"><?php echo $project['organization_name']; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Description:</div>
                    <div class="col-md-9"><?php echo $project['description'] ? $project['description'] : 'No description provided.'; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Status:</div>
                    <div class="col-md-9">
                        <span class="badge <?php 
                            echo $project['status'] === 'active' ? 'bg-success' : 
                                ($project['status'] === 'completed' ? 'bg-secondary' : 'bg-warning'); 
                        ?>">
                            <?php echo ucfirst($project['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Created:</div>
                    <div class="col-md-9"><?php echo date('M d, Y', strtotime($project['created_at'])); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Last Updated:</div>
                    <div class="col-md-9"><?php echo date('M d, Y', strtotime($project['updated_at'])); ?></div>
                </div>
            </div>
        </div>

        <!-- Project Traballa -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent work hours</h5>
                <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/work-hours?project_id=' . $project_id : 'index.php?page=work-hours&project_id=' . $project_id; ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock out</th>
                                <th>Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($work_hours)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No Traballa recorded for this project yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($work_hours as $work): ?>
                                    <tr>
                                        <td><?php echo $work['user_name']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($work['clock_in'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($work['clock_in'])); ?></td>
                                        <td>
                                            <?php if ($work['status'] === 'completed'): ?>
                                                <?php echo date('h:i A', strtotime($work['clock_out'])); ?>
                                            <?php else: ?>
                                                <span class="badge bg-warning">In Progress</span>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kanbas Board -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tablero Kanban</h5>
                <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/kanban/' . $project_id : 'index.php?page=kanban&id=' . $project_id; ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-tasks me-1"></i> Ver Tablero
                </a>
            </div>
            <div class="card-body">
                <p>Gestiona las tareas del proyecto con el tablero Kanban. Organiza el trabajo en columnas, asigna responsables y establece fechas de vencimiento.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Project Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="display-6"><?php echo number_format($stats['total_hours'], 1); ?></div>
                        <div class="text-muted">Hours</div>
                    </div>
                    <div class="col-4">
                        <div class="display-6"><?php echo $stats['member_count']; ?></div>
                        <div class="text-muted">Members</div>
                    </div>
                    <div class="col-4">
                        <div class="display-6"><?php echo $stats['active_sessions']; ?></div>
                        <div class="text-muted">Active</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Members -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Members</h5>
                <?php if ($is_project_manager || isAdmin()): ?>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manageMembersModal">
                        <i class="fas fa-user-plus me-1"></i> Manage
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php if (empty($members)): ?>
                        <p class="text-muted">No members in this project.</p>
                    <?php else: ?>
                        <?php foreach ($members as $member): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div><?php echo $member['name']; ?></div>
                                    <small class="text-muted"><?php echo $member['email']; ?></small>
                                </div>
                                <span class="badge <?php echo $member['is_manager'] ? 'bg-primary' : 'bg-secondary'; ?>">
                                    <?php echo $member['is_manager'] ? 'Manager' : 'Member'; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Project Managers -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Managers</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php if (empty($managers)): ?>
                        <p class="text-muted">No managers assigned to this project.</p>
                    <?php else: ?>
                        <?php foreach ($managers as $manager): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div><?php echo $manager['name']; ?></div>
                                    <small class="text-muted"><?php echo $manager['email']; ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<?php if ($is_project_manager || isAdmin()): ?>
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_project">
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Project name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $project['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo $project['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?php echo $project['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="on_hold" <?php echo $project['status'] === 'on_hold' ? 'selected' : ''; ?>>On Hold</option>
                            <option value="completed" <?php echo $project['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Members Modal -->
<div class="modal fade" id="manageMembersModal" tabindex="-1" aria-labelledby="manageMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageMembersModalLabel">Manage project members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Member Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Add member</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>">
                            <input type="hidden" name="action" value="add_member">
                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="form-text">Enter the email of an existing user or a new user to invite</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name (for new users)</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                    <div class="form-text">Only required if inviting a new user</div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_manager" name="is_manager">
                                <label class="form-check-label" for="is_manager">
                                    Make Project Manager
                                </label>
                                <div class="form-text">Project managers can add/remove members and update project details</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add Member</button>
                        </form>
                    </div>
                </div>
                
                <!-- Current Members List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Current Members</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($members)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No members in this project</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($members as $member): ?>
                                            <tr>
                                                <td><?php echo $member['name']; ?></td>
                                                <td><?php echo $member['email']; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $member['is_manager'] ? 'bg-primary' : 'bg-secondary'; ?>">
                                                        <?php echo $member['is_manager'] ? 'Manager' : 'Member'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($member['id'] != $_SESSION['user_id']): ?>
                                                        <form method="post" action="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>" class="d-inline">
                                                            <input type="hidden" name="action" value="remove_member">
                                                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                                                            <input type="hidden" name="user_id" value="<?php echo $member['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this member?')">
                                                                <i class="fas fa-user-minus"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted">You</span>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

