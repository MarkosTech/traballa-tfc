/**
 * Cookie Manager
 * This script handles cookie consent management across the site
 */

// Helper function to get a cookie by name
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

// Helper function to check if a specific cookie category is allowed
function isCookieAllowed(category) {
    try {
        const cookieConsent = JSON.parse(getCookie('cookie_consent') || '{}');
        return cookieConsent[category] === true;
    } catch (e) {
        return false;
    }
}

// Function to accept all cookies
function acceptAllCookiesJson() {
    // Show loading state on the button
    const acceptButton = document.querySelector('button.btn-primary');
    if (acceptButton) {
        const originalText = acceptButton.innerHTML;
        acceptButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        acceptButton.disabled = true;
    }
    
    // Prepare data for the AJAX request
    const data = {
        action: 'accept_all'
    };
    
    // Send the AJAX request
    fetch('/webroot/ajax/handle-cookie-consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide the banner if successful
            const banner = document.getElementById('cookieConsentBanner');
            if (banner) {
                banner.style.display = 'none';
            }
            
            // Reload the page to apply cookie settings
            window.location.reload();
        } else {
            // Show error if request failed
            if (acceptButton) {
                acceptButton.innerHTML = originalText;
                acceptButton.disabled = false;
            }
            alert('Error saving cookie preferences. Please try again.');
        }
    })
    .catch(error => {
        // Handle errors
        if (acceptButton) {
            acceptButton.innerHTML = originalText;
            acceptButton.disabled = false;
        }
        console.error('Error:', error);
        alert('Error saving cookie preferences. Please try again.');
    });
}

// Function to reject all cookies
function rejectAllCookiesJson() {
    // Show loading state on the button
    const rejectButton = document.querySelector('.btn-outline-secondary[onclick="rejectAllCookiesJson()"]');
    if (rejectButton) {
        const originalText = rejectButton.innerHTML;
        rejectButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        rejectButton.disabled = true;
    }
    
    // Prepare data for the AJAX request
    const data = {
        action: 'reject_all'
    };
    
    // Send the AJAX request
    fetch('/webroot/ajax/handle-cookie-consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide the banner if successful
            const banner = document.getElementById('cookieConsentBanner');
            if (banner) {
                banner.style.display = 'none';
            }
            
            // Reload the page to apply cookie settings
            window.location.reload();
        } else {
            // Show error if request failed
            if (rejectButton) {
                rejectButton.innerHTML = originalText;
                rejectButton.disabled = false;
            }
            alert('Error saving cookie preferences. Please try again.');
        }
    })
    .catch(error => {
        // Handle errors
        if (rejectButton) {
            rejectButton.innerHTML = originalText;
            rejectButton.disabled = false;
        }
        console.error('Error:', error);
        alert('Error saving cookie preferences. Please try again.');
    });
}

// Function to save cookie preferences
function savePreferencesJson() {
    // Show loading state on the button
    const saveButton = document.querySelector('.modal-footer .btn-primary');
    if (saveButton) {
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        saveButton.disabled = true;
    }
    
    // Get the form values
    const functional = document.getElementById('consent_functional').checked;
    const analytics = document.getElementById('consent_analytics').checked;
    const marketing = document.getElementById('consent_marketing').checked;
    
    // Prepare data for the AJAX request
    const data = {
        action: 'save_preferences',
        preferences: {
            functional: functional,
            analytics: analytics,
            marketing: marketing
        }
    };
    
    // Send the AJAX request
    fetch('/webroot/ajax/handle-cookie-consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide the modal and banner if successful
            const modal = bootstrap.Modal.getInstance(document.getElementById('cookiePreferencesModal'));
            modal.hide();
            
            const banner = document.getElementById('cookieConsentBanner');
            if (banner) {
                banner.style.display = 'none';
            }
            
            // Reload the page to apply cookie settings
            window.location.reload();
        } else {
            // Show error if request failed
            if (saveButton) {
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
            }
            alert('Error saving cookie preferences. Please try again.');
        }
    })
    .catch(error => {
        // Handle errors
        if (saveButton) {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
        console.error('Error:', error);
        alert('Error saving cookie preferences. Please try again.');
    });
}
