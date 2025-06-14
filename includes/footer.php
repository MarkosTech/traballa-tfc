<?php
/**
 * Traballa - Footer
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

// Prevent direct access to this file
if (!defined('INDEX_EXEC')) {
    die("Direct access is not allowed.");
}
?>
<!-- Footer -->
<footer class="footer mt-auto text-center" style="margin-bottom: 65px;">
    <div class="container text-md-center">
        <a href="/privacy-policy" class="text-decoration-none me-3">Privacy policy</a>
        <a href="/cookies-policy" class="text-decoration-none me-3">Cookie policy</a>
        <a href="/terms-of-service" class="text-decoration-none">ToS</a>
        <a href="/legal-advice" class="text-decoration-none ms-3">Legal advice</a>
        <a href="/user-docs" class="text-decoration-none ms-3">User manual</a>
        <a href="/documentation" class="text-decoration-none ms-3">Docs</a>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-link text-decoration-none" onclick="openCookiePreferences()">Manage cookies</button>
        </div>
    </div>
</footer>
<!-- Include Cookie Manager Script -->
<script src="/webroot/assets/js/cookie-manager.js"></script>

<script>
function openCookiePreferences() {
    // Check if the modal already exists
    if (document.getElementById('cookiePreferencesModal')) {
        // Open the modal
        var modalElement = document.getElementById('cookiePreferencesModal');
        var bsModal = new bootstrap.Modal(modalElement);
        bsModal.show();
    }
}
</script>

<?php 
// Include cookie banner
require_once __DIR__ . '/cookie-banner.php'; 
?>
