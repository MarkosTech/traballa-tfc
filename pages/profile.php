<?php
/**
 * Traballa - Profile
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

// Get user details
$user = getUserById($pdo, $_SESSION['user_id']);

// Process profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'update_profile' && isset($_POST['name']) && isset($_POST['email'])) {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $user_id = $_SESSION['user_id'];
        
        // Check if email already exists for another user
        $check_stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
        $check_stmt->execute([$email, $user_id]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "Email already exists";
        } else {
            // Update profile
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            if ($stmt->execute([$name, $email, $user_id])) {
                // Update session variables
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                $success = "Profile updated successfully";
                
                // Refresh user data
                $user = getUserById($pdo, $_SESSION['user_id']);
            } else {
                $error = "Error updating profile";
            }
        }
    } elseif ($action === 'change_password' && isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['user_id'];
        
        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $password_error = "Current password is incorrect";
        } elseif ($new_password !== $confirm_password) {
            $password_error = "New passwords do not match";
        } elseif (strlen($new_password) < 6) {
            $password_error = "New password must be at least 6 characters";
        } else {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            
            if ($stmt->execute([$hashed_password, $user_id])) {
                $password_success = "Password changed successfully";
            } else {
                $password_error = "Error changing password";
            }
        }
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">My Profile</h1>
    <button class="btn btn-outline-info help-btn" data-help-context="account-settings">
        <i class="fas fa-question-circle me-1"></i>Help
    </button>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Profile information</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" value="<?php echo ucfirst($user['role']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Member since</label>
                        <input type="text" class="form-control" id="created_at" value="<?php echo date('M d, Y', strtotime($user['created_at'])); ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Update profile</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Change Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <?php if (isset($password_error)): ?>
                    <div class="alert alert-danger"><?php echo $password_error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($password_success)): ?>
                    <div class="alert alert-success"><?php echo $password_success; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <input type="hidden" name="action" value="change_password">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm new password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change password</button>
                </form>
            </div>
        </div>
        
        <!-- Account Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Account statistics</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Traballa
                        <span class="badge bg-primary rounded-pill">
                            <?php 
                                $stmt = $pdo->prepare("SELECT SUM(total_hours) as total FROM work_hours WHERE user_id = ? AND status = 'completed'");
                                $stmt->execute([$_SESSION['user_id']]);
                                $total = $stmt->fetchColumn() ?? 0;
                                echo number_format($total, 1);
                            ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Work Sessions
                        <span class="badge bg-primary rounded-pill">
                            <?php 
                                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM work_hours WHERE user_id = ?");
                                $stmt->execute([$_SESSION['user_id']]);
                                echo $stmt->fetchColumn() ?? 0;
                            ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Average Hours/Session
                        <span class="badge bg-primary rounded-pill">
                            <?php 
                                $stmt = $pdo->prepare("SELECT AVG(total_hours) as avg FROM work_hours WHERE user_id = ? AND status = 'completed'");
                                $stmt->execute([$_SESSION['user_id']]);
                                $avg = $stmt->fetchColumn() ?? 0;
                                echo number_format($avg, 1);
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

