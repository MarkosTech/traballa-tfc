<?php
/**
 * Traballa - GDPR Data Management
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/traballa-tfc
 * 
 * This page implements all necessary functionality for users to exercise their
 * rights under GDPR (General Data Protection Regulation):
 * - Right of access (Art. 15)
 * - Right of rectification (Art. 16)
 * - Right to erasure/to be forgotten (Art. 17)
 * - Right to restriction of processing (Art. 18)
 * - Right to data portability (Art. 20)
 * - Right to object (Art. 21)
 */

// Include required GDPR functionality
require_once __DIR__ . '/../includes/GdprManager.php';

// Create GDPR manager instance
$gdprManager = new GdprManager($pdo);

// Check if user is authenticated
$isLoggedIn = isset($_SESSION['user_id']);
$currentUserId = $isLoggedIn ? $_SESSION['user_id'] : null;

// Check for pending deletion request
$pendingDeletion = false;
if ($isLoggedIn) {
    $deletionRequest = $gdprManager->getPendingDeletionRequest($currentUserId);
    if ($deletionRequest) {
        $pendingDeletion = true;
        $deletionInfo = $deletionRequest;
    }
}

// Process GDPR-related actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'access':
            // Implementation of Right of Access (Art. 15)
            // Fetch all user data using the GDPR manager
            $userData = $gdprManager->fetchUserData($currentUserId);
            $accessSuccess = true;
            break;
            
        case 'rectify':
            // Implementation of Right of Rectification (Art. 16)
            // Update user personal data
            $fields = [];
            if (isset($_POST['field'])) {
                foreach ($_POST['field'] as $field => $value) {
                    if (!empty($value)) {
                        $fields[$field] = $value;
                    }
                }
            }
            
            if (!empty($fields)) {
                $rectifySuccess = $gdprManager->updateUserData($currentUserId, $fields);
                if ($rectifySuccess) {
                    $gdprManager->logGdprAction($currentUserId, 'data_rectification', 'User updated personal information');
                }
            }
            break;
            
        case 'erase':
            // Implementation of Right to Erasure (Art. 17)
            $confirmErase = $_POST['confirm_erase'] ?? '';
            $deleteReason = $_POST['delete_reason'] ?? '';
            
            if ($confirmErase === 'DELETE') {
                $eraseRequested = $gdprManager->initiateDataDeletion($currentUserId, $deleteReason);
                if ($eraseRequested) {
                    $gdprManager->logErasureRequest($currentUserId);
                    $deletionRequest = $gdprManager->getPendingDeletionRequest($currentUserId);
                    if ($deletionRequest) {
                        $pendingDeletion = true;
                        $deletionInfo = $deletionRequest;
                    }
                }
            }
            break;
            
        case 'cancel_deletion':
            // Cancel a pending deletion request
            $cancellationSuccess = $gdprManager->cancelDeletionRequest($currentUserId);
            if ($cancellationSuccess) {
                $pendingDeletion = false;
            }
            break;
            
        case 'restrict':
            // Implementation of Right to Restriction of Processing (Art. 18)
            $restrictions = $_POST['restrictions'] ?? [];
            $restrictionReason = $_POST['restriction_reason'] ?? '';
            
            if (!empty($restrictions)) {
                $restrictSuccess = $gdprManager->setProcessingRestrictions($currentUserId, $restrictions);
            }
            break;
            
        case 'export':
            // Implementation of Right to Data Portability (Art. 20)
            $format = $_POST['format'] ?? 'json';
            $dataTypes = $_POST['export_data'] ?? ['all'];
            
            // Generate the export file
            $exportPath = $gdprManager->generateUserDataExport($currentUserId, $format, $dataTypes);
            
            if ($exportPath) {
                // Set headers for download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="traballa-data-export.' . $format . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($exportPath));
                readfile($exportPath);
                exit;
            }
            break;
            
        case 'object':
            // Implementation of Right to Object (Art. 21)
            $objectionReason = $_POST['objection_reason'] ?? '';
            $processingTypes = $_POST['processing_types'] ?? [];
            
            if (!empty($objectionReason) && !empty($processingTypes)) {
                $objectSuccess = $gdprManager->registerObjection($currentUserId, $objectionReason, $processingTypes);
            }
            break;
    }
}

