<?php
/**
 * Traballa - Organizations
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

// Process organization actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Validate CSRF token
   check_csrf();
   
   if (isset($_POST['action'])) {
       $action = $_POST['action'];
       
       if ($action === 'add_organization' && isset($_POST['name']) && isset($_POST['description'])) {
           $name = sanitize($_POST['name']);
           $description = sanitize($_POST['description']);
           
           // Insert new organization
           $stmt = $pdo->prepare("INSERT INTO organizations (name, description) VALUES (?, ?)");
           if ($stmt->execute([$name, $description])) {
               $organization_id = $pdo->lastInsertId();
               
               // Add current user as organization admin
               $user_id = $_SESSION['user_id'];
               $stmt = $pdo->prepare("INSERT INTO organization_members (organization_id, user_id, is_admin) VALUES (?, ?, 1)");
               $stmt->execute([$organization_id, $user_id]);
               
               $success = "Organization added successfully";
           } else {
               $error = "Error adding organization";
           }
       } elseif ($action === 'update_organization' && isset($_POST['organization_id']) && isset($_POST['name']) && isset($_POST['description'])) {
           $organization_id = (int)$_POST['organization_id'];
           $name = sanitize($_POST['name']);
           $description = sanitize($_POST['description']);
           
           // Check if user has permission to update this organization
           if (isAdmin() || isOrganizationAdmin($pdo, $_SESSION['user_id'], $organization_id)) {
               // Update organization
               $stmt = $pdo->prepare("UPDATE organizations SET name = ?, description = ? WHERE id = ?");
               if ($stmt->execute([$name, $description, $organization_id])) {
                   $success = "Organization updated successfully";
               } else {
                   $error = "Error updating organization";
               }
           } else {
               $error = "You do not have permission to update this organization";
           }
       } elseif ($action === 'delete_organization' && isset($_POST['organization_id'])) {
           $organization_id = (int)$_POST['organization_id'];
           
           // Check if user has permission to delete this organization
           // (isAdmin() || isOrganizationAdmin($pdo, $_SESSION['user_id'], $organization_id))
           if (isAdmin()) {
               // Delete organization
               $stmt = $pdo->prepare("DELETE FROM organizations WHERE id = ?");
               if ($stmt->execute([$organization_id])) {
                   $success = "Organization deleted successfully";
               } else {
                   $error = "Error deleting organization";
               }
           } else {
               $error = "You do not have permission to delete this organization";
           }
       } elseif ($action === 'add_member' && isset($_POST['organization_id']) && isset($_POST['email'])) {
           $organization_id = (int)$_POST['organization_id'];
           $email = sanitize($_POST['email']);
           $is_admin = isset($_POST['is_admin']) ? 1 : 0;
           
           // Check if user has permission to add members to this organization
           if (isAdmin() || isOrganizationAdmin($pdo, $_SESSION['user_id'], $organization_id)) {
               // Check if user exists
               $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
               $stmt->execute([$email]);
               
               if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $user_id = $user['id'];
                   
                   // Check if user is already a member of this organization
                   $check_stmt = $pdo->prepare("SELECT * FROM organization_members WHERE organization_id = ? AND user_id = ?");
                   $check_stmt->execute([$organization_id, $user_id]);
                   
                   if ($check_stmt->fetch()) {
                       // Update existing membership
                       $stmt = $pdo->prepare("UPDATE organization_members SET is_admin = ? WHERE organization_id = ? AND user_id = ?");
                       if ($stmt->execute([$is_admin, $organization_id, $user_id])) {
                           $success = "User role updated successfully";
                       } else {
                           $error = "Error updating user role";
                       }
                   } else {
                       // Add new member
                       $stmt = $pdo->prepare("INSERT INTO organization_members (organization_id, user_id, is_admin) VALUES (?, ?, ?)");
                       if ($stmt->execute([$organization_id, $user_id, $is_admin])) {
                           $success = "User added to organization successfully";
                       } else {
                           $error = "Error adding user to organization";
                       }
                   }
               } else {
                   // User doesn't exist, create a new user account
                   $name = isset($_POST['name']) ? sanitize($_POST['name']) : "New User";
                   $role = $is_admin ? 'user' : 'employee';
                   
                   // Generate a random password
                   $random_password = bin2hex(random_bytes(8));
                   $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
                   
                   // Insert new user
                   $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                   if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                       $user_id = $pdo->lastInsertId();
                       
                       // Add to organization
                       $stmt = $pdo->prepare("INSERT INTO organization_members (organization_id, user_id, is_admin) VALUES (?, ?, ?)");
                       if ($stmt->execute([$organization_id, $user_id, $is_admin])) {
                           // Send invitation email with credentials
                           $organization_stmt = $pdo->prepare("SELECT name FROM organizations WHERE id = ?");
                           $organization_stmt->execute([$organization_id]);
                           $organization_name = $organization_stmt->fetchColumn();
                           
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
                                   $mail->Subject = "You've been added to $organization_name";
                                   $mail->Body = "
                                       <h2>Welcome to Traballa Counter</h2>
                                       <p>Hello $name,</p>
                                       <p>You have been added to the organization <strong>$organization_name</strong>.</p>
                                       <p>Your account has been created with the following credentials:</p>
                                       <p><strong>Email:</strong> $email<br>
                                       <strong>Password:</strong> $random_password</p>
                                       <p>Please login at <a href='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}'>Traballa Counter</a> and change your password.</p>
                                   ";
                                   
                                   $mail->send();
                                   $success = "User added to organization successfully and invitation email sent.";
                               } catch (Exception $e) {
                                   $success = "User added to organization successfully but failed to send invitation email.";
                               }
                           } else {
                               // Fallback to basic mail function
                               $to = $email;
                               $subject = "You've been added to $organization_name";
                               $message = "Hello $name,

";
                               $message .= "You have been added to the organization $organization_name.

";
                               $message .= "Your account has been created with the following credentials:
";
                               $message .= "Email: $email
";
                               $message .= "Password: $random_password

";
                               $message .= "Please login and change your password.";
                               $headers = "From: noreply@workhourscounter.com";
                               
                               mail($to, $subject, $message, $headers);
                               $success = "User added to organization successfully and invitation email sent.";
                           }
                       } else {
                           $error = "Error adding user to organization";
                       }
                   } else {
                       $error = "Error creating user account";
                   }
               }
           } else {
               $error = "You do not have permission to add members to this organization";
           }
       } elseif ($action === 'remove_member' && isset($_POST['organization_id']) && isset($_POST['user_id'])) {
           $organization_id = (int)$_POST['organization_id'];
           $user_id = (int)$_POST['user_id'];
           
           // Check if user has permission to remove members from this organization
           if (isAdmin() || isOrganizationAdmin($pdo, $_SESSION['user_id'], $organization_id)) {
               // Remove member
               $stmt = $pdo->prepare("DELETE FROM organization_members WHERE organization_id = ? AND user_id = ?");
               if ($stmt->execute([$organization_id, $user_id])) {
                   $success = "User removed from organization successfully";
               } else {
                   $error = "Error removing user from organization";
               }
           } else {
               $error = "You do not have permission to remove members from this organization";
           }
       }
   }
}

// Get organizations based on user role
if (isAdmin()) {
   $organizations = getAllOrganizations($pdo);
} else {
   $organizations = getUserOrganizations($pdo, $_SESSION['user_id']);
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
   <h1 class="h3 mb-0">Organization Management</h1>
   <div class="d-flex gap-2">
       <button class="btn btn-outline-info help-btn" data-help-context="account-settings">
           <i class="fas fa-question-circle me-1"></i>Help
       </button>
       <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrganizationModal">
           <i class="fas fa-plus me-1"></i> Add organization
       </button>
   </div>
</div>

<?php if (isset($error)): ?>
   <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
   <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<!-- Organizations List -->
<div class="row">
   <?php if (empty($organizations)): ?>
       <div class="col-12">
           <div class="alert alert-info">
               <i class="fas fa-info-circle me-2"></i> No organizations found. Click the "Add Organization" button to create your first organization.
           </div>
       </div>
   <?php else: ?>
       <?php foreach ($organizations as $organization): ?>
           <div class="col-md-6 col-lg-4 mb-4">
               <div class="card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                       <h5 class="mb-0"><?php echo $organization['name']; ?></h5>
                       <div class="dropdown">
                           <button class="btn btn-sm btn-outline-secondary" type="button" id="orgMenu<?php echo $organization['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                           </button>
                           <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="orgMenu<?php echo $organization['id']; ?>">
                               <li>
                                   <button class="dropdown-item view-organization-btn" data-bs-toggle="modal" data-bs-target="#viewOrganizationModal" 
                                           data-id="<?php echo $organization['id']; ?>"
                                           data-name="<?php echo $organization['name']; ?>"
                                           data-description="<?php echo $organization['description']; ?>"
                                           data-created="<?php echo date('M d, Y', strtotime($organization['created_at'])); ?>">
                                       <i class="fas fa-eye me-1"></i> View Details
                                   </button>
                               </li>
                               <?php if (isAdmin() || (isset($organization['is_admin']) && $organization['is_admin'])): ?>
                               <li>
                                   <button class="dropdown-item edit-organization-btn" data-bs-toggle="modal" data-bs-target="#editOrganizationModal"
                                           data-id="<?php echo $organization['id']; ?>"
                                           data-name="<?php echo $organization['name']; ?>"
                                           data-description="<?php echo $organization['description']; ?>">
                                       <i class="fas fa-edit me-1"></i> Edit Organization
                                   </button>
                               </li>
                               <li>
                                   <button class="dropdown-item manage-members-btn" data-bs-toggle="modal" data-bs-target="#manageMembersModal"
                                           data-id="<?php echo $organization['id']; ?>"
                                           data-name="<?php echo $organization['name']; ?>">
                                       <i class="fas fa-users me-1"></i> Manage Members
                                   </button>
                               </li>
                               <li><hr class="dropdown-divider"></li>
                               <li>
                                   <button class="dropdown-item text-danger delete-organization-btn" data-bs-toggle="modal" data-bs-target="#deleteOrganizationModal"
                                           data-id="<?php echo $organization['id']; ?>"
                                           data-name="<?php echo $organization['name']; ?>">
                                       <i class="fas fa-trash me-1"></i> Delete Organization
                                   </button>
                               </li>
                               <?php endif; ?>
                           </ul>
                       </div>
                   </div>
                   <div class="card-body">
                       <p class="card-text">
                           <?php 
                               $desc = $organization['description'];
                               echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc; 
                           ?>
                       </p>
                       
                       <?php 
                           // Get organization statistics
                           $stats = getOrganizationStatistics($pdo, $organization['id']);
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
                               <div class="fw-bold"><?php echo $stats['project_count']; ?></div>
                               <div class="small text-muted">Projects</div>
                           </div>
                       </div>
                   </div>
                   <div class="card-footer d-flex justify-content-between align-items-center">
                       <?php if (isset($organization['is_admin']) && $organization['is_admin']): ?>
                           <span class="badge bg-primary">Admin</span>
                       <?php else: ?>
                           <span class="badge bg-secondary">Member</span>
                       <?php endif; ?>
                       <small class="text-muted">Created: <?php echo date('M d, Y', strtotime($organization['created_at'])); ?></small>
                   </div>
               </div>
           </div>
       <?php endforeach; ?>
   <?php endif; ?>
</div>

<!-- Add Organization Modal -->
<div class="modal fade" id="addOrganizationModal" tabindex="-1" aria-labelledby="addOrganizationModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="addOrganizationModalLabel">Add new organization</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form method="post" action="">
               <div class="modal-body">
                   <input type="hidden" name="action" value="add_organization">
                   <?php echo csrf_field(); ?>
                   <div class="mb-3">
                       <label for="name" class="form-label">Organization name</label>
                       <input type="text" class="form-control" id="name" name="name" required>
                   </div>
                   <div class="mb-3">
                       <label for="description" class="form-label">Description</label>
                       <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-primary">Add organization</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Edit Organization Modal -->
<div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-labelledby="editOrganizationModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="editOrganizationModalLabel">Edit organization</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form method="post" action="">
               <div class="modal-body">
                   <input type="hidden" name="action" value="update_organization">
                   <input type="hidden" name="organization_id" id="edit_organization_id">
                   <?php echo csrf_field(); ?>
                   <div class="mb-3">
                       <label for="edit_name" class="form-label">Organization name</label>
                       <input type="text" class="form-control" id="edit_name" name="name" required>
                   </div>
                   <div class="mb-3">
                       <label for="edit_description" class="form-label">Description</label>
                       <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-primary">Update organization</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- View Organization Modal -->
<div class="modal fade" id="viewOrganizationModal" tabindex="-1" aria-labelledby="viewOrganizationModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="viewOrganizationModalLabel">Organization details</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <div class="mb-3">
                   <label class="form-label fw-bold">Organization name</label>
                   <p id="view_name"></p>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-bold">Description</label>
                   <p id="view_description"></p>
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
               <h5 class="modal-title" id="manageMembersModalLabel">Manage Organization Members</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <h6 class="mb-3">Organization: <span id="manage_organization_name"></span></h6>
               
               <!-- Add Member Form -->
               <div class="card mb-4">
                   <div class="card-header">
                       <h6 class="mb-0">Add Member</h6>
                   </div>
                   <div class="card-body">
                       <form method="post" action="" id="addMemberForm">
                           <input type="hidden" name="action" value="add_member">
                           <input type="hidden" name="organization_id" id="manage_organization_id">
                           <?php echo csrf_field(); ?>
                           
                           <div class="row mb-3">
                               <div class="col-md-6">
                                   <label for="member_email" class="form-label">Email Address</label>
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
                               <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
                               <label class="form-check-label" for="is_admin">
                                   Make Organization Admin
                               </label>
                               <div class="form-text">Organization admins can add/remove members and update organization details</div>
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

<!-- Delete Organization Modal -->
<div class="modal fade" id="deleteOrganizationModal" tabindex="-1" aria-labelledby="deleteOrganizationModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="deleteOrganizationModalLabel">Delete Organization</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <p>Are you sure you want to delete <span id="delete_organization_name" class="fw-bold"></span>?</p>
               <p class="text-danger">This action cannot be undone. All projects and Traballa associated with this organization will also be deleted.</p>
           </div>
           <div class="modal-footer">
               <form method="post" action="">
                   <input type="hidden" name="action" value="delete_organization">
                   <input type="hidden" name="organization_id" id="delete_organization_id">
                   <?php echo csrf_field(); ?>
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-danger">Delete</button>
               </form>
           </div>
       </div>
   </div>
</div>

<!-- Pass current user ID to JavaScript -->
<script>
window.currentUserId = <?php echo $_SESSION['user_id']; ?>;
</script>

<!-- Organizations JavaScript -->
<script src="assets/js/organizations.js"></script>

