/**
 * Traballa - Landing Page JavaScript
 */

// Navbar scrolling effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navBrand = document.querySelector('.navbar-brand');
    const navLogo = document.querySelector('.navbar-logo');
    
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled', 'navbar-light');
        navbar.classList.remove('bg-transparent');
        navBrand.classList.remove('text-white');
        navLinks.forEach(link => {
            link.classList.remove('text-white');
        });
    } else {
        navbar.classList.remove('scrolled', 'navbar-light');
        navbar.classList.add('bg-transparent');
        navBrand.classList.add('text-white');
        navLinks.forEach(link => {
            link.classList.add('text-white');
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Animation on scroll
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
    
    // Handle video play button click
    const videoPlayButton = document.querySelector('.hero-play-button');
    const heroVideo = document.querySelector('.hero-video');
    
    if (videoPlayButton && heroVideo) {
        videoPlayButton.addEventListener('click', function() {
            if (heroVideo.paused) {
                heroVideo.play();
                videoPlayButton.style.opacity = '0';
            } else {
                heroVideo.pause();
                videoPlayButton.style.opacity = '0.7';
            }
        });
        
        heroVideo.addEventListener('play', function() {
            videoPlayButton.style.opacity = '0';
        });
        
        heroVideo.addEventListener('pause', function() {
            videoPlayButton.style.opacity = '0.7';
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                
                window.scrollTo({
                    top: targetPosition - navbarHeight - 20,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Mobile menu behavior
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                navbarToggler.click();
            }
        });
    });
    
    // Demo request form handler
    const demoRequestForm = document.getElementById('demo-request-form');
    
    if (demoRequestForm) {
        demoRequestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            
            if (emailInput && emailInput.value) {
                const formParent = this.parentNode;
                this.style.display = 'none';
                
                const successMessage = document.createElement('div');
                successMessage.className = 'text-center py-3';
                successMessage.innerHTML = '<i class="fas fa-check-circle text-success fa-3x mb-3"></i>' +
                    '<h5>Thank you!</h5>' +
                    '<p>We\'ll contact you at ' + emailInput.value + ' to schedule your demo.</p>';
                
                formParent.appendChild(successMessage);
            }
        });
    }
    
    // Initialize animation classes
    animatedElements.forEach(element => {
        element.classList.add('animate-visible');
    });
    
    // Show cookie banner if not previously accepted or rejected
    const cookieBanner = document.getElementById('cookieBanner');
    if (cookieBanner && !localStorage.getItem('cookie_consent')) {
        cookieBanner.style.display = 'block';
    }
});