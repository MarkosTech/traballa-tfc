<?php
/**
 * Traballa - Cookie Consent Banner
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * This component implements a GDPR-compliant cookie consent banner that:
 * - Informs users about cookie usage
 * - Allows granular consent for different cookie categories
 * - Persists user preferences
 * - Blocks non-essential cookies until consent is given
 */

// Prevent direct access to this file
if (!defined('INDEX_EXEC')) {
    die("Direct access is not allowed.");
}

// Check if consent has already been given
$cookieConsent = isset($_COOKIE['cookie_consent']) ? json_decode($_COOKIE['cookie_consent'], true) : null;
$showBanner = $cookieConsent === null;

?>
<!-- Cookie Preferences Modal -->
<div class="modal fade" id="cookiePreferencesModal" tabindex="-1" aria-labelledby="cookiePreferencesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cookiePreferencesModalLabel">Manage cookies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="consent_essential" checked disabled>
                            <label class="form-check-label" for="consent_essential">
                                <strong>Essential cookies</strong>
                            </label>
                            <div class="form-text">Necessary for the basic functionality of the website. They cannot be disabled.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consent_functional" id="consent_functional" checked>
                            <label class="form-check-label" for="consent_functional">
                                <strong>Functional cookies</strong>
                            </label>
                            <div class="form-text">Enable advanced features such as display preferences and user settings.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consent_analytics" id="consent_analytics" checked>
                            <label class="form-check-label" for="consent_analytics">
                                <strong>Analytics cookies</strong>
                            </label>
                            <div class="form-text">Help us understand how you use the site to improve the experience.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="consent_marketing" id="consent_marketing">
                            <label class="form-check-label" for="consent_marketing">
                                <strong>Marketing cookies</strong>
                            </label>
                            <div class="form-text">Used to display relevant advertising based on your visits to the site.</div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="cookie_consent_action" value="save_preferences">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePreferencesJson()">Save preferences</button>
            </div>
        </div>
    </div>
</div>

<?php if ($showBanner): ?>

<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" class="position-fixed bottom-0 start-0 end-0 p-3 bg-light border-top shadow" style="z-index: 1050;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5>We use cookies</h5>
                <p class="mb-2">
                    This site uses cookies to improve your experience, offer personalized services and analyze traffic.
                    Essential cookies are necessary for the site to work. You can choose which cookies to allow.
                    <a href="/cookies-policy" target="_blank">More information</a>.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <button type="button" class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#cookiePreferencesModal">
                    Configure
                </button>
                <button type="button" onclick="rejectAllCookiesJson()" class="btn btn-sm btn-outline-secondary me-2">
                    Reject all
                </button>
                <button type="button" onclick="acceptAllCookiesJson()" class="btn btn-sm btn-primary">
                    Accept all
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Only load analytics scripts if analytics cookies are allowed
if (isCookieAllowed('analytics')) {
    // Analytics scripts can be placed here
}

// Only load marketing scripts if marketing cookies are allowed
if (isCookieAllowed('marketing')) {
    // Marketing scripts can be placed here
}
</script>

<?php endif; // End of show banner check ?>
