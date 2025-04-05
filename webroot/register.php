<?php
/**
 * Traballa - User Registration Page
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/workhours-tfc
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

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($pdo, $_POST['name']);
    $email = sanitize($pdo, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $privacy_consent = isset($_POST['privacy_consent']) ? 1 : 0;
    $data_processing_consent = isset($_POST['data_processing_consent']) ? 1 : 0;
    $work_tracking_consent = isset($_POST['work_tracking_consent']) ? 1 : 0;
    
    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (!$privacy_consent) {
        $error = "You must accept the Privacy Policy to register";
    } elseif (!$data_processing_consent) {
        $error = "You must consent to data processing for registration";
    } else {
        try {
            // Check if email already exists using PDO prepared statement
            $check_stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $check_stmt->execute([$email]);
            
            if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
                $error = "Email already exists. Please use a different email or try logging in.";
            } else {
                // Hash password and create user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $insert_stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'employee')");
                if ($insert_stmt->execute([$name, $email, $hashed_password])) {
                    $user_id = $pdo->lastInsertId();
                
                // Initialize GDPR compliance for new user
                $registration_data = [
                    'privacy_consent' => $privacy_consent,
                    'data_processing_consent' => $data_processing_consent,
                    'work_tracking_consent' => $work_tracking_consent
                ];
                
                
                $success = "Account created successfully! You can now log in.";
                // Auto-login the user
                $session->set('user_id', $user_id);
                $session->set('user_name', $name);
                $session->set('user_email', $email);
                $session->set('user_role', 'employee');
                header("Location: index.php");
                exit();
            } else {
                $error = "Error creating account. Please try again.";
            }
        }
    } catch (PDOException $e) {
        $error = "Database error occurred. Please try again.";
        // Log the actual error for debugging (in production, log to file)
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log("Registration PDO Error: " . $e->getMessage());
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
    <title>Traballa tracker - Register</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    
    <!-- Authentication CSS -->
    <link rel="stylesheet" href="assets/css/auth.css">
    
    <style>
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .home-link:hover {
            transform: translateY(-2px);
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Home Link -->
    <a href="landing.php" class="home-link">
        <i class="fas fa-home me-2"></i> Back to Home
    </a>
    
    <!-- Floating background shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container bounce-in">
        <div class="register-header text-center">
            <h2 class="fw-bold mb-2">Sign up</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" id="registerForm" novalidate class="register-form">
            <!-- Column 1 fields -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="name" name="name" required 
                           placeholder="Enter your full name" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                           autocomplete="name">
                </div>
                <div class="invalid-feedback" id="nameError"></div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Enter your email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           autocomplete="email">
                </div>
                <div class="invalid-feedback" id="emailError"></div>
            </div>
            
            <!-- Column 2 fields -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required 
                           placeholder="Create a strong password" autocomplete="new-password">
                    <button class="btn btn-toggle-password" type="button" id="togglePassword" tabindex="-1">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-meter">
                        <div class="strength-meter-fill" id="strengthMeter"></div>
                    </div>
                    <small class="strength-text" id="strengthText">Choose a strong password with at least 6 characters</small>
                </div>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirm your password" autocomplete="new-password">
                    <button class="btn btn-toggle-password" type="button" id="toggleConfirmPassword" tabindex="-1">
                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                    </button>
                </div>
                <div id="passwordMatch" class="mt-1"></div>
                <div class="invalid-feedback" id="confirmPasswordError"></div>
            </div>
            
            <!-- GDPR Consent Section - full width across columns -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="fas fa-shield-alt me-2"></i>Privacy & Data Protection
                </h6>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="privacy_consent" name="privacy_consent" required>
                    <label class="form-check-label" for="privacy_consent">
                        I have read and accept the 
                        <a href="privacy-policy.php" target="_blank" class="text-primary text-decoration-none">
                            <strong>Privacy Policy</strong>
                        </a> <span class="text-danger">*</span>
                    </label>
                    <div class="invalid-feedback" id="privacyError"></div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="data_processing_consent" name="data_processing_consent" required>
                    <label class="form-check-label" for="data_processing_consent">
                        I consent to the processing of my personal data for account management and service provision 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="invalid-feedback" id="dataProcessingError"></div>
                </div>
                
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    You can review and modify your consent preferences anytime in your 
                    <a href="gdpr.php" class="text-primary text-decoration-none">GDPR Rights</a> settings.
                </small>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <span class="btn-text">Create account</span>
                    <i class="fas fa-spinner fa-spin d-none" id="loadingIcon"></i>
                </button>
            </div>
            
            <div class="text-center">
                <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none fw-semibold">Sign in here</a></p>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const inputs = {
        name: document.getElementById('name'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        confirmPassword: document.getElementById('confirm_password'),
        privacyConsent: document.getElementById('privacy_consent'),
        dataProcessingConsent: document.getElementById('data_processing_consent'),
        workTrackingConsent: document.getElementById('work_tracking_consent')
    };
    const strengthMeter = document.getElementById('strengthMeter');
    const strengthText = document.getElementById('strengthText');
    const passwordMatch = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnText = document.querySelector('.btn-text');

    // Enhanced eye tracking with smooth movement
    let mousePosition = { x: 0, y: 0 };
    let isAnimating = false;

    document.addEventListener('mousemove', (e) => {
        mousePosition.x = e.clientX;
        mousePosition.y = e.clientY;
        
        if (!isAnimating) {
            isAnimating = true;
            requestAnimationFrame(updateEyes);
        }
    });

    function updateEyes() {
        const rect = cat.svg.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        cat.pupils.forEach((pupil, index) => {
            const eyeX = index === 0 ? centerX - 25 : centerX + 25;
            const eyeY = centerY - 10;
            
            const angle = Math.atan2(mousePosition.y - eyeY, mousePosition.x - eyeX);
            const distance = Math.min(4, 
                Math.hypot(mousePosition.x - eyeX, mousePosition.y - eyeY) / 20
            );

            const moveX = Math.cos(angle) * distance;
            const moveY = Math.sin(angle) * distance;

            pupil.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        isAnimating = false;
    }

    // Password visibility toggles
    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = inputs.password.getAttribute('type') === 'password' ? 'text' : 'password';
        inputs.password.setAttribute('type', type);
        const icon = document.getElementById('togglePasswordIcon');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
        
        // Cat animation
        cat.svg.style.transform = 'scale(1.03) rotate(2deg)';
        setTimeout(() => {
            cat.svg.style.transform = '';
        }, 200);
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const type = inputs.confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        inputs.confirmPassword.setAttribute('type', type);
        const icon = document.getElementById('toggleConfirmPasswordIcon');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Enhanced password strength checker
    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = [];

        // Length check
        if (password.length >= 8) score += 2;
        else if (password.length >= 6) score += 1;
        else feedback.push('At least 6 characters');

        // Character variety checks
        if (/[a-z]/.test(password)) score += 1;
        else feedback.push('lowercase letter');

        if (/[A-Z]/.test(password)) score += 1;
        else feedback.push('uppercase letter');

        if (/[0-9]/.test(password)) score += 1;
        else feedback.push('number');

        if (/[^a-zA-Z0-9]/.test(password)) score += 2;
        else feedback.push('special character');

        // Common patterns (subtract points)
        if (/(.)\1{2,}/.test(password)) score -= 1; // Repeated characters
        if (/123|abc|qwe|asd/i.test(password)) score -= 1; // Common sequences

        let strength, className, textClassName;
        
        if (score <= 2) {
            strength = 'Very weak';
            className = 'strength-very-weak';
            textClassName = 'text-very-weak';
        } else if (score <= 4) {
            strength = 'Weak';
            className = 'strength-weak';
            textClassName = 'text-weak';
        } else if (score <= 6) {
            strength = 'Fair';
            className = 'strength-fair';
            textClassName = 'text-fair';
        } else if (score <= 8) {
            strength = 'Good';
            className = 'strength-good';
            textClassName = 'text-good';
        } else {
            strength = 'Strong';
            className = 'strength-strong';
            textClassName = 'text-strong';
        }

        return { score, strength, className, textClassName, feedback };
    }

    // Form validation functions
    function validateName(name) {
        if (!name.trim()) return 'Name is required';
        if (name.trim().length < 2) return 'Name must be at least 2 characters';
        if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(name)) return 'Name contains invalid characters';
        return null;
    }

    function validateEmail(email) {
        if (!email.trim()) return 'Email is required';
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) return 'Please enter a valid email address';
        return null;
    }

    function validatePassword(password) {
        if (!password) return 'Password is required';
        if (password.length < 6) return 'Password must be at least 6 characters';
        return null;
    }

    function validateConfirmPassword(password, confirmPassword) {
        if (!confirmPassword) return 'Please confirm your password';
        if (password !== confirmPassword) return 'Passwords do not match';
        return null;
    }

    function validateGDPRConsents() {
        const privacyError = inputs.privacyConsent.checked ? null : 'You must accept the Privacy Policy to register';
        const dataProcessingError = inputs.dataProcessingConsent.checked ? null : 'You must consent to data processing for registration';
        
        return { privacyError, dataProcessingError };
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

    // Real-time validation
    inputs.name.addEventListener('blur', () => {
        const error = validateName(inputs.name.value);
        if (error) {
            showError(inputs.name, error);
        } else {
            clearError(inputs.name);
        }
        validateForm();
    });

    inputs.name.addEventListener('input', () => {
        if (inputs.name.classList.contains('is-invalid')) {
            const error = validateName(inputs.name.value);
            if (!error) clearError(inputs.name);
        }
        validateForm();
    });

    inputs.email.addEventListener('blur', () => {
        const error = validateEmail(inputs.email.value);
        if (error) {
            showError(inputs.email, error);
        } else {
            clearError(inputs.email);
        }
        validateForm();
    });

    inputs.email.addEventListener('input', () => {
        if (inputs.email.classList.contains('is-invalid')) {
            const error = validateEmail(inputs.email.value);
            if (!error) clearError(inputs.email);
        }
        validateForm();
    });

    // Password strength and validation
    inputs.password.addEventListener('input', function() {
        const password = this.value;
        const result = checkPasswordStrength(password);
        
        // Update strength meter
        strengthMeter.className = 'strength-meter-fill ' + result.className;
        strengthText.className = 'strength-text ' + result.textClassName;
        strengthText.textContent = result.strength + ' password';

        // Show/hide sunglasses
        if (password.length > 0) {
            cat.svg.classList.add('covering-eyes');
        } else {
            cat.svg.classList.remove('covering-eyes');
        }

        // Validate password
        const error = validatePassword(password);
        if (error) {
            showError(inputs.password, error);
        } else {
            clearError(inputs.password);
        }

        // Revalidate confirm password if it has a value
        if (inputs.confirmPassword.value) {
            validatePasswordMatch();
        }

        validateForm();
    });

    // Confirm password validation
    function validatePasswordMatch() {
        const password = inputs.password.value;
        const confirmPassword = inputs.confirmPassword.value;

        if (!confirmPassword) {
            passwordMatch.innerHTML = '';
            return null;
        }

        if (password === confirmPassword) {
            passwordMatch.innerHTML = '<small class="text-success fw-semibold"><i class="fas fa-check-circle"></i> Passwords match perfectly!</small>';
            clearError(inputs.confirmPassword);
            return true;
        } else {
            passwordMatch.innerHTML = '<small class="text-danger fw-semibold"><i class="fas fa-exclamation-circle"></i> Passwords do not match</small>';
            showError(inputs.confirmPassword, 'Passwords do not match');
            return false;
        }
    }

    inputs.confirmPassword.addEventListener('input', function() {
        validatePasswordMatch();
        validateForm();
    });

    inputs.confirmPassword.addEventListener('blur', function() {
        const error = validateConfirmPassword(inputs.password.value, inputs.confirmPassword.value);
        if (error) {
            showError(inputs.confirmPassword, error);
        }
        validateForm();
    });

    // GDPR consent validation
    inputs.privacyConsent.addEventListener('change', function() {
        const gdprErrors = validateGDPRConsents();
        if (gdprErrors.privacyError) {
            showError(inputs.privacyConsent, gdprErrors.privacyError);
        } else {
            clearError(inputs.privacyConsent);
        }
        validateForm();
    });

    inputs.dataProcessingConsent.addEventListener('change', function() {
        const gdprErrors = validateGDPRConsents();
        if (gdprErrors.dataProcessingError) {
            showError(inputs.dataProcessingConsent, gdprErrors.dataProcessingError);
        } else {
            clearError(inputs.dataProcessingConsent);
        }
        validateForm();
    });

    inputs.workTrackingConsent.addEventListener('change', function() {
        validateForm(); // This one is optional, so just revalidate form
    });

    // Form validation
    function validateForm() {
        const nameValid = !validateName(inputs.name.value);
        const emailValid = !validateEmail(inputs.email.value);
        const passwordValid = !validatePassword(inputs.password.value);
        const confirmPasswordValid = !validateConfirmPassword(inputs.password.value, inputs.confirmPassword.value);
        const gdprErrors = validateGDPRConsents();
        const gdprValid = !gdprErrors.privacyError && !gdprErrors.dataProcessingError;

        const isValid = nameValid && emailValid && passwordValid && confirmPasswordValid && gdprValid;

        submitBtn.disabled = !isValid;
        
        // Cat mood changes
        if (isValid) {
            cat.svg.classList.add('happy-cat');
            cat.container.classList.add('pulse');
        } else {
            cat.svg.classList.remove('happy-cat');
            cat.container.classList.remove('pulse');
        }
        
        return isValid;
    }

    // Enhanced cat interactions
    inputs.password.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(-3deg) scale(1.05)';
        cat.svg.classList.add('covering-eyes');
    });

    inputs.password.addEventListener('blur', () => {
        cat.svg.style.transform = '';
        if (!inputs.password.value) {
            cat.svg.classList.remove('covering-eyes');
        }
    });

    inputs.confirmPassword.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(-3deg) scale(1.05)';
        cat.svg.classList.add('covering-eyes');
    });

    inputs.confirmPassword.addEventListener('blur', () => {
        cat.svg.style.transform = '';
        if (!inputs.confirmPassword.value && !inputs.password.value) {
            cat.svg.classList.remove('covering-eyes');
        }
    });

    inputs.name.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(2deg) scale(1.03)';
    });

    inputs.name.addEventListener('blur', () => {
        cat.svg.style.transform = '';
    });

    inputs.email.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(1deg) scale(1.02)';
    });

    inputs.email.addEventListener('blur', () => {
        cat.svg.style.transform = '';
    });

    // Form submission with enhanced feedback
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Perform final validation
        const isFormValid = validateForm();
        
        // Check GDPR consents specifically
        const gdprErrors = validateGDPRConsents();
        if (gdprErrors.privacyError) {
            showError(inputs.privacyConsent, gdprErrors.privacyError);
        }
        if (gdprErrors.dataProcessingError) {
            showError(inputs.dataProcessingConsent, gdprErrors.dataProcessingError);
        }
        
        // Allow submission if basic fields are filled (less strict validation)
        const basicFieldsValid = inputs.name.value.trim() && 
                                inputs.email.value.trim() && 
                                inputs.password.value && 
                                inputs.confirmPassword.value &&
                                inputs.privacyConsent.checked &&
                                inputs.dataProcessingConsent.checked;
        
        if (!basicFieldsValid) {
            // Shake animation on invalid form
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 820);
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        loadingIcon.classList.remove('d-none');
        btnText.textContent = 'Creating Account...';
        
        // Cat celebration animation
        cat.svg.style.transform = 'translateY(-15px) scale(1.1) rotate(10deg)';
        cat.svg.classList.add('happy-cat');
        cat.container.classList.add('pulse');
        
        // Submit the form after animation
        setTimeout(() => {
            form.submit();
        }, 800);
    });

    // Enhanced error handling for server-side errors
    const alertElement = document.querySelector('.alert-danger');
    if (alertElement) {
        setTimeout(() => {
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 820);
        }, 100);
    }

    // Auto-hide alerts after 7 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 7000);
    });

    // Add floating particles effect
    function createParticle() {
        const particle = document.createElement('div');
        const colors = ['#2196F3', '#1976D2', '#64B5F6', '#4ecdc4', '#ffe66d'];
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        particle.style.cssText = `
            position: fixed;
            width: ${Math.random() * 6 + 2}px;
            height: ${Math.random() * 6 + 2}px;
            background: ${color};
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            left: ${Math.random() * window.innerWidth}px;
            top: ${window.innerHeight + 10}px;
            opacity: 0.7;
            animation: floatUp ${Math.random() * 3 + 3}s linear forwards;
        `;
        
        document.body.appendChild(particle);
        setTimeout(() => particle.remove(), 6000);
    }

    // Add CSS for floating particles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes floatUp {
            to {
                transform: translateY(-${window.innerHeight + 50}px) translateX(${Math.random() * 200 - 100}px) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Create particles periodically
    setInterval(createParticle, 1500);

    // Initialize form - enable submit button by default
    submitBtn.disabled = false;

    // Success message auto-redirect
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        cat.svg.classList.add('happy-cat');
        cat.container.classList.add('pulse');
        
        // Auto-redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 3000);
    }

    // Initialize cat appearance
    setTimeout(() => {
        cat.svg.style.opacity = '1';
        cat.svg.style.transform = 'translateY(0)';
    }, 200);
});
</script>

    <!-- Minimal Footer -->
    <footer class="minimal-footer text-center">
        <div class="container">
            <div class="footer-links">
                <a href="privacy-policy.php" class="footer-link text-white">
                    <i class="fas fa-shield-alt me-1"></i>Privacy policy
                </a>
                <span class="footer-separator">•</span>
                <a href="terms-of-service.php" class="footer-link text-white">
                    <i class="fas fa-user-shield me-1"></i>Terms of service
                </a>
                <span class="footer-separator">•</span>
                <a href="https://github.com/markostech/workhours-tfc" target="_blank" class="footer-link text-white">
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
