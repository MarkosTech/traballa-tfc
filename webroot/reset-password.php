<?php
/**
 * Traballa - Password reset
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

define('INDEX_EXEC', true); // Allow execution of child scripts
require_once '../config/database.php';
require_once '../includes/Session.php';
require_once '../includes/functions.php';

// Initialize our session handler
$session = new Session($pdo);

// Redirect if already logged in
if ($session->get('user_id')) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';
$token = '';
$valid_token = false;
$user_id = 0;

// Check if token is provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Validate token using PDO prepared statement
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($reset) {
            $user_id = $reset['user_id'];
            $valid_token = true;
        } else {
            $error = "Invalid or expired token. Please request a new password reset link.";
        }
    } catch (PDOException $e) {
        $error = "Database error occurred. Please try again.";
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Reset Password Token Validation PDO Error: " . $e->getMessage());
        }
    }
} else {
    $error = "No reset token provided.";
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($password) || empty($confirm_password)) {
        $error = "Please enter both password fields";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        try {
            // Update password using PDO prepared statement
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            
            if ($update_stmt->execute([$hashed_password, $user_id])) {
                // Delete used token
                $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
                $delete_stmt->execute([$user_id]);
                
                $success = "Password has been reset successfully. You can now login with your new password.";
            } else {
                $error = "Error updating password. Please try again later.";
            }
        } catch (PDOException $e) {
            $error = "Database error occurred. Please try again.";
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Reset Password Update PDO Error: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Traballa Tracker</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <!-- Floating background shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="reset-password-container bounce-in">
        <div class="reset-password-header">
            <div class="icon-container">
                <i class="fas fa-key"></i>
            </div>
            <h2>Reset Password</h2>
            <p>Enter your new password below</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-primary">Go to Login</a>
            </div>
        <?php elseif ($valid_token): ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Enter your new password">
                    </div>
                    <div class="form-text">Password must be at least 6 characters</div>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-semibold">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm your new password">
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center mt-3">
                <a href="forgot-password.php" class="btn btn-primary">Request New Reset Link</a>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="login.php" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Back to Login
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