// Get current restrictions and objections if user is logged in
$currentRestrictions = [];
$currentObjections = [];
if ($isLoggedIn) {
    $currentRestrictions = $gdprManager->getProcessingRestrictions($currentUserId);
    $currentObjections = $gdprManager->getObjections($currentUserId);
}

// Load Constants (may be used in emails)
$siteName = defined('SITE_NAME') ? SITE_NAME : 'Traballa';
$siteURL = defined('SITE_URL') ? SITE_URL : 'https://traballa.me';
$adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@traballa.local';

// Format date for display
function formatDateForDisplay($dateStr) {
    if (empty($dateStr)) return '';
    $date = new DateTime($dateStr);
    return $date->format('F j, Y');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDPR Data Management - Traballa</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #2d3748;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --border-color: #e2e8f0;
            --danger-color: #e53e3e;
            --success-color: #38a169;
            --warning-color: #d69e2e;
            --info-color: #3182ce;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            background-color: #f8f9fa;
            line-height: 1.6;
        }

        .gdpr-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 4rem 0 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .gdpr-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        .nav-pills .nav-link {
            color: var(--text-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:not(.active):hover {
            background-color: rgba(102, 126, 234, 0.1);
        }
        
        .gdpr-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .tab-content {
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: white;
        }
        
        .infographic {
            background-color: var(--bg-light);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-bottom: none;
        }
        
        .alert-gdpr {
            background-color: rgba(102, 126, 234, 0.1);
            border-left: 4px solid var(--primary-color);
        }
        
        .data-table th {
            background-color: var(--bg-light);
        }
        
        .confirmation-input {
            font-weight: bold;
            text-align: center;
        }
        
        .rights-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        
        .rights-icon.access {
            background-color: rgba(49, 130, 206, 0.1);
            color: var(--info-color);
        }
        
        .rights-icon.rectify {
            background-color: rgba(214, 158, 46, 0.1);
            color: var(--warning-color);
        }
        
        .rights-icon.erase {
            background-color: rgba(229, 62, 62, 0.1);
            color: var(--danger-color);
        }
        
        .rights-icon.restrict {
            background-color: rgba(49, 130, 206, 0.1);
            color: var(--info-color);
        }
        
        .rights-icon.portability {
            background-color: rgba(56, 161, 105, 0.1);
            color: var(--success-color);
        }
        
        .rights-icon.object {
            background-color: rgba(113, 128, 150, 0.1);
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div class="gdpr-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-user-shield me-3"></i>GDPR Data Management
            </h1>
            <p class="lead mb-0">Exercise your data protection rights</p>
        </div>
    </div>
    
    <div class="container">
        <div class="gdpr-container">
            <?php if (!$isLoggedIn): ?>
            
            <!-- User not logged in message -->
            <div class="alert alert-gdpr mb-4">
                <h5><i class="fas fa-lock me-2"></i>Authentication Required</h5>
                <p class="mb-0">You must be logged in to access your GDPR data management options. Please <a href="login" class="alert-link">sign in</a> to continue.</p>
            </div>
            
            <?php else: ?>
            
            <!-- Overview tab - shown first -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-gdpr">
                        <h5 class="mb-2"><i class="fas fa-info-circle me-2"></i>About Your GDPR Rights</h5>
                        <p>The General Data Protection Regulation (GDPR) gives you control over your personal data. On this page, you can exercise your rights concerning your data in Traballa.</p>
                    </div>
                </div>
            </div>
            
            <!-- GDPR Rights -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-4">Your Data Protection Rights</h3>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon access mx-auto">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h5>Right of Access</h5>
                        <p class="text-muted">View all data we store about you</p>
                        <a href="#access" class="btn btn-sm btn-outline-primary" data-bs-toggle="tab" data-bs-target="#access" role="tab">Access Data</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon rectify mx-auto">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h5>Right to Rectification</h5>
                        <p class="text-muted">Correct inaccurate information</p>
                        <a href="#rectify" class="btn btn-sm btn-outline-primary" data-bs-toggle="tab" data-bs-target="#rectify" role="tab">Update Data</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon portability mx-auto">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h5>Right to Data Portability</h5>
                        <p class="text-muted">Export your data in common formats</p>
                        <a href="#portability" class="btn btn-sm btn-outline-primary" data-bs-toggle="tab" data-bs-target="#portability" role="tab">Export Data</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon restrict mx-auto">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h5>Right to Restriction</h5>
                        <p class="text-muted">Restrict how we process your data</p>
                        <a href="#restrict" class="btn btn-sm btn-outline-primary" data-bs-toggle="tab" data-bs-target="#restrict" role="tab">Set Restrictions</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon object mx-auto">
                            <i class="fas fa-hand-paper"></i>
                        </div>
                        <h5>Right to Object</h5>
                        <p class="text-muted">Object to certain data processing</p>
                        <a href="#object" class="btn btn-sm btn-outline-primary" data-bs-toggle="tab" data-bs-target="#object" role="tab">File Objection</a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="rights-icon erase mx-auto">
                            <i class="fas fa-trash"></i>
                        </div>
                        <h5>Right to Be Forgotten</h5>
                        <p class="text-muted">Request deletion of all your data</p>
                        <a href="#erase" class="btn btn-sm btn-outline-danger" data-bs-toggle="tab" data-bs-target="#erase" role="tab">Delete Data</a>
                    </div>
                </div>
            </div>
            
            <!-- Tab content for each GDPR right -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <ul class="nav nav-pills flex-column" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="access-tab" data-bs-toggle="tab" data-bs-target="#access" href="#access" role="tab">
                                <i class="fas fa-eye me-2"></i> Access
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="rectify-tab" data-bs-toggle="tab" data-bs-target="#rectify" href="#rectify" role="tab">
                                <i class="fas fa-edit me-2"></i> Rectify
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="portability-tab" data-bs-toggle="tab" data-bs-target="#portability" href="#portability" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i> Export
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="restrict-tab" data-bs-toggle="tab" data-bs-target="#restrict" href="#restrict" role="tab">
                                <i class="fas fa-ban me-2"></i> Restrict
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="object-tab" data-bs-toggle="tab" data-bs-target="#object" href="#object" role="tab">
                                <i class="fas fa-hand-paper me-2"></i> Object
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" id="erase-tab" data-bs-toggle="tab" data-bs-target="#erase" href="#erase" role="tab">
                                <i class="fas fa-trash me-2"></i> Erase
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Right of Access (Art. 15) -->
                        <div class="tab-pane fade show active" id="access" role="tabpanel">
                            <h4><i class="fas fa-eye me-2 text-info"></i>Access Your Data</h4>
                            <p>Here you can view all personal data that we store about you. This includes your account information, usage data, and preferences.</p>
                            
                            <?php if (isset($accessSuccess)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Your data has been retrieved successfully.
                            </div>
                            <?php endif; ?>
                            
                            <form action="gdpr" method="post">
                                <input type="hidden" name="action" value="access">
                                <button type="submit" class="btn btn-info mb-4">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh data
                                </button>
                            </form>
                            
                            <div class="accordion" id="userData">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalData" aria-expanded="true" aria-controls="personalData">
                                            <i class="fas fa-user me-2"></i> Personal information
                                        </button>
                                    </h2>
                                    <div id="personalData" class="accordion-collapse collapse show" data-bs-parent="#userData">
                                        <div class="accordion-body">
                                            <table class="table table-bordered data-table">
                                                <tbody>
                                                    <tr>
                                                        <th width="30%">Name</th>
                                                        <td><?php echo isset($userData['personal']['name']) ? htmlspecialchars($userData['personal']['name']) : 'Not available'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email Address</th>
                                                        <td><?php echo isset($userData['personal']['email']) ? htmlspecialchars($userData['personal']['email']) : 'Not available'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Role</th>
                                                        <td><?php echo isset($userData['personal']['role']) ? htmlspecialchars($userData['personal']['role']) : 'Not available'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Created</th>
                                                        <td><?php echo isset($userData['personal']['created_at']) ? formatDateForDisplay($userData['personal']['created_at']) : 'Not available'; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#workData" aria-controls="workData">
                                            <i class="fas fa-briefcase me-2"></i> Work data
                                        </button>
                                    </h2>
                                    <div id="workData" class="accordion-collapse collapse" data-bs-parent="#userData">
                                        <div class="accordion-body">
                                            <table class="table table-bordered data-table">
                                                <tbody>
                                                    <tr>
                                                        <th width="30%">Tracked Hours</th>
                                                        <td><?php echo isset($userData['work']['tracked_hours']) ? htmlspecialchars($userData['work']['tracked_hours']) . ' hours' : '0 hours'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Projects</th>
                                                        <td>
                                                            <?php 
                                                            if (isset($userData['work']['projects']) && is_array($userData['work']['projects']) && !empty($userData['work']['projects'])) {
                                                                echo htmlspecialchars(implode(', ', $userData['work']['projects']));
                                                            } else {
                                                                echo 'No projects';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tasks</th>
                                                        <td><?php echo isset($userData['work']['tasks']) ? htmlspecialchars($userData['work']['tasks']) . ' tasks' : '0 tasks'; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#prefsData" aria-controls="prefsData">
                                            <i class="fas fa-sliders-h me-2"></i> Preferences & settings
                                        </button>
                                    </h2>
                                    <div id="prefsData" class="accordion-collapse collapse" data-bs-parent="#userData">
                                        <div class="accordion-body">
                                            <table class="table table-bordered data-table">
                                                <tbody>
                                                    <tr>
                                                        <th width="30%">Language</th>
                                                        <td><?php echo isset($userData['settings']['language']) ? htmlspecialchars($userData['settings']['language']) : 'Not set'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Timezone</th>
                                                        <td><?php echo isset($userData['settings']['timezone']) ? htmlspecialchars($userData['settings']['timezone']) : 'Not set'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email Notifications</th>
                                                        <td>
                                                            <?php 
                                                            if (isset($userData['settings']['notifications'])) {
                                                                echo $userData['settings']['notifications'] ? 'Enabled' : 'Disabled';
                                                            } else {
                                                                echo 'Not set';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#logsData" aria-controls="logsData">
                                            <i class="fas fa-history me-2"></i> Access Logs
                                        </button>
                                    </h2>
                                    <div id="logsData" class="accordion-collapse collapse" data-bs-parent="#userData">
                                        <div class="accordion-body">
                                            <table class="table table-bordered data-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date & Time</th>
                                                        <th>IP Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($userData['logs']['access_logs']) && !empty($userData['logs']['access_logs'])): ?>
                                                        <?php foreach ($userData['logs']['access_logs'] as $log): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($log['date']); ?></td>
                                                            <td><?php echo htmlspecialchars($log['ip']); ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td colspan="2">No access logs available</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right to Rectification (Art. 16) -->
                        <div class="tab-pane fade" id="rectify" role="tabpanel">
                            <h4><i class="fas fa-edit me-2 text-warning"></i>Update Your Data</h4>
                            <p>If any of your personal information is inaccurate or incomplete, you can correct it here.</p>
                            
                            <?php if (isset($rectifySuccess)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Your information has been updated successfully.
                            </div>
                            <?php endif; ?>
                            
                            <form action="gdpr.php" method="post" class="mb-4">
                                <input type="hidden" name="action" value="rectify">
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="field[name]" 
                                           value="<?php echo isset($userData['personal']['name']) ? htmlspecialchars($userData['personal']['name']) : ''; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="field[email]" 
                                           value="<?php echo isset($userData['personal']['email']) ? htmlspecialchars($userData['personal']['email']) : ''; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="language" class="form-label">Preferred Language</label>
                                    <select class="form-select" id="language" name="field[language]">
                                        <?php 
                                        $currentLanguage = isset($userData['settings']['language']) ? $userData['settings']['language'] : 'en';
                                        $languages = ['en' => 'English', 'es' => 'Spanish', 'fr' => 'French', 'de' => 'German'];
                                        foreach ($languages as $code => $name): 
                                        ?>
                                            <option value="<?php echo $code; ?>" <?php echo ($currentLanguage == $code) ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="field[timezone]">
                                        <?php 
                                        $currentTimezone = isset($userData['settings']['timezone']) ? $userData['settings']['timezone'] : 'Europe/Madrid';
                                        $timezones = [
                                            'Europe/Madrid' => 'Europe/Madrid',
                                            'Europe/London' => 'Europe/London',
                                            'America/New_York' => 'America/New York',
                                            'America/Los_Angeles' => 'America/Los Angeles',
                                            'Asia/Tokyo' => 'Asia/Tokyo'
                                        ];
                                        foreach ($timezones as $zone => $name): 
                                        ?>
                                            <option value="<?php echo $zone; ?>" <?php echo ($currentTimezone == $zone) ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update Information
                                </button>
                            </form>
                        </div>
                        
                        <!-- Right to Data Portability (Art. 20) -->
                        <div class="tab-pane fade" id="portability" role="tabpanel">
                            <h4><i class="fas fa-exchange-alt me-2 text-success"></i>Export Your Data</h4>
                            <p>Download your personal data in a structured, commonly used and machine-readable format. This allows you to transfer your data to another service or keep a personal backup.</p>
                            
                            <form action="gdpr.php" method="post" class="mb-4">
                                <input type="hidden" name="action" value="export">
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Data Export Options</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select data to export:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="exportAll" name="export_data[]" value="all" checked>
                                                <label class="form-check-label" for="exportAll">
                                                    All data (recommended)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="exportPersonal" name="export_data[]" value="personal">
                                                <label class="form-check-label" for="exportPersonal">
                                                    Personal information only
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="exportWork" name="export_data[]" value="work">
                                                <label class="form-check-label" for="exportWork">
                                                    Work data only
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="exportPrefs" name="export_data[]" value="preferences">
                                                <label class="form-check-label" for="exportPrefs">
                                                    Preferences and settings only
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Format:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="formatJson" name="format" value="json" checked>
                                                <label class="form-check-label" for="formatJson">
                                                    JSON (recommended for data portability)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="formatCsv" name="format" value="csv">
                                                <label class="form-check-label" for="formatCsv">
                                                    CSV (for spreadsheet applications)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Download My Data
                                </button>
                            </form>
                            
                            <div class="alert alert-gdpr">
                                <h6><i class="fas fa-info-circle me-2"></i>About Data Exports</h6>
                                <p class="mb-0">Exports include all your personal information, time tracking records, project data, and preferences in a structured format. If you switch to another time-tracking tool, this export can help you migrate your data.</p>
                            </div>
                        </div>
                        
                        <!-- Right to Restriction of Processing (Art. 18) -->
                        <div class="tab-pane fade" id="restrict" role="tabpanel">
                            <h4><i class="fas fa-ban me-2 text-info"></i>Restrict Data Processing</h4>
                            <p>You can request restriction of processing your personal data in certain circumstances, such as if you contest the accuracy of the data or if the processing is unlawful.</p>
                            
                            <?php if (isset($restrictSuccess)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Your processing restrictions have been updated successfully.
                            </div>
                            <?php endif; ?>
                            
                            <form action="gdpr.php" method="post" class="mb-4">
                                <input type="hidden" name="action" value="restrict">
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Processing Restriction Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select what data processing you want to restrict:</label>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="restrictAnalytics" name="restrictions[]" value="analytics"
                                                       <?php echo (isset($currentRestrictions) && in_array('analytics', $currentRestrictions)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="restrictAnalytics">
                                                    <strong>Analytics processing:</strong> Usage statistics and improvements
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="restrictMarketing" name="restrictions[]" value="marketing"
                                                       <?php echo (isset($currentRestrictions) && in_array('marketing', $currentRestrictions)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="restrictMarketing">
                                                    <strong>Marketing processing:</strong> Emails about new features, promotions
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="restrictResearch" name="restrictions[]" value="research"
                                                       <?php echo (isset($currentRestrictions) && in_array('research', $currentRestrictions)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="restrictResearch">
                                                    <strong>Research processing:</strong> Improving our products and services 
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="restrictProfiling" name="restrictions[]" value="profiling"
                                                       <?php echo (isset($currentRestrictions) && in_array('profiling', $currentRestrictions)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="restrictProfiling">
                                                    <strong>Profiling and automated decisions:</strong> Personalization based on your behavior
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="restrictionReason" class="form-label">Reason for restriction request:</label>
                                            <select class="form-select" id="restrictionReason" name="restriction_reason">
                                                <option value="">Please select a reason</option>
                                                <option value="accuracy">I contest the accuracy of the data</option>
                                                <option value="unlawful">The processing is unlawful</option>
                                                <option value="verification">Pending verification of overriding grounds</option>
                                                <option value="other">Other reason</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="restrictionDetails" class="form-label">Additional details (optional):</label>
                                            <textarea class="form-control" id="restrictionDetails" name="restriction_details" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Restricting data processing may affect some features of the service. Essential processing required for basic functionality cannot be restricted.
                                </div>
                                
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-lock me-2"></i>Apply Restrictions
                                </button>
                            </form>
                        </div>
                        
                        <!-- Right to Object (Art. 21) -->
                        <div class="tab-pane fade" id="object" role="tabpanel">
                            <h4><i class="fas fa-hand-paper me-2 text-secondary"></i>Object to Processing</h4>
                            <p>You have the right to object to processing of your personal data when it's based on legitimate interests, for direct marketing purposes, or for scientific/historical research and statistics.</p>
                            
                            <?php if (isset($objectSuccess)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Your objection has been recorded successfully.
                            </div>
                            <?php endif; ?>
                            
                            <form action="gdpr.php" method="post" class="mb-4">
                                <input type="hidden" name="action" value="object">
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Processing Objection</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select what processing you object to:</label>
                                            
                                            <?php 
                                            $objectedProcessingTypes = [];
                                            if (!empty($currentObjections)) {
                                                foreach ($currentObjections as $objection) {
                                                    $objectedProcessingTypes[] = $objection['processing_type'];
                                                }
                                            }
                                            ?>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="objectDirect" name="processing_types[]" value="direct_marketing"
                                                       <?php echo in_array('direct_marketing', $objectedProcessingTypes) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="objectDirect">
                                                    <strong>Direct marketing:</strong> Marketing communications sent directly to you
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="objectProfiling" name="processing_types[]" value="profiling"
                                                       <?php echo in_array('profiling', $objectedProcessingTypes) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="objectProfiling">
                                                    <strong>Profiling:</strong> Automated analysis of your usage patterns
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="objectResearch" name="processing_types[]" value="research"
                                                       <?php echo in_array('research', $objectedProcessingTypes) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="objectResearch">
                                                    <strong>Research purposes:</strong> Using your data for product research
                                                </label>
                                            </div>
                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="objectStatistics" name="processing_types[]" value="statistics"
                                                       <?php echo in_array('statistics', $objectedProcessingTypes) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="objectStatistics">
                                                    <strong>Statistical purposes:</strong> Using your data for analytics
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="objectionReason" class="form-label">Reason for your objection:</label>
                                            <textarea class="form-control" id="objectionReason" name="objection_reason" rows="3" required></textarea>
                                            <div class="form-text">Please provide a brief explanation for your objection.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-gdpr">
                                    <i class="fas fa-info-circle me-2"></i>Your objection will be reviewed within 30 days. You'll be notified of the outcome by email.
                                </div>
                                
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-hand-paper me-2"></i>Submit Objection
                                </button>
                            </form>
                        </div>
                        
                        <!-- Right to Erasure/Right to be Forgotten (Art. 17) -->
                        <div class="tab-pane fade" id="erase" role="tabpanel">
                            <h4><i class="fas fa-trash me-2 text-danger"></i>Delete Your Data</h4>
                            <p>You can request complete deletion of your personal data. This action is permanent and will remove your account and all associated data after a 30-day grace period.</p>
                            
                            <?php if (isset($eraseRequested)): ?>
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i>Data Deletion Process Initiated</h5>
                                <p>Your account has been marked for deletion. It will be permanently deleted after a 30-day grace period.</p>
                                <p>During this period, you can reactivate your account by logging in. After the grace period, all your data will be permanently deleted and cannot be recovered.</p>
                                <p>You will receive a confirmation email once the deletion process is complete.</p>
                            </div>
                            <?php else: ?>
                            
                            <div class="alert alert-danger mb-4">
                                <h5><i class="fas fa-exclamation-circle me-2"></i>Warning: This action cannot be undone</h5>
                                <p class="mb-0">Deleting your data will permanently remove your account, all your work hours, projects, and personal settings after a 30-day grace period.</p>
                            </div>
                            
                            <div class="card mb-4 border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">Before you delete your data</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Please consider the following before proceeding:</p>
                                    <ul class="mb-4">
                                        <li>You will lose all your tracking history and reports</li>
                                        <li>Projects you created may be reassigned to other users</li>
                                        <li>You will no longer have access to your dashboard and insights</li>
                                        <li>After the 30-day grace period, your data cannot be recovered</li>
                                    </ul>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-download me-2"></i><strong>Recommendation:</strong> Consider <a href="#portability" class="alert-link" data-bs-toggle="tab" data-bs-target="#portability" role="tab">exporting your data</a> before deletion.
                                    </div>
                                </div>
                            </div>
                            
                            <form action="gdpr.php" method="post" onsubmit="return confirmDelete()">
                                <input type="hidden" name="action" value="erase">
                                
                                <div class="mb-3">
                                    <label for="deleteReason" class="form-label">Reason for deletion (optional):</label>
                                    <select class="form-select" id="deleteReason" name="delete_reason">
                                        <option value="">Please select a reason</option>
                                        <option value="no_longer_needed">I no longer need the service</option>
                                        <option value="data_concerns">I have concerns about my data</option>
                                        <option value="switching">I am switching to another service</option>
                                        <option value="unhappy">I am unhappy with the service</option>
                                        <option value="other">Other reason</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="confirmErase" class="form-label">To confirm deletion, type "DELETE" in the box below:</label>
                                    <input type="text" class="form-control confirmation-input" id="confirmErase" name="confirm_erase" placeholder="DELETE" required>
                                </div>
                                
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Delete My Account and Data
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <a href="settings" class="back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Settings
                </a>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Confirmation for delete action
        function confirmDelete() {
            const confirmText = document.getElementById('confirmErase').value;
            if (confirmText !== 'DELETE') {
                alert('Please type DELETE to confirm the deletion of your account.');
                return false;
            }
            
            return confirm('Are you sure you want to permanently delete your account and all your data? This action cannot be undone after the 30-day grace period.');
        }
        
        // Handle export options (mutual exclusivity)
        document.addEventListener('DOMContentLoaded', function() {
            const exportAll = document.getElementById('exportAll');
            const otherExports = document.querySelectorAll('#exportPersonal, #exportWork, #exportPrefs');
            
            if (exportAll) {
                exportAll.addEventListener('change', function() {
                    if (this.checked) {
                        otherExports.forEach(checkbox => {
                            checkbox.checked = false;
                            checkbox.disabled = true;
                        });
                    } else {
                        otherExports.forEach(checkbox => {
                            checkbox.disabled = false;
                        });
                    }
                });
                
                otherExports.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            exportAll.checked = false;
                        }
                    });
                });
            }
            
            // Direct URL link with hash parameter handling
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const tabElement = document.getElementById(hash + '-tab');
                if (tabElement) {
                    const tab = new bootstrap.Tab(tabElement);
                    tab.show();
                }
            }
        });
    </script>
</body>
</html>
