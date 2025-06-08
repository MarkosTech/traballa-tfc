<?php
/**
 * Traballa - Kanban board
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
$project_id = null;
$show_project_selector = false;

if (!isset($_GET['id'])) {
    $show_project_selector = true;
    // Rest of page will load with project selector UI instead of kanban board
} else {
    $project_id = (int)$_GET['id'];

    // Get project details
    $project = getProjectById($pdo, $project_id);

    if (!$project) {
        echo '<div class="alert alert-danger">Project not found.</div>';
        $show_project_selector = true;
    }
    // Check if user has access to this project
    else if (!canAccessProject($pdo, $_SESSION['user_id'], $project_id)) {
        echo '<div class="alert alert-danger">You do not have permission to access this project.</div>';
        $show_project_selector = true;
    } else {
        // Check if current user is a manager of this project
        $is_project_manager = isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id);

        // Get current tab ID (default to main tab if not specified)
        $current_tab_id = isset($_GET['tab']) ? (int)$_GET['tab'] : null;
        
        // Get all tabs for this project
        $tabs = getKanbanTabs($pdo, $project_id);
        
        // If no tabs exist, create default tab
        if (empty($tabs)) {
            createDefaultKanbanTab($pdo, $project_id);
            $tabs = getKanbanTabs($pdo, $project_id);
        }
        
        // If no current tab specified, use the default tab
        if ($current_tab_id === null && !empty($tabs)) {
            foreach ($tabs as $tab) {
                if ($tab['is_default']) {
                    $current_tab_id = $tab['id'];
                    break;
                }
            }
        }
        
        // Validate that the current tab still exists (in case it was deleted)
        if ($current_tab_id !== null && !empty($tabs)) {
            $tab_exists = false;
            foreach ($tabs as $tab) {
                if ($tab['id'] == $current_tab_id) {
                    $tab_exists = true;
                    break;
                }
            }
            
            // If the current tab doesn't exist, fall back to the default tab
            if (!$tab_exists) {
                foreach ($tabs as $tab) {
                    if ($tab['is_default']) {
                        $current_tab_id = $tab['id'];
                        break;
                    }
                }
            }
        }

        // Get kanban columns and tasks for the current tab
        $columns = getKanbanColumnsWithTabs($pdo, $project_id, $current_tab_id);

        // If no columns exist, create default columns for the current tab
        if (empty($columns) && $current_tab_id) {
            // First create default columns for the project
            createDefaultKanbanColumns($pdo, $project_id);
            // Then update them to belong to the current tab
            $stmt = $pdo->prepare("UPDATE kanban_columns SET tab_id = ? WHERE project_id = ? AND tab_id IS NULL");
            $stmt->execute([$current_tab_id, $project_id]);
            $columns = getKanbanColumnsWithTabs($pdo, $project_id, $current_tab_id);
        }

        // Get project members for assignments
        $members = getProjectMembers($pdo, $project_id);
    }
}

// Process task actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $project_id) {
    // Validate CSRF token
    check_csrf();
    
    $action = $_POST['action'];
    
    if ($action === 'add_task' && isset($_POST['column_id']) && isset($_POST['title'])) {
        $column_id = (int)$_POST['column_id'];
        $title = sanitize($_POST['title']);
        $description = isset($_POST['description']) ? sanitize($_POST['description']) : '';
        $assigned_to = isset($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        
        if (addKanbanTask($pdo, $column_id, $project_id, $title, $_SESSION['user_id'], $description, $assigned_to, $due_date)) {
            $success = "Task added successfully";
        } else {
            $error = "Error adding task";
        }
    } elseif ($action === 'update_task' && isset($_POST['task_id'])) {
        $task_id = (int)$_POST['task_id'];
        $title = sanitize($_POST['title']);
        $description = isset($_POST['description']) ? sanitize($_POST['description']) : '';
        $assigned_to = isset($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
        
        if (updateKanbanTask($pdo, $task_id, $title, $description, $assigned_to, $due_date)) {
            $success = "Task updated successfully";
        } else {
            $error = "Error updating task";
        }
    } elseif ($action === 'delete_task' && isset($_POST['task_id'])) {
        $task_id = (int)$_POST['task_id'];
        
        if (deleteKanbanTask($pdo, $task_id)) {
            $success = "Task deleted successfully";
        } else {
            $error = "Error deleting task";
        }
    } elseif ($action === 'add_column' && isset($_POST['name'])) {
        $name = sanitize($_POST['name']);
        
        if (addKanbanColumn($pdo, $project_id, $name)) {
            $success = "Column added successfully";
        } else {
            $error = "Error adding column";
        }
    } elseif ($action === 'update_column' && isset($_POST['column_id']) && isset($_POST['name'])) {
        $column_id = (int)$_POST['column_id'];
        $name = sanitize($_POST['name']);
        
        if (updateKanbanColumn($pdo, $column_id, $name)) {
            $success = "Column updated successfully";
        } else {
            $error = "Error updating column";
        }
    } elseif ($action === 'delete_column' && isset($_POST['column_id'])) {
        $column_id = (int)$_POST['column_id'];
        
        if (deleteKanbanColumn($pdo, $column_id)) {
            $success = "Column deleted successfully";
        } else {
            $error = "Error deleting column";
        }
    } elseif ($action === 'add_tab' && isset($_POST['name'])) {
        $name = sanitize($_POST['name']);
        
        if (addKanbanTab($pdo, $project_id, $name)) {
            $success = "Tab added successfully";
        } else {
            $error = "Error adding tab";
        }
    } elseif ($action === 'update_tab' && isset($_POST['tab_id']) && isset($_POST['name'])) {
        $tab_id = (int)$_POST['tab_id'];
        $name = sanitize($_POST['name']);
        
        if (updateKanbanTab($pdo, $tab_id, $name)) {
            $success = "Tab updated successfully";
        } else {
            $error = "Error updating tab";
        }
    } elseif ($action === 'delete_tab' && isset($_POST['tab_id'])) {
        $tab_id = (int)$_POST['tab_id'];
        
        if (deleteKanbanTab($pdo, $tab_id)) {
            $success = "Tab deleted successfully";
            // Set flag for redirect after page loads
            $redirect_after_delete = true;
        } else {
            $error = "Error deleting tab";
        }
    }
    
    // Refresh data after changes
    if (isset($current_tab_id)) {
        $tabs = getKanbanTabs($pdo, $project_id);
        $columns = getKanbanColumnsWithTabs($pdo, $project_id, $current_tab_id);
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

<!-- Kanban Board -->
<div class="kanban-wrapper" <?php if ($project_id) { echo 'data-project-id="' . $project_id . '"'; } ?>>
    <?php if ($show_project_selector): ?>
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Kanban board: select a project</h1>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
            <?php
                // Obtener los proyectos del usuario (filtrados por organización si está seleccionada)
                $organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;
                $user_projects = getUserActiveProjects($pdo, $_SESSION['user_id'], $organization_id);
                
                if (empty($user_projects)) {
                    echo '<div class="alert alert-info">No tienes proyectos activos';
                    if ($organization_id) {
                        echo ' en la organización actual. Prueba a seleccionar otra organización o contacta con tu administrador.';
                    } else {
                        echo '. Contacta con tu administrador para ser asignado a un proyecto.';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="list-group">';
                    foreach ($user_projects as $project) {
                        $url = function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) 
                               ? '/kanban/' . $project['id'] 
                               : 'index.php?page=kanban&id=' . $project['id'];
                        echo '<a href="' . $url . '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">';
                        echo htmlspecialchars($project['name']);
                        
                        // Mostrar badge si el usuario es manager del proyecto
                        if (isset($project['is_manager']) && $project['is_manager']) {
                            echo '<span class="badge bg-primary rounded-pill">Manager</span>';
                        }
                        
                        echo '</a>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    <?php elseif ($project_id): ?>
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Kanban board: <?php echo htmlspecialchars($project['name']); ?></h1>
                <div>
                    <?php if ($is_project_manager || isAdmin()): ?>
                        <button type="button" class="btn btn-primary add-column-btn" data-bs-toggle="modal" data-bs-target="#addColumnModal">
                            <i class="fas fa-plus me-1"></i> Add column
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
        </div>

        <!-- Tab Navigation -->
        <?php if (!empty($tabs)): ?>
        <div class="kanban-tabs-wrapper mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs kanban-tabs" id="kanbanTabs" role="tablist">
                    <?php foreach ($tabs as $tab): ?>
                        <li class="nav-item kanban-tab-item" role="presentation" data-tab-id="<?php echo $tab['id']; ?>">
                            <div class="d-flex align-items-center position-relative">
                                <a class="nav-link <?php echo ($tab['id'] == $current_tab_id) ? 'active' : ''; ?>" 
                                   href="?page=kanban&id=<?php echo $project_id; ?>&tab=<?php echo $tab['id']; ?>"
                                   role="tab" 
                                   data-tab-id="<?php echo $tab['id']; ?>">
                                    <?php echo htmlspecialchars($tab['name']); ?>
                                </a>
                                <?php if ($is_project_manager || isAdmin()): ?>
                                    <div class="dropdown tab-dropdown">
                                        <button class="btn btn-sm btn-link text-muted tab-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item edit-tab-btn" data-bs-toggle="modal" data-bs-target="#editTabModal"
                                                    data-tab-id="<?php echo $tab['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($tab['name']); ?>">
                                                    <i class="fas fa-edit me-2"></i> Edit Tab
                                                </button>
                                            </li>
                                            <?php if (!$tab['is_default']): ?>
                                            <li>
                                                <button class="dropdown-item text-danger delete-tab-btn" data-bs-toggle="modal" data-bs-target="#deleteTabModal"
                                                    data-tab-id="<?php echo $tab['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($tab['name']); ?>">
                                                    <i class="fas fa-trash me-2"></i> Delete Tab
                                                </button>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <?php if ($is_project_manager || isAdmin()): ?>
                <div class="kanban-tab-actions">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addTabModal">
                        <i class="fas fa-plus me-1"></i> Add Tab
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="kanban-board">
            <div class="kanban-container">
                <?php foreach ($columns as $column): ?>
                    <div class="kanban-column" data-column-id="<?php echo $column['id']; ?>">
                        <div class="kanban-column-header">
                            <h5><?php echo sanitize_output($column['name']); ?></h5>
                            <?php if ($is_project_manager || isAdmin()): ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item edit-column-btn" data-bs-toggle="modal" data-bs-target="#editColumnModal"
                                                data-column-id="<?php echo (int)$column['id']; ?>"
                                                data-name="<?php echo sanitize_attribute($column['name']); ?>">
                                                <i class="fas fa-edit me-1"></i> Edit column
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger delete-column-btn" data-bs-toggle="modal" data-bs-target="#deleteColumnModal"
                                                data-column-id="<?php echo (int)$column['id']; ?>"
                                                data-name="<?php echo sanitize_attribute($column['name']); ?>">
                                                <i class="fas fa-trash me-1"></i> Delete column
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="kanban-tasks" data-column-id="<?php echo $column['id']; ?>">
                            <?php 
                            $activeTasks = [];
                            $completedTasks = [];
                            
                            if (!empty($column['tasks'])) {
                                foreach ($column['tasks'] as $task) {
                                    if (isset($task['status']) && $task['status'] === 'completed') {
                                        $completedTasks[] = $task;
                                    } else {
                                        $activeTasks[] = $task;
                                    }
                                }
                            }
                            
                            // Display active tasks first
                            foreach ($activeTasks as $task): 
                                $taskStatusClass = isset($task['status']) ? 'task-status-' . $task['status'] : 'task-status-active';
                            ?>
                                <div class="kanban-task <?php echo sanitize_attribute($taskStatusClass); ?>" data-task-id="<?php echo (int)$task['id']; ?>" data-status="<?php echo sanitize_attribute(isset($task['status']) ? $task['status'] : 'active'); ?>">
                                    <div class="kanban-task-header">
                                        <h6 class="mb-0"><?php echo sanitize_output($task['title']); ?></h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item edit-task-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                                        data-task-id="<?php echo (int)$task['id']; ?>"
                                                        data-title="<?php echo sanitize_attribute($task['title']); ?>"
                                                        data-description="<?php echo sanitize_attribute($task['description']); ?>"
                                                        data-assigned-to="<?php echo (int)$task['assigned_to']; ?>"
                                                        data-due-date="<?php echo sanitize_attribute($task['due_date']); ?>"
                                                        data-status="<?php echo isset($task['status']) ? $task['status'] : 'active'; ?>">
                                                        <i class="fas fa-edit me-1"></i> Edit task
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger delete-task-btn" data-bs-toggle="modal" data-bs-target="#deleteTaskModal"
                                                        data-task-id="<?php echo $task['id']; ?>"
                                                        data-title="<?php echo $task['title']; ?>">
                                                        <i class="fas fa-trash me-1"></i> Delete task
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="task-status-buttons">
                                            <button type="button" class="btn btn-sm btn-status status-btn-active status-option" title="Mark as Active" data-status="active" data-task-id="<?php echo $task['id']; ?>">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-status status-btn-pending status-option" title="Mark as Pending" data-status="pending" data-task-id="<?php echo $task['id']; ?>">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-status status-btn-completed status-option" title="Mark as Completed" data-status="completed" data-task-id="<?php echo $task['id']; ?>">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (!empty($task['description'])): ?>
                                        <p class="small text-muted mt-1 mb-2"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                                    <?php endif; ?>
                                    <div class="kanban-task-footer">
                                        <?php if ($task['assigned_to']): ?>
                                            <div class="small">
                                                <i class="fas fa-user me-1"></i> 
                                                <?php 
                                                foreach ($members as $member) {
                                                    if ($member['id'] == $task['assigned_to']) {
                                                        echo $member['name'];
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($task['due_date']): ?>
                                            <div class="small">
                                                <i class="fas fa-calendar me-1"></i> 
                                                <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; 
                            
                            // Display completed tasks section if there are any
                            if (!empty($completedTasks)): ?>
                                <div class="completed-tasks-section">
                                    <div class="completed-tasks-header" data-bs-toggle="collapse" href="#completedTasks<?php echo $column['id']; ?>" role="button" aria-expanded="false">
                                        <i class="fas fa-chevron-down me-1 collapse-icon"></i> Completed tasks (<?php echo count($completedTasks); ?>)
                                    </div>
                                    <div class="collapse" id="completedTasks<?php echo $column['id']; ?>">
                                        <?php foreach ($completedTasks as $task): ?>
                                            <div class="kanban-task task-status-completed" data-task-id="<?php echo $task['id']; ?>" data-status="completed">
                                                <div class="kanban-task-header">
                                                    <h6 class="mb-0"><?php echo $task['title']; ?></h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <button class="dropdown-item edit-task-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                                                    data-task-id="<?php echo $task['id']; ?>"
                                                                    data-title="<?php echo $task['title']; ?>"
                                                                    data-description="<?php echo $task['description']; ?>"
                                                                    data-assigned-to="<?php echo $task['assigned_to']; ?>"
                                                                    data-due-date="<?php echo $task['due_date']; ?>"
                                                                    data-status="completed">
                                                                    <i class="fas fa-edit me-1"></i> Edit task
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger delete-task-btn" data-bs-toggle="modal" data-bs-target="#deleteTaskModal"
                                                                    data-task-id="<?php echo $task['id']; ?>"
                                                                    data-title="<?php echo $task['title']; ?>">
                                                                    <i class="fas fa-trash me-1"></i> Delete task
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="task-status-buttons">
                                                        <button type="button" class="btn btn-sm btn-status status-btn-active status-option" title="Mark as Active" data-status="active" data-task-id="<?php echo $task['id']; ?>">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-status status-btn-pending status-option" title="Mark as Pending" data-status="pending" data-task-id="<?php echo $task['id']; ?>">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-status status-btn-completed status-option" title="Mark as Completed" data-status="completed" data-task-id="<?php echo $task['id']; ?>">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php if (!empty($task['description'])): ?>
                                                    <p class="small text-muted mt-1 mb-2"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                                                <?php endif; ?>
                                                <div class="kanban-task-footer">
                                                    <?php if ($task['assigned_to']): ?>
                                                        <div class="small">
                                                            <i class="fas fa-user me-1"></i> 
                                                            <?php 
                                                            foreach ($members as $member) {
                                                                if ($member['id'] == $task['assigned_to']) {
                                                                    echo $member['name'];
                                                                    break;
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($task['due_date']): ?>
                                                        <div class="small">
                                                            <i class="fas fa-calendar me-1"></i> 
                                                            <?php echo date('M d, Y', strtotime($task['due_date'])); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="kanban-column-footer">
                            <button class="btn btn-sm btn-outline-primary w-100 add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-column-id="<?php echo $column['id']; ?>">
                                <i class="fas fa-plus me-1"></i> Add task
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add CSS and JavaScript for Kanban -->
<link rel="stylesheet" href="/assets/css/kanban.css">

<?php if ($project_id): ?>
<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add new task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTaskForm">
                <div class="modal-body">
                    <input type="hidden" name="column_id" id="add_task_column_id">
                    <input type="hidden" name="tab_id" id="add_task_tab_id" value="<?php echo $current_tab_id; ?>">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Task title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign to</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Unassigned</option>
                            <?php foreach ($members as $member): ?>
                                <option value="<?php echo $member['id']; ?>"><?php echo $member['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_task">
                    <input type="hidden" name="task_id" id="edit_task_id">
                    
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Task title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_assigned_to" class="form-label">Assign to</label>
                        <select class="form-select" id="edit_assigned_to" name="assigned_to">
                            <option value="">Unassigned</option>
                            <?php foreach ($members as $member): ?>
                                <option value="<?php echo $member['id']; ?>"><?php echo $member['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_due_date" class="form-label">Due date</label>
                        <input type="date" class="form-control" id="edit_due_date" name="due_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Task Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Change status for task "<span id="update_status_task_title"></span>"?</p>
                <input type="hidden" id="update_status_task_id">
                <input type="hidden" id="update_status_new_status">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary status-option" data-status="active">
                        <i class="fas fa-play me-1"></i> Active
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning status-option" data-status="pending">
                        <i class="fas fa-pause me-1"></i> Pending
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success status-option" data-status="completed">
                        <i class="fas fa-check me-1"></i> Completed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete task Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Delete task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the task "<span id="delete_task_title"></span>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTask">Delete</button>
                <input type="hidden" id="delete_task_id">
            </div>
        </div>
    </div>
</div>

<!-- Add Column Modal -->
<div class="modal fade" id="addColumnModal" tabindex="-1" aria-labelledby="addColumnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addColumnModalLabel">Add new column</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addColumnForm">
                <div class="modal-body">
                    <input type="hidden" name="project_id" value="<?php echo isset($project_id) ? $project_id : ''; ?>">
                    <input type="hidden" name="tab_id" id="add_column_tab_id" value="<?php echo isset($current_tab_id) ? $current_tab_id : ''; ?>">
                    
                    <div class="mb-3">
                        <label for="column_name" class="form-label">Column name</label>
                        <input type="text" class="form-control" id="column_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add column</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit column Modal -->
<div class="modal fade" id="editColumnModal" tabindex="-1" aria-labelledby="editColumnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editColumnModalLabel">Edit column</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_column">
                    <input type="hidden" name="column_id" id="edit_column_id">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="edit_column_name" class="form-label">Column name</label>
                        <input type="text" class="form-control" id="edit_column_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update column</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete column Modal -->
<div class="modal fade" id="deleteColumnModal" tabindex="-1" aria-labelledby="deleteColumnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteColumnModalLabel">Delete column</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the column "<span id="delete_column_name"></span>"?</p>
                <p class="text-danger">This action cannot be undone. All tasks in this column will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <form method="post" action="">
                    <input type="hidden" name="action" value="delete_column">
                    <input type="hidden" name="column_id" id="delete_column_id">
                    <?php echo csrf_field(); ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Tab Modal -->
<div class="modal fade" id="addTabModal" tabindex="-1" aria-labelledby="addTabModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTabModalLabel">Add new tab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTabForm" method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_tab">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="tab_name" class="form-label">Tab name</label>
                        <input type="text" class="form-control" id="tab_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add tab</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tab Modal -->
<div class="modal fade" id="editTabModal" tabindex="-1" aria-labelledby="editTabModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTabModalLabel">Edit tab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTabForm" method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_tab">
                    <input type="hidden" name="tab_id" id="edit_tab_id">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="edit_tab_name" class="form-label">Tab name</label>
                        <input type="text" class="form-control" id="edit_tab_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update tab</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Tab Modal -->
<div class="modal fade" id="deleteTabModal" tabindex="-1" aria-labelledby="deleteTabModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTabModalLabel">Delete tab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the tab "<span id="delete_tab_name"></span>"?</p>
                <p class="text-danger">This action cannot be undone. All columns and tasks in this tab will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <form method="post" action="">
                    <input type="hidden" name="action" value="delete_tab">
                    <input type="hidden" name="tab_id" id="delete_tab_id">
                    <?php echo csrf_field(); ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Tab</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Add Sortable.js library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script src="/assets/js/kanban.js"></script>

<script>

<?php if (isset($redirect_after_delete) && $redirect_after_delete): ?>
// Redirect after tab deletion
window.location.href = "?page=kanban&id=<?php echo $project_id; ?>";
<?php endif; ?>

</script>