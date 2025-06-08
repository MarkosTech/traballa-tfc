/**
 * CSRF Protection Utilities
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 */

(function() {
    'use strict';

    // Global CSRF utilities
    window.CSRFUtils = {
        
        // Get CSRF token from meta tag or existing form
        getToken: function() {
            // Try to get from meta tag first
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                return metaTag.getAttribute('content');
            }
            
            // Try to get from existing form
            const csrfInput = document.querySelector('input[name="csrf_token"]');
            if (csrfInput) {
                return csrfInput.value;
            }
            
            return null;
        },
        
        // Add CSRF token to form data
        addToFormData: function(formData) {
            const token = this.getToken();
            if (token) {
                formData.append('csrf_token', token);
            }
            return formData;
        },
        
        // Add CSRF token to URL parameters
        addToParams: function(params) {
            const token = this.getToken();
            if (token) {
                params.csrf_token = token;
            }
            return params;
        },
        
        // Create CSRF input field
        createField: function() {
            const token = this.getToken();
            if (token) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'csrf_token';
                input.value = token;
                return input;
            }
            return null;
        },
        
        // Setup AJAX requests with CSRF protection
        setupAjax: function() {
            const token = this.getToken();
            if (token && typeof $ !== 'undefined') {
                // jQuery setup
                $.ajaxSetup({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                            xhr.setRequestHeader("X-CSRFToken", token);
                        }
                    }
                });
            }
            
            // Fetch API setup
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                if (options.method && !/^(GET|HEAD|OPTIONS|TRACE)$/i.test(options.method)) {
                    options.headers = options.headers || {};
                    options.headers['X-CSRFToken'] = token;
                }
                return originalFetch.call(this, url, options);
            };
        }
    };
    
    // Auto-setup when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        CSRFUtils.setupAjax();
        
        // Add CSRF tokens to forms that don't have them
        const forms = document.querySelectorAll('form[method="post"], form[method="POST"]');
        forms.forEach(function(form) {
            const existingToken = form.querySelector('input[name="csrf_token"]');
            if (!existingToken) {
                const csrfField = CSRFUtils.createField();
                if (csrfField) {
                    form.appendChild(csrfField);
                }
            }
        });
    });
    
})();
