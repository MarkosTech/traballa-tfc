<?php
/**
 * Traballa - Login Page
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

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($pdo, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        try {
            // Check if user exists using PDO prepared statement
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables using our Session class
                    $session->set('user_id', $user['id']);
                    $session->set('user_name', $user['name']);
                    $session->set('user_email', $user['email']);
                    $session->set('user_role', $user['role']);
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid password";
                }
            } else {
                $error = "User not found";
            }
        } catch (PDOException $e) {
            $error = "Database error occurred. Please try again.";
            // Log the actual error for debugging (in production, log to file)
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Login PDO Error: " . $e->getMessage());
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
    <title>Traballa tracker - Login</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    
    <!-- Unified Authentication Styles -->
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
    </div>

    <div class="login-container">
        <div class="cat-mascot">
            <svg class="cat-svg" viewBox="0 0 300 300" width="130" height="130">
                <!-- Cat body -->
                <path class="cat-body" d="M150 250 C50 250 50 150 100 100 C100 50 200 50 200 100 C250 150 250 250 150 250 Z"/>
                
                <!-- Head -->
                <circle cx="150" cy="100" r="60" fill="#FDD835" stroke="#333" stroke-width="2"/>
                
                <!-- Ears -->
                <path d="M120 50 L130 30 L140 50 Z" fill="#FDD835" stroke="#333" stroke-width="2"/>
                <path d="M160 50 L170 30 L180 50 Z" fill="#FDD835" stroke="#333" stroke-width="2"/>
                <path d="M125 45 L130 35 L135 45 Z" fill="#FF6B9D"/>
                <path d="M165 45 L170 35 L175 45 Z" fill="#FF6B9D"/>
                
                <!-- Eyes -->
                <g class="cat-eyes">
                    <ellipse class="cat-eye" cx="125" cy="90" rx="12" ry="18" fill="white" stroke="#333" stroke-width="1"/>
                    <circle class="cat-pupil" cx="125" cy="90" r="6" fill="#333"/>
                    
                    <ellipse class="cat-eye" cx="175" cy="90" rx="12" ry="18" fill="white" stroke="#333" stroke-width="1"/>
                    <circle class="cat-pupil" cx="175" cy="90" r="6" fill="#333"/>
                </g>
                
                <!-- Nose -->
                <path d="M150 110 L145 118 L155 118 Z" fill="#FF6B9D"/>
                
                <!-- Mouth -->
                <path d="M150 118 Q140 125 130 120" fill="none" stroke="#333" stroke-width="2"/>
                <path d="M150 118 Q160 125 170 120" fill="none" stroke="#333" stroke-width="2"/>
                
                <!-- Whiskers -->
                <line x1="95" y1="100" x2="120" y2="105" stroke="#333" stroke-width="2"/>
                <line x1="95" y1="115" x2="120" y2="115" stroke="#333" stroke-width="2"/>
                <line x1="95" y1="130" x2="120" y2="125" stroke="#333" stroke-width="2"/>
                <line x1="205" y1="100" x2="180" y2="105" stroke="#333" stroke-width="2"/>
                <line x1="205" y1="115" x2="180" y2="115" stroke="#333" stroke-width="2"/>
                <line x1="205" y1="130" x2="180" y2="125" stroke="#333" stroke-width="2"/>
                
                <!-- Sunglasses -->
                <g class="sunglasses">
                    <ellipse cx="125" cy="90" rx="20" ry="22" fill="#1a1a1a" stroke="#333" stroke-width="2" fill-opacity="0.95"/>
                    <ellipse cx="175" cy="90" rx="20" ry="22" fill="#1a1a1a" stroke="#333" stroke-width="2" fill-opacity="0.95"/>
                    <line x1="145" y1="90" x2="155" y2="90" stroke="#333" stroke-width="3"/>
                    <path d="M105 90 L85 85" stroke="#333" stroke-width="3"/>
                    <path d="M195 90 L215 85" stroke="#333" stroke-width="3"/>
                    <ellipse cx="118" cy="83" rx="4" ry="2" fill="#4a4a4a" transform="rotate(-20, 118, 83)"/>
                    <ellipse cx="168" cy="83" rx="4" ry="2" fill="#4a4a4a" transform="rotate(-20, 168, 83)"/>
                </g>
            </svg>
        </div>

        <div class="login-header text-center mt-5">
            <h2 class="fw-bold mb-2">Sign in</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" action="" id="loginForm" novalidate>
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
            
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required 
                           placeholder="Enter your password" autocomplete="current-password">
                    <button class="btn btn-toggle-password" type="button" id="togglePassword" tabindex="-1">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>
            
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                    <label class="form-check-label small" for="rememberMe">
                        Remember me
                    </label>
                </div>
                <a href="./forgot-password.php" class="text-decoration-none small">Forgot password?</a>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <span class="btn-text">Sign in</span>
                    <i class="fas fa-spinner fa-spin d-none" id="loadingIcon"></i>
                </button>
            </div>
            
            <div class="text-center">
                <p class="mb-0">Don't have an account? <a href="register.php" class="text-decoration-none fw-semibold">Sign up here</a></p>
            </div>
        </form>
    </div>

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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
 <script>
document.addEventListener('DOMContentLoaded', () => {
    const cat = {
        svg: document.querySelector('.cat-svg'),
        pupils: document.querySelectorAll('.cat-pupil'),
        container: document.querySelector('.cat-mascot')
    };

    const form = document.getElementById('loginForm');
    const inputs = {
        email: document.getElementById('email'),
        password: document.getElementById('password')
    };
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

    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = inputs.password.getAttribute('type') === 'password' ? 'text' : 'password';
        inputs.password.setAttribute('type', type);
        const icon = document.getElementById('togglePasswordIcon');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
        
        // Add a little animation to the cat
        cat.svg.style.transform = 'scale(1.05)';
        setTimeout(() => {
            cat.svg.style.transform = '';
        }, 200);
    });

    // Form validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
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
        const errorDiv = document.getElementById(input.id + 'Error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    function validateForm() {
        let isValid = true;

        // Email validation
        if (!inputs.email.value.trim()) {
            showError(inputs.email, 'Email is required');
            isValid = false;
        } else if (!validateEmail(inputs.email.value)) {
            showError(inputs.email, 'Please enter a valid email');
            isValid = false;
        } else {
            clearError(inputs.email);
        }

        // Password validation
        if (!inputs.password.value) {
            showError(inputs.password, 'Password is required');
            isValid = false;
        } else {
            clearError(inputs.password);
        }

        return isValid;
    }

    // Real-time validation
    inputs.email.addEventListener('blur', () => {
        if (inputs.email.value.trim() && !validateEmail(inputs.email.value)) {
            showError(inputs.email, 'Please enter a valid email');
        } else if (inputs.email.value.trim()) {
            clearError(inputs.email);
        }
    });

    inputs.email.addEventListener('input', () => {
        if (inputs.email.classList.contains('is-invalid')) {
            clearError(inputs.email);
        }
    });

    inputs.password.addEventListener('input', () => {
        if (inputs.password.classList.contains('is-invalid')) {
            clearError(inputs.password);
        }
    });

    // Enhanced cat interactions
    inputs.password.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(-3deg) scale(1.05)';
        cat.svg.classList.add('covering-eyes');
        cat.container.classList.add('pulse');
    });

    inputs.password.addEventListener('blur', () => {
        cat.svg.style.transform = '';
        if (!inputs.password.value) {
            cat.svg.classList.remove('covering-eyes');
        }
        cat.container.classList.remove('pulse');
    });

    inputs.email.addEventListener('focus', () => {
        cat.svg.style.transform = 'rotate(2deg) scale(1.03)';
        setTimeout(() => {
            if (document.activeElement === inputs.email) {
                cat.svg.classList.add('happy-cat');
            }
        }, 300);
    });

    inputs.email.addEventListener('blur', () => {
        cat.svg.style.transform = '';
        cat.svg.classList.remove('happy-cat');
    });

    // Form submission with enhanced feedback
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (!validateForm()) {
            // Shake animation on invalid form
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 820);
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        loadingIcon.classList.remove('d-none');
        btnText.textContent = 'Signing in...';
        
        // Cat celebration animation
        cat.svg.style.transform = 'translateY(-10px) scale(1.1) rotate(5deg)';
        cat.svg.classList.add('happy-cat');
        
        // Submit the form after animation
        setTimeout(() => {
            form.submit();
        }, 500);
    });

    // Enhanced error handling for server-side errors
    const alertElement = document.querySelector('.alert-danger');
    if (alertElement) {
        // Shake the form on server error
        setTimeout(() => {
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 820);
        }, 100);
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Add floating particles effect
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 4px;
            height: 4px;
            background: rgba(102, 126, 234, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            left: ${Math.random() * window.innerWidth}px;
            top: ${window.innerHeight + 10}px;
            animation: floatUp 4s linear forwards;
        `;
        
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 4000);
    }

    // Add CSS for floating particles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes floatUp {
            to {
                transform: translateY(-${window.innerHeight + 20}px) translateX(${Math.random() * 100 - 50}px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Create particles periodically
    setInterval(createParticle, 2000);

    // Initialize cat position
    setTimeout(() => {
        cat.svg.style.opacity = '1';
        cat.svg.style.transform = 'translateY(0)';
    }, 100);
});
</script>

</body>
</html>