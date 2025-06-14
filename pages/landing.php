<?php
/**
 * Traballa - Landing Page
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/workhours-tfc
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this softwa                        <h3>Create account</h3>
                        <p>Sign up for free and set up your profile in just a few clicks. No credit card required.</p> and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copi    </section>
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

// If already logged in, redirect to dashboard
if ($session->get('user_id')) {
    $redirect_url = getDashboardUrl('index.php?page=dashboard');
    header("Location: " . $redirect_url);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Traballa - Track time, get productive</title>
    <meta name="description" content="Traballa helps teams track time, manage projects, and visualize productivity with powerful analytics. Suitable for remote workers, freelancers, and organizations of all sizes.">
    <meta name="keywords" content="time tracking, project management, productivity, work hours, remote work, team management">
    <meta name="author" content="Traballa">
    <meta name="theme-color" content="#3498db">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://traballa.com/">
    <meta property="og:title" content="Traballa - Work time management & project tracking">
    <meta property="og:description" content="Track time, manage projects, and boost productivity with Traballa">
    <meta property="og:image" content="assets/img/svg/traballa-logo-whiteBgColor.svg">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/svg/traballa-logo-noBgColor.svg" type="image/svg+xml">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Landing Page CSS -->
    <link rel="stylesheet" href="assets/css/landing.css">
    
    <!-- Enhanced Responsiveness -->
    <link rel="stylesheet" href="assets/css/landing-responsive.css">
    
    <!-- Browser Compatibility Styles -->
    <link rel="stylesheet" href="assets/css/browser-compatibility.css">
    
    <!-- Schema.org markup for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Traballa",
        "description": "Work time tracking and project management application",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR"
        }
    }
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-transparent">
        <div class="container">
            <a class="navbar-brand text-white" href="landing.php">
                <img src="assets/img/svg/traballa-logo-noBgColor.svg" alt="Traballa Logo" class="navbar-logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#how-it-works">How it works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#self-host">Self-host</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo getLoginUrl('login'); ?>">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light rounded-pill ms-lg-3" href="register">Sign up free</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mx-auto text-lg-start">
                    <h1 class="animate__animated animate__fadeIn">Track your work time & boost productivity</h1>
                    <p class="animate__animated animate__fadeIn animate__delay-1s">Traballa helps teams track time, manage projects, and visualize productivity with powerful analytics. Perfect for remote workers, freelancers, and organizations of all sizes looking to optimize their workflow.</p>
                    <div class="animate__animated animate__fadeIn animate__delay-2s">
                        <a href="register" class="btn btn-light btn-lg rounded-pill me-3 mb-2 mb-sm-0">Start for free</a>
                        <a href="#features" class="btn btn-outline-light btn-lg rounded-pill">Learn more</a>
                    </div>
                    <div class="mt-4 animate__animated animate__fadeIn animate__delay-2s">
                        <span class="badge bg-light text-dark rounded-pill py-2 px-3 mx-1">
                            <i class="fas fa-clock me-1"></i> Simple time tracking
                        </span>
                        <span class="badge bg-light text-dark rounded-pill py-2 px-3 mx-1">
                            <i class="fas fa-shield-alt me-1"></i> GDPR compliant
                        </span>
                        <span class="badge bg-light text-dark rounded-pill py-2 px-3 mx-1">
                            <i class="fas fa-code me-1"></i> Open source
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 animate__animated animate__fadeIn animate__delay-1s">
                    <div class="hero-video-container position-relative">
                        <div class="hero-device-frame">
                            <video autoplay loop muted class="hero-video" poster="assets/img/traballa-demo-poster.jpg" id="heroVideo">
                                <source src="https://edu.markostech.es/e9dcvf6n6um204qr.mp4" type="video/mp4">
                                <!-- Fallback image if video doesn't load -->
                                <img src="assets/img/traballa-demo-poster.jpg" alt="Traballa in action" class="img-fluid rounded-lg shadow-lg">
                            </video>
                            <div class="hero-play-button">
                                <i class="fas fa-play"></i>
                            </div>
                            <button class="hero-unmute-button" id="unmuteButton" title="Unmute video">
                                <i class="fas fa-volume-mute" id="muteIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold animate-on-scroll">Powerful features to streamline your work</h2>
                <p class="text-muted animate-on-scroll">Everything you need to manage projects and track productivity</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Time tracking</h3>
                        <p class="text-muted">Track your work hours effortlessly with one-click timers. Set breaks, add notes, and categorize your time by projects to gain valuable insights into how you spend your day.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h3>Project management</h3>
                        <p class="text-muted">Create projects, assign team members, and monitor progress in real-time. Visualize workflows with interactive Kanban boards and stay on top of deadlines.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3>Detailed reports</h3>
                        <p class="text-muted">Get comprehensive insights into productivity patterns with customizable reports and analytics. Export data in multiple formats including CSV, PDF, and Excel for seamless integration with your workflow.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3>Calendar integration</h3>
                        <p class="text-muted">View your tasks and tracked time in a calendar format. Integrates with popular calendar apps.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Team collaboration</h3>
                        <p class="text-muted">Work together seamlessly with team workspaces, shared projects, and real-time updates. Communicate effectively and keep everyone aligned on project goals and deadlines.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center feature-card animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile friendly</h3>
                        <p class="text-muted">Track time and manage projects on the go with our fully responsive design that works seamlessly on all devices - from desktop to tablet to smartphone.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="how-it-works" class="how-it-works py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold animate-on-scroll">How Traballa works</h2>
                <p class="text-muted animate-on-scroll">Get started in minutes with these simple steps</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-card animate-on-scroll text-center">
                        <div class="step-number">1</div>
                        <div class="icon-container mb-4">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>Create account</h3>
                        <p>Sign up for free and set up your profile in just a few clicks.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-card animate-on-scroll text-center">
                        <div class="step-number">2</div>
                        <div class="icon-container mb-4">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h3>Create projects</h3>
                        <p>Set up your projects and invite team members to collaborate. Organize work efficiently with custom project structures.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-card animate-on-scroll text-center">
                        <div class="step-number">3</div>
                        <div class="icon-container mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Track time</h3>
                        <p>Use our intuitive timer to track work hours across different tasks. Add detailed notes and categorize your activities.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-card animate-on-scroll text-center">
                        <div class="step-number">4</div>
                        <div class="icon-container mb-4">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Analyze & optimize</h3>
                        <p>Review detailed reports to improve productivity and efficiency. Identify patterns and optimize your workflow for better results.</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 animate-on-scroll">
                <a href="register" class="btn btn-primary rounded-pill">Get started now</a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold animate-on-scroll">Simple, transparent pricing</h2>
                <p class="text-muted animate-on-scroll">Choose the plan that works for you</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="pricing-card animate-on-scroll">
                        <div class="pricing-header text-center">
                            <h3 class="fw-bold">Free</h3>
                            <p class="mb-0">Perfect for individuals</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="pricing-price">
                                €0<small>/month</small>
                            </div>
                            <ul class="pricing-features text-start">
                                <li><i class="fas fa-check"></i> 1 user</li>
                                <li><i class="fas fa-check"></i> 3 projects</li>
                                <li><i class="fas fa-check"></i> Basic time tracking</li>
                                <li><i class="fas fa-check"></i> Weekly reports</li>
                                <li><i class="fas fa-times"></i> Team collaboration</li>
                                <li><i class="fas fa-times"></i> Advanced reports</li>
                                <li><i class="fas fa-times"></i> API access</li>
                            </ul>
                            <a href="register" class="btn btn-outline-primary rounded-pill w-100">Start free</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card pricing-popular animate-on-scroll">
                        <div class="pricing-header text-center">
                            <h3 class="fw-bold">Pro</h3>
                            <p class="mb-0">Ideal for small teams</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="pricing-price">
                                €9<small>/user/month</small>
                            </div>
                            <ul class="pricing-features text-start">
                                <li><i class="fas fa-check"></i> Up to 10 users</li>
                                <li><i class="fas fa-check"></i> Unlimited projects</li>
                                <li><i class="fas fa-check"></i> Advanced time tracking</li>
                                <li><i class="fas fa-check"></i> Detailed reports</li>
                                <li><i class="fas fa-check"></i> Team collaboration</li>
                                <li><i class="fas fa-check"></i> Calendar integration</li>
                                <li><i class="fas fa-times"></i> API access</li>
                            </ul>
                            <a href="register" class="btn btn-accent rounded-pill w-100 text-white">Start 14-day trial</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card animate-on-scroll">
                        <div class="pricing-header text-center">
                            <h3 class="fw-bold">Enterprise</h3>
                            <p class="mb-0">Built for organizations</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="pricing-price">
                                €15<small>/user/month</small>
                            </div>
                            <ul class="pricing-features text-start">
                                <li><i class="fas fa-check"></i> Unlimited users</li>
                                <li><i class="fas fa-check"></i> Unlimited projects</li>
                                <li><i class="fas fa-check"></i> Advanced time tracking</li>
                                <li><i class="fas fa-check"></i> Custom reports</li>
                                <li><i class="fas fa-check"></i> Team collaboration</li>
                                <li><i class="fas fa-check"></i> Priority support</li>
                                <li><i class="fas fa-check"></i> API access</li>
                            </ul>
                            <a href="register" class="btn btn-primary rounded-pill w-100">Contact sales</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Self-Host Section -->
    <section id="self-host" class="self-host">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold animate-on-scroll">Prefer to self-host?</h2>
                    <p class="animate-on-scroll">Keep your data on your own servers with our self-hosted option. Perfect for organizations with specific compliance requirements, data sovereignty needs, or those who prefer complete control over their infrastructure and data security.</p>
                    
                    <div class="d-flex align-items-center mb-3 animate-on-scroll">
                        <div class="me-3">
                            <i class="fas fa-lock fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Data privacy</h4>
                            <p class="mb-0 text-muted">Keep sensitive data within your infrastructure</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3 animate-on-scroll">
                        <div class="me-3">
                            <i class="fas fa-code-branch fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Customization</h4>
                            <p class="mb-0 text-muted">Modify the code to fit your specific needs</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4 animate-on-scroll">
                        <div class="me-3">
                            <i class="fas fa-server fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Full control</h4>
                            <p class="mb-0 text-muted">Manage updates, backups, and security on your terms</p>
                        </div>
                    </div>
                    
                    <div class="animate-on-scroll">
                        <a href="https://github.com/markostech/workhours-tfc" target="_blank" class="btn btn-primary rounded-pill me-3">
                            <i class="fab fa-github me-2"></i>View on GitHub
                        </a>
                        <a href="#" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-file-download me-2"></i>Installation guide
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="shadow-lg rounded p-4 bg-light animate-on-scroll">
                        <img src="assets/img/self-host.svg" alt="Self-hosted Traballa illustration" class="img-fluid self-host-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold animate-on-scroll">Frequently asked questions</h2>
                <p class="text-muted animate-on-scroll">Find quick answers to common questions</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion accordion-flush animate-on-scroll" id="faqAccordion">
                        <!-- Question 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    How does Traballa's time tracking work?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Traballa offers multiple ways to track time to fit your workflow. You can use our intuitive one-click timer to start and stop tracking in real-time, or manually enter hours for completed work. Time entries can be categorized by project, client, and specific tasks, with support for detailed notes and custom tags. The system also allows you to set up break reminders and automatically pause tracking during inactive periods, making detailed reporting effortless later on.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Can I export my time tracking data?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Absolutely! Traballa provides comprehensive export capabilities in multiple formats including CSV, Excel, and PDF. These exports can be fully customized to include exactly the information you need, whether it's for client billing, team productivity analysis, or compliance reporting. You can filter by date ranges, specific projects, team members, and even custom tags to create targeted reports that meet your exact requirements.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    How many team members can I add to my workspace?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>The number of team members depends on your plan. The Free plan allows 1 user, the Pro plan supports up to 10 users, and the Enterprise plan offers unlimited users. You can always upgrade your plan as your team grows.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Is my data secure with Traballa?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Absolutely. Security is our top priority. We employ industry-standard encryption for data both in transit and at rest. We're GDPR compliant and offer features like two-factor authentication and single sign-on for enhanced security. For organizations with specific security requirements, our self-hosted option gives you complete control over your data.</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="text-center mt-4 animate-on-scroll">
                        <a href="#" class="text-primary">
                            <i class="fas fa-question-circle me-2"></i>View all FAQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="animate-on-scroll">
                        <h2 class="fw-bold text-white mb-3">Ready to transform your productivity?</h2>
                        <p class="text-white mb-4">Join thousands of teams already using Traballa to track time, manage projects, and boost productivity.</p>
                        <a href="register" class="btn btn-light btn-lg rounded-pill me-3 mb-3 mb-md-0">Start for free</a>
                        <a href="<?php echo getLoginUrl('login'); ?>" class="btn btn-outline-light btn-lg rounded-pill">Sign in</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="footer-logo-container mb-3">
                        <img src="assets/img/svg/traballa-logo-noBgWhite.svg" alt="Traballa Logo" class="footer-logo" height="45">
                    </div>
                    <p>Simplifying time tracking and project management for teams everywhere. Built with privacy, productivity, and collaboration in mind.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://github.com/markostech/workhours-tfc"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Company</h5>
                    <ul class="footer-links">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Product</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#self-host">Self-host</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="terms-of-service.php">Terms</a></li>
                        <li><a href="privacy-policy.php">Privacy</a></li>
                        <li><a href="#">Cookies</a></li>
                        <li><a href="#">GDPR</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="mt-5 mb-4 footer-divider">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <small>&copy; <?php echo date('Y'); ?> Traballa - MIT License</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small>Made with <i class="fas fa-heart text-danger"></i> in Galicia, Spain</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Landing Page JavaScript -->
    <script src="assets/js/landing.js"></script>
    
    <!-- Cookie Consent Banner -->
    <div class="cookie-banner" id="cookieBanner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <p class="mb-0">We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies. <a href="privacy-policy.php">Learn more</a></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-sm btn-light me-2" id="cookieAccept">Accept</button>
                    <button class="btn btn-sm btn-outline-light" id="cookieReject">Reject</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Simple cookie consent handler
        document.addEventListener('DOMContentLoaded', function() {
            const cookieBanner = document.getElementById('cookieBanner');
            const acceptBtn = document.getElementById('cookieAccept');
            const rejectBtn = document.getElementById('cookieReject');
            
            // Check if user has already made a choice
            if (!localStorage.getItem('cookie_consent')) {
                cookieBanner.style.display = 'block';
            }
            
            acceptBtn.addEventListener('click', function() {
                localStorage.setItem('cookie_consent', 'accepted');
                cookieBanner.style.display = 'none';
                
                // Here you could initialize any tracking scripts
                // Example: initializeAnalytics();
            });
            
            rejectBtn.addEventListener('click', function() {
                localStorage.setItem('cookie_consent', 'rejected');
                cookieBanner.style.display = 'none';
            });
            
            // Video unmute functionality
            const heroVideo = document.getElementById('heroVideo');
            const unmuteButton = document.getElementById('unmuteButton');
            const muteIcon = document.getElementById('muteIcon');
            
            if (heroVideo && unmuteButton && muteIcon) {
                unmuteButton.addEventListener('click', function() {
                    if (heroVideo.muted) {
                        heroVideo.muted = false;
                        muteIcon.className = 'fas fa-volume-up';
                        unmuteButton.title = 'Mute video';
                    } else {
                        heroVideo.muted = true;
                        muteIcon.className = 'fas fa-volume-mute';
                        unmuteButton.title = 'Unmute video';
                    }
                });
                
                // Show unmute button after video starts playing
                heroVideo.addEventListener('loadeddata', function() {
                    unmuteButton.style.display = 'flex';
                });
            }
        });
    </script>
    
    <style>
        .cookie-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(44, 62, 80, 0.95);
            color: white;
            padding: 15px 0;
            z-index: 9998;
            display: none;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .hero-video {
            max-height: 600px;
            width: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .hero-video-container {
            max-height: 600px;
            overflow: hidden;
            border-radius: 12px;
        }
        
        .hero-unmute-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-unmute-button:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }
        
        .hero-unmute-button:active {
            transform: scale(0.95);
        }
        
        @media (max-width: 768px) {
            .hero-video {
                max-height: 400px;
            }
            
            .hero-video-container {
                max-height: 400px;
            }
            
            .hero-unmute-button {
                top: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-video {
                max-height: 300px;
            }
            
            .hero-video-container {
                max-height: 300px;
            }
        }
    </style>
</body>
</html>
