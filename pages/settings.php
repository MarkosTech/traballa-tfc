<?php
/**
 * Traballa - Settings
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

if (!defined('INDEX_EXEC')) {
    exit('Direct access not allowed.');
}

// Include breadcrumb functionality
require_once __DIR__ . '/../includes/Breadcrumb.php';
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
echo $breadcrumb->render(current_route());
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Settings</h1>
    <button class="btn btn-outline-info help-btn" data-help-context="account-settings">
        <i class="fas fa-question-circle me-1"></i>Help
    </button>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Notification Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Notification Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                        </div>
                        <div class="form-text">Receive email notifications for important updates.</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="reminderNotifications" checked>
                            <label class="form-check-label" for="reminderNotifications">Clock-in Reminders</label>
                        </div>
                        <div class="form-text">Receive reminders to clock in at the start of your shift.</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="reportNotifications">
                            <label class="form-check-label" for="reportNotifications">Weekly Reports</label>
                        </div>
                        <div class="form-text">Receive weekly summary reports of your Traballa.</div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
                </form>
            </div>
        </div>
        
        <!-- Display Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Display Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="timeFormat" class="form-label">Time Format</label>
                        <select class="form-select" id="timeFormat">
                            <option value="12">12-hour (1:30 PM)</option>
                            <option value="24">24-hour (13:30)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dateFormat" class="form-label">Date Format</label>
                        <select class="form-select" id="dateFormat">
                            <option value="mdy">MM/DD/YYYY</option>
                            <option value="dmy">DD/MM/YYYY</option>
                            <option value="ymd">YYYY/MM/DD</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Account Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Account Settings</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Two-Factor Authentication</label>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="badge bg-danger">Disabled</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary">Enable</button>
                    </div>
                    <div class="form-text">Add an extra layer of security to your account.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Account Data</label>
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2">Export Data</button>
                        <button type="button" class="btn btn-sm btn-outline-danger">Delete Account</button>
                    </div>
                    <div class="form-text">Export your data or delete your account.</div>
                </div>
            </div>
        </div>
        
        <!-- System Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Version</span>
                        <span>1.0.0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Last Login</span>
                        <span><?php echo date('M d, Y h:i A'); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Browser</span>
                        <span id="browser-info">Loading...</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>IP Address</span>
                        <span><?php echo $_SERVER['REMOTE_ADDR']; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Get browser information
document.addEventListener('DOMContentLoaded', function() {
    const browserInfo = navigator.userAgent;
    let browser = 'Unknown';
    
    if (browserInfo.includes('Firefox')) {
        browser = 'Firefox';
    } else if (browserInfo.includes('Chrome')) {
        browser = 'Chrome';
    } else if (browserInfo.includes('Safari')) {
        browser = 'Safari';
    } else if (browserInfo.includes('Edge')) {
        browser = 'Edge';
    } else if (browserInfo.includes('MSIE') || browserInfo.includes('Trident/')) {
        browser = 'Internet Explorer';
    }
    
    document.getElementById('browser-info').textContent = browser;
});

// Save settings function (demo only)
function saveSettings() {
    // In a real application, this would save to the database
    alert('Settings saved successfully!');
}
</script>

