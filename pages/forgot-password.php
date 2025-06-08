<?php
/**
 * Traballa - Forgot password
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

require_once '../config/database.php';
require_once '../includes/Session.php';
require_once '../includes/functions.php';

// Initialize our session handler
$session = new Session($pdo);

// Redirect if already logged in
if ($session->get('user_id')) {
    $redirect_url = getDashboardUrl('index.php');
    header("Location: " . $redirect_url);
    exit();
}

$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    check_csrf();
    
    $email = sanitize($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store token in database
            $user_id = $user['id'];
            
            // Delete any existing tokens for this user
            $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $delete_stmt->execute([$user_id]);
            
            // Insert new token
            $insert_stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            
            if ($insert_stmt->execute([$user_id, $token, $expires])) {
                // Get SMTP settings from config
                $smtp_settings = getSMTPSettings();
                
                // Send email with reset link
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset-password.php?token=" . $token;
                
                if ($smtp_settings && $smtp_settings['enabled']) {
                    // Use PHPMailer with SMTP settings
                    require '../vendor/autoload.php';
                    
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
                        $mail->addAddress($email, $user['name']);
                        
                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password reset request';
                        $mail->Body = "
                            <h2>Password reset request</h2>
                            <p>Hello {$user['name']},</p>
                            <p>You have requested to reset your password. Please click the link below to reset your password:</p>
                            <p><a href='$reset_link'>Reset Password</a></p>
                            <p>This link will expire in 1 hour.</p>
                            <p>If you did not request this, please ignore this email.</p>
                        ";
                        
                        $mail->send();
                        $success = "Password reset link has been sent to your email address.";
                    } catch (Exception $e) {
                        $error = "Error sending email: " . $mail->ErrorInfo;
                    }
                } else {
                    // Fallback to basic mail function
                    $to = $email;
                    $subject = "Password reset request";
                    $message = "Hello {$user['name']},\n\n";
                    $message .= "You have requested to reset your password. Please click the link below to reset your password:\n\n";
                    $message .= "$reset_link\n\n";
                    $message .= "This link will expire in 1 hour.\n\n";
                    $message .= "If you did not request this, please ignore this email.";
                    $headers = "From: noreply@workhourscounter.com";
                    
                    if (mail($to, $subject, $message, $headers)) {
                        $success = "Password reset link has been sent to your email address.";
                    } else {
                        $error = "Error sending email. Please try again later.";
                    }
                }
            } else {
                $error = "Error generating reset token. Please try again later.";
            }
        } else {
            // Don't reveal if email exists or not for security
            $success = "If your email address exists in our database, you will receive a password recovery link.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Traballa Tracker</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Authentication CSS -->
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <!-- Floating background shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Floating particles container -->
    <div class="floating-particles" id="particleContainer"></div>

    <div class="forgot-password-container bounce-in">
        <div class="forgot-password-header">
            <div class="icon-container">
                <i class="fas fa-key"></i>
            </div>
            <h2>Forgot Password?</h2>
            <p>No worries! Enter your email and we'll send you a reset link</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" id="forgotPasswordForm" novalidate>
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Enter your email address" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           autocomplete="email">
                </div>
                <div class="invalid-feedback" id="emailError"></div>
            </div>
            
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <span class="btn-text">Send reset link</span>
                    <i class="fas fa-spinner fa-spin d-none" id="loadingIcon"></i>
                </button>
            </div>
            
            <div class="text-center">
                <a href="login.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('forgotPasswordForm');
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submitBtn');
            const loadingIcon = document.getElementById('loadingIcon');
            const btnText = document.querySelector('.btn-text');

            // Email validation
            function validateEmail(email) {
                if (!email.trim()) return 'Email address is required';
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) return 'Please enter a valid email address';
                return null;
            }

            function showError(input, message) {
                input.classList.add('is-invalid');
                const errorDiv = document.getElementById(input.id + 'Error');
                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            }

            function clearError(input) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                const errorDiv = document.getElementById(input.id + 'Error');
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            }

            // Real-time email validation
            emailInput.addEventListener('blur', () => {
                const error = validateEmail(emailInput.value);
                if (error) {
                    showError(emailInput, error);
                } else {
                    clearError(emailInput);
                }
            });

            emailInput.addEventListener('input', () => {
                if (emailInput.classList.contains('is-invalid')) {
                    const error = validateEmail(emailInput.value);
                    if (!error) clearError(emailInput);
                }
            });

            // Form submission
            form.addEventListener('submit', (e) => {
                const emailError = validateEmail(emailInput.value);
                
                if (emailError) {
                    e.preventDefault();
                    showError(emailInput, emailError);
                    form.classList.add('shake');
                    setTimeout(() => form.classList.remove('shake'), 820);
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                loadingIcon.classList.remove('d-none');
                btnText.textContent = 'Sending...';
            });

            // Create floating particles
            function createParticle() {
                const particle = document.createElement('div');
                const colors = ['#2196F3', '#1976D2', '#64B5F6', '#4ecdc4', '#ffe66d'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                
                particle.className = 'particle';
                particle.style.cssText = `
                    width: ${Math.random() * 4 + 2}px;
                    height: ${Math.random() * 4 + 2}px;
                    background: ${color};
                    left: ${Math.random() * window.innerWidth}px;
                    top: ${window.innerHeight + 10}px;
                    --random-x: ${Math.random() * 100 - 50}px;
                `;
                
                document.getElementById('particleContainer').appendChild(particle);
                setTimeout(() => particle.remove(), 4000);
            }

            // Create particles periodically
            setInterval(createParticle, 2000);

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 5000);
            });

            // Enhanced error handling for server-side errors
            const errorAlert = document.querySelector('.alert-danger');
            if (errorAlert) {
                setTimeout(() => {
                    form.classList.add('shake');
                    setTimeout(() => form.classList.remove('shake'), 820);
                }, 100);
            }

            // Success message handling
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                // Auto-redirect to login after 5 seconds
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 5000);
            }
        });
    </script>

    <!-- Minimal Footer -->
    <footer class="minimal-footer text-center">
        <div class="container">
            <div class="footer-links">
                <a href="privacy-policy" class="footer-link text-white">
                    <i class="fas fa-shield-alt me-1"></i>Privacy policy
                </a>
                <span class="footer-separator">•</span>
                <a href="terms-of-service.php" class="footer-link text-white">
                    <i class="fas fa-user-shield me-1"></i>Terms of service
                </a>
                <span class="footer-separator">•</span>
                <a href="https://github.com/markostech/traballa-tfc" target="_blank" class="footer-link text-white">
                    <i class="fab fa-github me-1"></i>Source code
                </a>
            </div>
            <div class="mt-2">
                <small class="text-white">
                    &copy; <?php echo date('Y'); ?> Traballa - 
                    <a href="https://opensource.org/licenses/MIT" target="_blank" class="footer-link text-white">MIT License</a>
                </small>
            </div>
        </div>
    </footer>

</body>
</html>

