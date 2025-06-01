class SidebarManager {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.mainContent = document.getElementById('main-content');
        this.toggleBtn = document.getElementById('toggle-sidebar');
        this.mobileToggle = null;
        this.overlay = null;
        
        this.breakpoints = {
            mobile: 576,
            tablet: 992
        };
        
        this.init();
    }
    
    init() {
        this.setupPreloader();
        this.setupNavLinks();
        this.setupEventListeners();
        this.loadSavedState();
        this.adjustLayout();
        this.setupKanbanLink();
    }
    
    setupPreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    preloader.classList.add('fade-out');
                    setTimeout(() => preloader.style.display = 'none', 500);
                }, 300);
            });
        }
    }
    
    setupNavLinks() {
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            const icon = link.querySelector('i');
            const textNode = Array.from(link.childNodes).find(node => 
                node.nodeType === Node.TEXT_NODE && node.textContent.trim()
            );
            if (icon && textNode && !link.querySelector('span')) {
                const text = textNode.textContent.trim();
                textNode.remove();
                const span = document.createElement('span');
                span.textContent = text;
                link.appendChild(span);
            }
        });
    }
    
    setupEventListeners() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggleSidebar());
        }
        
        window.addEventListener('resize', () => this.adjustLayout());
    }
    
    setupKanbanLink() {
        const kanbanLink = document.querySelector('a[data-bs-target="#kanbanProjectModal"]');
        if (kanbanLink) {
            kanbanLink.addEventListener('click', (e) => {
                if (this.isMobile()) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = kanbanLink.href;
                }
            });
        }
    }
    
    toggleSidebar() {
        if (this.isMobile()) {
            this.sidebar.classList.toggle('expanded');
            this.handleOverlay();
        } else {
            this.sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', this.sidebar.classList.contains('collapsed'));
            if (this.isTablet()) {
                this.sidebar.classList.toggle('expanded');
            }
        }
        this.adjustContentLayout();
    }
    
    loadSavedState() {
        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed && this.isDesktop()) {
            this.sidebar.classList.add('collapsed');
        }
    }
    
    adjustLayout() {
        this.cleanupMobileElements();
        
        if (this.isMobile()) {
            this.setupMobileLayout();
        } else if (this.isTablet()) {
            this.setupTabletLayout();
        } else {
            this.setupDesktopLayout();
        }
        
        this.adjustContentLayout();
    }
    
    setupMobileLayout() {
        this.sidebar.classList.add('collapsed');
        this.sidebar.classList.remove('expanded');
        this.createMobileToggle();
        this.setupMobileTouchEvents();
    }
    
    setupTabletLayout() {
        this.sidebar.classList.add('collapsed');
        this.sidebar.classList.remove('expanded');
    }
    
    setupDesktopLayout() {
        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed) {
            this.sidebar.classList.add('collapsed');
        } else {
            this.sidebar.classList.remove('collapsed');
            this.sidebar.classList.remove('expanded');
        }
    }
    
    adjustContentLayout() {
        if (!this.sidebar || !this.mainContent) return;
        
        let marginLeft, width;
        
        if (this.isMobile()) {
            marginLeft = '0';
            width = '100%';
        } else if (this.isTablet()) {
            marginLeft = '60px';
            width = 'calc(100% - 60px)';
        } else {
            if (this.sidebar.classList.contains('collapsed')) {
                marginLeft = '60px';
                width = 'calc(100% - 60px)';
            } else {
                marginLeft = '250px';
                width = 'calc(100% - 250px)';
            }
        }
        
        this.mainContent.style.marginLeft = marginLeft;
        this.mainContent.style.width = width;
    }
    
    createMobileToggle() {
        if (document.querySelector('.mobile-sidebar-toggle')) return;
        
        this.mobileToggle = document.createElement('button');
        this.mobileToggle.className = 'mobile-sidebar-toggle';
        this.mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
        this.mobileToggle.title = 'Toggle Menu';
        this.mobileToggle.setAttribute('aria-label', 'Toggle navigation menu');
        
        this.mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleSidebar();
        });
        
        document.body.appendChild(this.mobileToggle);
        document.body.classList.add('mobile-toggle-ready');
    }
    
    setupMobileTouchEvents() {
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            if (link._touchHandler) {
                link.removeEventListener('touchend', link._touchHandler);
            }
            
            link._touchHandler = (e) => {
                e.stopPropagation();
                setTimeout(() => {
                    if (link.href && link.href !== '#') {
                        window.location.href = link.href;
                    } else if (link.getAttribute('data-bs-toggle')) {
                        this.handleModalTrigger(link);
                    }
                }, 50);
            };
            
            link.addEventListener('touchend', link._touchHandler);
        });
    }
    
    handleModalTrigger(link) {
        const target = link.getAttribute('data-bs-target');
        if (!target) return;
        
        if (target === '#kanbanProjectModal') {
            // For the kanban modal, redirect to kanban page
            const hasRewrite = typeof apache_get_modules !== 'undefined' && 
                             apache_get_modules && 
                             apache_get_modules.includes('mod_rewrite');
            const url = hasRewrite ? '/kanban' : 'index.php?page=kanban';
            window.location.href = url;
            return;
        }
        
        const modal = document.querySelector(target);
        if (modal) {
            this.sidebar.classList.remove('expanded');
            this.handleOverlay();
            
            if (window.bootstrap?.Modal) {
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            }
        }
    }
    
    handleOverlay() {
        if (this.sidebar.classList.contains('expanded')) {
            this.createOverlay();
            document.body.classList.add('sidebar-open');
        } else {
            this.removeOverlay();
            document.body.classList.remove('sidebar-open');
        }
    }
    
    createOverlay() {
        this.removeOverlay();
        
        this.overlay = document.createElement('div');
        this.overlay.className = 'sidebar-overlay active';
        
        const closeHandler = (e) => {
            if (e) e.preventDefault();
            this.sidebar.classList.remove('expanded');
            this.handleOverlay();
        };
        
        this.overlay.addEventListener('click', closeHandler);
        this.overlay.addEventListener('touchend', closeHandler);
        
        document.body.appendChild(this.overlay);
    }
    
    removeOverlay() {
        if (this.overlay) {
            this.overlay.remove();
            this.overlay = null;
        }
    }
    
    cleanupMobileElements() {
        if (!this.isMobile()) {
            const mobileToggle = document.querySelector('.mobile-sidebar-toggle');
            if (mobileToggle) mobileToggle.remove();
            this.removeOverlay();
        }
    }
    
    isMobile() {
        return window.innerWidth <= this.breakpoints.mobile;
    }
    
    isTablet() {
        return window.innerWidth <= this.breakpoints.tablet && window.innerWidth > this.breakpoints.mobile;
    }
    
    isDesktop() {
        return window.innerWidth > this.breakpoints.tablet;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new SidebarManager();
});
