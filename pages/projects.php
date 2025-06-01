<?php
/**
 * Traballa - Projects
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

// Check if user has management permissions
if (!hasManagementPermissions()) {
   echo '<div class="alert alert-danger">You do not have permission to access this page.</div>';
   exit();
}

// Process project actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['action'])) {
       $action = $_POST['action'];
       
       if ($action === 'add_project' && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
           $name = sanitize($_POST['name']);
           $description = sanitize($_POST['description']);
           $status = sanitize($_POST['status']);
           
           // Get organization_id from session or use the first organization the user is a member of
           $organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;
           
           if (!$organization_id) {
               // Get the first organization the user is a member of
               $user_orgs = getUserOrganizations($pdo, $_SESSION['user_id']);
               if (!empty($user_orgs)) {
                   $organization_id = $user_orgs[0]['id'];
               } else {
                   $error = "You must be a member of at least one organization to create a project.";
                   // Skip the rest of the processing
                   goto skip_processing;
               }
           }
           
           // Insert new project with organization_id
           $stmt = $pdo->prepare("INSERT INTO projects (name, description, status, organization_id) VALUES (?, ?, ?, ?)");
           if ($stmt->execute([$name, $description, $status, $organization_id])) {
               $project_id = $pdo->lastInsertId();
               
               // Add current user as project manager
               $user_id = $_SESSION['user_id'];
               $stmt = $pdo->prepare("INSERT INTO project_members (project_id, user_id, is_manager) VALUES (?, ?, 1)");
               $stmt->execute([$project_id, $user_id]);
               
               $success = "Project added successfully";
           } else {
               $error = "Error adding project";
           }
       } elseif ($action === 'update_project' && isset($_POST['project_id']) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
           $project_id = (int)$_POST['project_id'];
           $name = sanitize($_POST['name']);
           $description = sanitize($_POST['description']);
           $status = sanitize($_POST['status']);
           
           // Check if user has permission to update this project
           if (isAdmin() || isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id)) {
               // Update project
               $stmt = $pdo->prepare("UPDATE projects SET name = ?, description = ?, status = ? WHERE id = ?");
               if ($stmt->execute([$name, $description, $status, $project_id])) {
                   $success = "Project updated successfully";
               } else {
                   $error = "Error updating project";
               }
           } else {
               $error = "You do not have permission to update this project";
           }
       } elseif ($action === 'delete_project' && isset($_POST['project_id'])) {
           $project_id = (int)$_POST['project_id'];
           
           // Check if user has permission to delete this project
           // || isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id)
           if (isAdmin()) {
               // Delete project
               $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
               if ($stmt->execute([$project_id])) {
                   $success = "Project deleted successfully";
               } else {
                   $error = "Error deleting project";
               }
           } else {
               $error = "You do not have permission to delete this project";
           }
       } elseif ($action === 'add_member' && isset($_POST['project_id']) && isset($_POST['email'])) {
           $project_id = (int)$_POST['project_id'];
           $email = sanitize($_POST['email']);
           $is_manager = isset($_POST['is_manager']) ? 1 : 0;
           
           // Check if user has permission to add members to this project
           if (isAdmin() || isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id)) {
               // Check if user exists
               $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
               $stmt->execute([$email]);
               
               if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $user_id = $user['id'];
                   
                   // Check if user is already a member of this project
                   $check_stmt = $pdo->prepare("SELECT * FROM project_members WHERE project_id = ? AND user_id = ?");
                   $check_stmt->execute([$project_id, $user_id]);
                   
                   if ($check_stmt->fetch()) {
                       // Update existing membership
                       $stmt = $pdo->prepare("UPDATE project_members SET is_manager = ? WHERE project_id = ? AND user_id = ?");
                       if ($stmt->execute([$is_manager, $project_id, $user_id])) {
                           $success = "User role updated successfully";
                       } else {
                           $error = "Error updating user role";
                       }
                   } else {
                       // Add new member
                       $stmt = $pdo->prepare("INSERT INTO project_members (project_id, user_id, is_manager) VALUES (?, ?, ?)");
                       if ($stmt->execute([$project_id, $user_id, $is_manager])) {
                           $success = "User added to project successfully";
                       } else {
                           $error = "Error adding user to project";
                       }
                   }
               } else {
                   // User doesn't exist, create a new user account
                   $name = isset($_POST['name']) ? sanitize($_POST['name']) : "New User";
                   $role = $is_manager ? 'user' : 'employee';
                   
                   // Generate a random password
                   $random_password = bin2hex(random_bytes(8));
                   $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
                   
                   // Insert new user
                   $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                   if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                       $user_id = $pdo->lastInsertId();
                       
                       // Get project's organization
                       $org_stmt = $pdo->prepare("SELECT organization_id FROM projects WHERE id = ?");
                       $org_stmt->execute([$project_id]);
                       $organization_id = $org_stmt->fetchColumn();
                       
                       // Add user to organization
                       $org_member_stmt = $pdo->prepare("INSERT INTO organization_members (organization_id, user_id, is_admin) VALUES (?, ?, 0)");
                       $org_member_stmt->execute([$organization_id, $user_id]);
                       
                       // Add to project
                       $project_member_stmt = $pdo->prepare("INSERT INTO project_members (project_id, user_id, is_manager) VALUES (?, ?, ?)");
                       if ($project_member_stmt->execute([$project_id, $user_id, $is_manager])) {
                           // Send invitation email with credentials
                           $project_stmt = $pdo->prepare("SELECT name FROM projects WHERE id = ?");
                           $project_stmt->execute([$project_id]);
                           $project_name = $project_stmt->fetchColumn();
                           
                           // Get SMTP settings from config
                           $smtp_settings = getSMTPSettings();
                           
                           if ($smtp_settings && $smtp_settings['enabled']) {
                               // Use PHPMailer with SMTP settings
                               require 'vendor/autoload.php';
                               
                               $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                               
                               try {
                                   // Server settings
                                   $mail->isSMTP();
                                   $mail->Host = $smtp_settings['host'];
                                   $mail->SMTPAuth = true;
                                   $mail->Username = $smtp_settings['username'];
                                   $mail->Password = $smtp_settings['password'];
                                   $mail->SMTPSecure = $smtp_settings['encryption'];
                                   $mail->Port = $smtp_settings['port'];
                                   
                                   // Recipients
                                   $mail->setFrom($smtp_settings['from_email'], $smtp_settings['from_name']);
                                   $mail->addAddress($email, $name);
                                   
                                   // Content
                                   $mail->isHTML(true);
                                   $mail->Subject = "You've been added to $project_name";
                                   $mail->Body = "
                                       <h2>Welcome to Traballa Counter</h2>
                                       <p>Hello $name,</p>
                                       <p>You have been added to the project <strong>$project_name</strong>.</p>
                                       <p>Your account has been created with the following credentials:</p>
                                       <p><strong>Email:</strong> $email<br>
                                       <strong>Password:</strong> $random_password</p>
                                       <p>Please login at <a href='http://{$_SERVER['HTTP_HOST']}'>Traballa Counter</a> and change your password.</p>
                                   ";
                                   
                                   $mail->send();
                                   $success = "User added to project successfully and invitation email sent.";
                               } catch (Exception $e) {
                                   $success = "User added to project successfully but failed to send invitation email.";
                               }
                           } else {
                               // Fallback to basic mail function
                               $to = $email;
                               $subject = "You've been added to $project_name";
                               $message = "Hello $name,\n\n";
                               $message .= "You have been added to the project $project_name.\n\n";
                               $message .= "Your account has been created with the following credentials:\n";
                               $message .= "Email: $email\n";
                               $message .= "Password: $random_password\n\n";
                               $message .= "Please login and change your password.";
                               $headers = "From: noreply@workhourscounter.com";
                               
                               mail($to, $subject, $message, $headers);
                               $success = "User added to project successfully and invitation email sent.";
                           }
                       } else {
                           $error = "Error adding user to project";
                       }
                   } else {
                       $error = "Error creating user account";
                   }
               }
           } else {
               $error = "You do not have permission to add members to this project";
           }
       } elseif ($action === 'remove_member' && isset($_POST['project_id']) && isset($_POST['user_id'])) {
           $project_id = (int)$_POST['project_id'];
           $user_id = (int)$_POST['user_id'];
           
           // Check if user has permission to remove members from this project
           if (isAdmin() || isProjectManagerOf($pdo, $_SESSION['user_id'], $project_id)) {
               // Remove member
               $stmt = $pdo->prepare("DELETE FROM project_members WHERE project_id = ? AND user_id = ?");
               if ($stmt->execute([$project_id, $user_id])) {
                   $success = "User removed from project successfully";
               } else {
                   $error = "Error removing user from project";
               }
           } else {
               $error = "You do not have permission to remove members from this project";
           }
       }
   }
}

// Label for skipping processing in case of errors
skip_processing:

// Get projects based on user role and organization
$organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;

if (isAdmin()) {
   $projects = $organization_id ? getAllProjects($pdo, $organization_id) : getAllProjects($pdo);
} else {
   $projects = $organization_id ? getUserProjects($pdo, $_SESSION['user_id'], $organization_id) : getUserProjects($pdo, $_SESSION['user_id']);
}
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
echo $breadcrumb->render(current_route());
?>

<div class="d-flex justify-content-between align-items-center mb-4">
   <h1 class="h3 mb-0">Project management</h1>
   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
       <i class="fas fa-plus me-1"></i> Add project
   </button>
</div>

<?php if (isset($error)): ?>
   <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
   <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<!-- Projects List -->
<div class="row">
   <?php if (empty($projects)): ?>
       <div class="col-12">
           <div class="alert alert-info">
               <i class="fas fa-info-circle me-2"></i> No projects found. Click the "Add project" button to create your first project.
           </div>
       </div>
   <?php else: ?>
       <?php foreach ($projects as $project): ?>
           <div class="col-md-6 col-lg-4 mb-4">
               <div class="card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                       <h5 class="mb-0"><?php echo $project['name']; ?></h5>
                       <div class="dropdown">
                           <button class="btn btn-sm btn-outline-secondary" type="button" id="projectMenu<?php echo $project['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                           </button>
                           <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="projectMenu<?php echo $project['id']; ?>">
                               <li>
                                   <button class="dropdown-item view-project-btn" data-bs-toggle="modal" data-bs-target="#viewProjectModal" 
                                           data-id="<?php echo $project['id']; ?>"
                                           data-name="<?php echo $project['name']; ?>"
                                           data-description="<?php echo $project['description']; ?>"
                                           data-status="<?php echo $project['status']; ?>"
                                           data-created="<?php echo date('M d, Y', strtotime($project['created_at'])); ?>"
                                           data-organization="<?php echo isset($project['organization_name']) ? $project['organization_name'] : ''; ?>">
                                       <i class="fas fa-eye me-1"></i> View details
                                   </button>
                               </li>
                               <li>
                                   <button class="dropdown-item edit-project-btn" data-bs-toggle="modal" data-bs-target="#editProjectModal"
                                           data-id="<?php echo $project['id']; ?>"
                                           data-name="<?php echo $project['name']; ?>"
                                           data-description="<?php echo $project['description']; ?>"
                                           data-status="<?php echo $project['status']; ?>">
                                       <i class="fas fa-edit me-1"></i> Edit project
                                   </button>
                               </li>
                               <li>
                                   <button class="dropdown-item manage-members-btn" data-bs-toggle="modal" data-bs-target="#manageMembersModal"
                                           data-id="<?php echo $project['id']; ?>"
                                           data-name="<?php echo $project['name']; ?>">
                                       <i class="fas fa-users me-1"></i> Manage members
                                   </button>
                               </li>
                               <li><hr class="dropdown-divider"></li>
                               <li>
                                   <button class="dropdown-item text-danger delete-project-btn" data-bs-toggle="modal" data-bs-target="#deleteProjectModal"
                                           data-id="<?php echo $project['id']; ?>"
                                           data-name="<?php echo $project['name']; ?>">
                                       <i class="fas fa-trash me-1"></i> Delete project
                                   </button>
                               </li>
                           </ul>
                       </div>
                   </div>
                   <div class="card-body">
                       <p class="card-text">
                           <?php 
                               $desc = $project['description'];
                               echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc; 
                           ?>
                       </p>
                       
                       <?php 
                           // Get project statistics
                           $stats = getProjectStatistics($pdo, $project['id']);
                       ?>
                       
                       <div class="row text-center mt-3">
                           <div class="col-4">
                               <div class="fw-bold"><?php echo number_format($stats['total_hours'], 1); ?></div>
                               <div class="small text-muted">Hours</div>
                           </div>
                           <div class="col-4">
                               <div class="fw-bold"><?php echo $stats['member_count']; ?></div>
                               <div class="small text-muted">Members</div>
                           </div>
                           <div class="col-4">
                               <div class="fw-bold"><?php echo $stats['active_sessions']; ?></div>
                               <div class="small text-muted">Active</div>
                           </div>
                       </div>
                   </div>
                   <div class="card-footer d-flex justify-content-between align-items-center">
                       <span class="badge <?php 
                           echo $project['status'] === 'active' ? 'bg-success' : 
                               ($project['status'] === 'completed' ? 'bg-secondary' : 'bg-warning'); 
                       ?>">
                           <?php echo ucfirst($project['status']); ?>
                       </span>
                       <small class="text-muted">
                           <?php if (isset($project['organization_name'])): ?>
                               <i class="fas fa-building me-1"></i> <?php echo $project['organization_name']; ?>
                           <?php endif; ?>
                       </small>
                   </div>
               </div>
           </div>
       <?php endforeach; ?>
   <?php endif; ?>
</div>

<!-- Add project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="addProjectModalLabel">Add new project</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form method="post" action="">
               <div class="modal-body">
                   <input type="hidden" name="action" value="add_project">
                   
                   <?php if (!isset($_SESSION['current_organization_id'])): ?>
                   <div class="alert alert-warning">
                       <i class="fas fa-exclamation-triangle me-2"></i> No organization selected. The project will be added to your first available organization.
                   </div>
                   <?php endif; ?>
                   
                   <div class="mb-3">
                       <label for="name" class="form-label">Project name</label>
                       <input type="text" class="form-control" id="name" name="name" required>
                   </div>
                   <div class="mb-3">
                       <label for="description" class="form-label">Description</label>
                       <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                   </div>
                   <div class="mb-3">
                       <label for="status" class="form-label">Status</label>
                       <select class="form-select" id="status" name="status" required>
                           <option value="active">Active</option>
                           <option value="on_hold">On Hold</option>
                           <option value="completed">Completed</option>
                       </select>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-primary">Add project</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="editProjectModalLabel">Edit project</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form method="post" action="">
               <div class="modal-body">
                   <input type="hidden" name="action" value="update_project">
                   <input type="hidden" name="project_id" id="edit_project_id">
                   <div class="mb-3">
                       <label for="edit_name" class="form-label">Project name</label>
                       <input type="text" class="form-control" id="edit_name" name="name" required>
                   </div>
                   <div class="mb-3">
                       <label for="edit_description" class="form-label">Description</label>
                       <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                   </div>
                   <div class="mb-3">
                       <label for="edit_status" class="form-label">Status</label>
                       <select class="form-select" id="edit_status" name="status" required>
                           <option value="active">Active</option>
                           <option value="on_hold">On Hold</option>
                           <option value="completed">Completed</option>
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

<!-- View Project Modal -->
<div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="viewProjectModalLabel">Project details</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <div class="mb-3">
                   <label class="form-label fw-bold">Project name</label>
                   <p id="view_name"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Description</label>
                   <p id="view_description"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Organization</label>
                   <p id="view_organization"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Status</label>
                   <p id="view_status"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Created</label>
                   <p id="view_created"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Members</label>
                   <div id="view_members" class="list-group">
                       <!-- Members will be loaded dynamically -->
                   </div>
               </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           </div>
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
               <h6 class="mb-3">Project: <span id="manage_project_name"></span></h6>
               
               <!-- Add Member Form -->
               <div class="card mb-4">
                   <div class="card-header">
                       <h6 class="mb-0">Add member</h6>
                   </div>
                   <div class="card-body">
                       <form method="post" action="" id="addMemberForm">
                           <input type="hidden" name="action" value="add_member">
                           <input type="hidden" name="project_id" id="manage_project_id">
                           
                           <div class="row mb-3">
                               <div class="col-md-6">
                                   <label for="member_email" class="form-label">Email address</label>
                                   <input type="email" class="form-control" id="member_email" name="email" required>
                                   <div class="form-text">Enter the email of an existing user or a new user to invite</div>
                               </div>
                               <div class="col-md-6">
                                   <label for="member_name" class="form-label">Name (for new users)</label>
                                   <input type="text" class="form-control" id="member_name" name="name">
                                   <div class="form-text">Only required if inviting a new user</div>
                               </div>
                           </div>
                           
                           <div class="form-check mb-3">
                               <input class="form-check-input" type="checkbox" id="is_manager" name="is_manager">
                               <label class="form-check-label" for="is_manager">
                                   Make project manager
                               </label>
                               <div class="form-text">Project managers can add/remove members and update project details</div>
                           </div>
                           
                           <button type="submit" class="btn btn-primary">Add member</button>
                       </form>
                   </div>
               </div>
               
               <!-- Current Members List -->
               <div class="card">
                   <div class="card-header">
                       <h6 class="mb-0">Current members</h6>
                   </div>
                   <div class="card-body">
                       <div class="table-responsive">
                           <table class="table table-hover" id="membersTable">
                               <thead>
                                   <tr>
                                       <th>Name</th>
                                       <th>Email</th>
                                       <th>Role</th>
                                       <th>Joined</th>
                                       <th>Actions</th>
                                   </tr>
                               </thead>
                               <tbody id="members_list">
                                   <!-- Members will be loaded dynamically -->
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

<!-- Delete Project Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="deleteProjectModalLabel">Delete project</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <p>Are you sure you want to delete <span id="delete_project_name" class="fw-bold"></span>?</p>
               <p class="text-danger">This action cannot be undone. All Traballa associated with this project will also be deleted.</p>
           </div>
           <div class="modal-footer">
               <form method="post" action="">
                   <input type="hidden" name="action" value="delete_project">
                   <input type="hidden" name="project_id" id="delete_project_id">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-danger">Delete</button>
               </form>
           </div>
       </div>
   </div>
</div>

<script>
// Set current user ID for JavaScript access
window.currentUserId = <?php echo $_SESSION['user_id']; ?>;
</script>
<script src="assets/js/projects.js"></script>

