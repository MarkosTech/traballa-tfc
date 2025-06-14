<?php
/**
 * Traballa - Subscription Management
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 */

$user_id = $session->get('user_id');
$user_org_id = $session->get('current_organization_id'); // Changed from 'organization_id'

// If no organization is selected, get the first one the user belongs to
if (!$user_org_id) {
    $user_organizations = getUserOrganizations($pdo, $user_id);
    if (!empty($user_organizations)) {
        $user_org_id = $user_organizations[0]['id'];
        $_SESSION['current_organization_id'] = $user_org_id;
    }
}

$message = '';
$error = '';

// Get subscription manager
$subscriptionManager = getSubscriptionManager();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'upgrade_plan':
            $plan_id = (int)$_POST['plan_id'];
            if ($subscriptionManager->upgradePlan($user_org_id, $plan_id)) {
                $message = "Plan upgraded successfully!";
            } else {
                $error = "Failed to upgrade plan.";
            }
            break;
            
        case 'start_trial':
            $plan_id = (int)$_POST['plan_id'];
            if ($subscriptionManager->startTrial($user_org_id, $plan_id)) {
                $message = "Trial started successfully!";
            } else {
                $error = "Failed to start trial.";
            }
            break;
            
        case 'cancel_subscription':
            if ($subscriptionManager->cancelSubscription($user_org_id)) {
                $message = "Subscription cancelled successfully.";
            } else {
                $error = "Failed to cancel subscription.";
            }
            break;
    }
}

// Get current plan and usage
$currentPlan = null;
$allPlans = [];
$usageStats = ['users_count' => 0, 'projects_count' => 0];

if ($subscriptionManager) {
    try {
        $currentPlan = $subscriptionManager->getOrganizationPlan($user_org_id);
        
        $allPlansRaw = $subscriptionManager->getPlans();
        if (is_array($allPlansRaw)) {
            // Add extra validation and limit plans
            $validPlans = [];
            $planCounter = 0;
            foreach ($allPlansRaw as $planData) {                
                // Validate essential plan fields
                if (is_array($planData) && 
                    isset($planData['id'], $planData['name'], $planData['price']) &&
                    is_numeric($planData['id']) && 
                    is_string($planData['name']) && 
                    is_numeric($planData['price'])) {
                    
                    $validPlans[] = $planData;
                }
            }
            $allPlans = $validPlans;
        }
        
        $usageStatsRaw = $subscriptionManager->getUsageStats($user_org_id);
        if (is_array($usageStatsRaw)) {
            $usageStats = array_merge($usageStats, $usageStatsRaw);
        }
        
        $subscriptionManager->updateUsageStats($user_org_id);
    } catch (Exception $e) {
        error_log("Subscription error: " . $e->getMessage());
        $error = "Error loading subscription data.";
        // Set empty safe defaults
        $allPlans = [];
        $currentPlan = null;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1></i>Subscription management</h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Current Plan -->
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Current plan</h5>
            </div>
            <div class="card-body">
                <?php if ($currentPlan): ?>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1"><?php echo htmlspecialchars($currentPlan['name']); ?></h4>
                            <p class="text-muted mb-2">
                                €<?php echo number_format($currentPlan['price'], 2); ?>/month
                            </p>
                            <span class="badge bg-<?php 
                                echo $currentPlan['subscription_status'] === 'active' ? 'success' : 
                                    ($currentPlan['subscription_status'] === 'trial' ? 'warning' : 'secondary'); 
                            ?>">
                                <?php echo ucfirst($currentPlan['subscription_status']); ?>
                            </span>
                            
                            <?php if ($currentPlan['subscription_status'] === 'trial' && $currentPlan['trial_ends_at']): ?>
                                <p class="text-warning mt-2 mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    Trial ends: <?php echo date('M j, Y', strtotime($currentPlan['trial_ends_at'])); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if ($currentPlan['subscription_status'] === 'active' && $currentPlan['name'] !== 'Free'): ?>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    Cancel subscription
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No active subscription found.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Usage Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Usage statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Users</span>
                            <span class="fw-bold">
                                <?php echo $usageStats['users_count']; ?>
                                <?php if ($currentPlan && $currentPlan['max_users'] != -1): ?>
                                    / <?php echo $currentPlan['max_users']; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ($currentPlan && $currentPlan['max_users'] != -1): ?>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" 
                                        style="width: <?php echo min(100, ($usageStats['users_count'] / $currentPlan['max_users']) * 100); ?>%">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Projects</span>
                            <span class="fw-bold">
                                <?php echo $usageStats['projects_count']; ?>
                                <?php if ($currentPlan && $currentPlan['max_projects'] != -1): ?>
                                    / <?php echo $currentPlan['max_projects']; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ($currentPlan && $currentPlan['max_projects'] != -1): ?>
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" 
                                        style="width: <?php echo min(100, ($usageStats['projects_count'] / $currentPlan['max_projects']) * 100); ?>%">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick actions</h5>
            </div>
            <div class="card-body text-center">
                <a href="<?php echo route_url('billing'); ?>" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-receipt me-2"></i>Billing history
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Available Plans -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Available plans</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php 
            // Ultra-safe plan rendering with for loop
            if (is_array($allPlans) && count($allPlans) > 0):
                $totalPlans = count($allPlans);
                $maxPlans = min(5, $totalPlans); // Limit to 5 plans maximum
                
                for ($i = 0; $i < $maxPlans; $i++):
                    if (!isset($allPlans[$i])) break;
                    $plan = $allPlans[$i];
                    
                    // Validate plan data
                    if (!is_array($plan) || !isset($plan['id'], $plan['name'], $plan['price'])) {
                        continue;
                    }
            ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 <?php echo ($currentPlan && $currentPlan['current_plan_id'] == $plan['id']) ? 'border-primary' : ''; ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($plan['name']); ?></h5>
                            <h3 class="text-primary">€<?php echo number_format($plan['price'], 2); ?><small>/month</small></h3>
                            <hr>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>
                                    <?php echo $plan['max_users'] == -1 ? 'Unlimited users' : $plan['max_users'] . ' user' . ($plan['max_users'] > 1 ? 's' : ''); ?>
                                </li>
                                <li><i class="fas fa-check text-success me-2"></i>
                                    <?php echo $plan['max_projects'] == -1 ? 'Unlimited projects' : $plan['max_projects'] . ' project' . ($plan['max_projects'] > 1 ? 's' : ''); ?>
                                </li>
                                
                                <?php 
                                // Safely decode features JSON and add protection against infinite loops
                                $features = [];
                                if (isset($plan['features']) && !empty($plan['features'])) {
                                    $decodedFeatures = json_decode($plan['features'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFeatures)) {
                                        $features = $decodedFeatures;
                                    }
                                }
                                
                                $featureLabels = [
                                    'time_tracking' => 'Time tracking',
                                    'basic_reports' => 'Basic reports',
                                    'team_collaboration' => 'Team collaboration',
                                    'advanced_reports' => 'Advanced reports',
                                    'calendar_integration' => 'Calendar integration',
                                    'api_access' => 'API access',
                                    'priority_support' => 'Priority support'
                                ];
                                
                                // Use for loop with keys array to prevent infinite loops
                                $featureKeys = array_keys($featureLabels);
                                $totalFeatures = count($featureKeys);
                                $maxFeatures = min(7, $totalFeatures); // Safety limit
                                
                                for ($j = 0; $j < $maxFeatures; $j++):
                                    if (!isset($featureKeys[$j])) break;
                                    $key = $featureKeys[$j];
                                    $label = $featureLabels[$key];
                                    
                                    $hasFeature = isset($features[$key]) && $features[$key];
                                    $icon = $hasFeature ? 'fas fa-check text-success' : 'fas fa-times text-muted';
                                ?>
                                    <li><i class="<?php echo $icon; ?> me-2"></i><?php echo htmlspecialchars($label); ?></li>
                                <?php endfor; ?>
                            </ul>
                            
                            <?php if ($currentPlan && $currentPlan['current_plan_id'] == $plan['id']): ?>
                                <button class="btn btn-outline-primary w-100" disabled>Current plan</button>
                            <?php else: ?>
                                <button class="btn btn-primary w-100 upgrade-btn" 
                                        data-plan-id="<?php echo $plan['id']; ?>"
                                        data-plan-name="<?php echo htmlspecialchars($plan['name']); ?>"
                                        data-plan-price="<?php echo $plan['price']; ?>">
                                    <?php if ($plan['price'] == 0): ?>
                                        Downgrade to free
                                    <?php else: ?>
                                        Select plan
                                    <?php endif; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php 
                endfor; 
            else: 
            ?>
                <div class="col-12">
                    <p class="text-center text-muted">No plans available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Upgrade Plan Modal -->
<div class="modal fade" id="upgradeModal" tabindex="-1">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upgrade plan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <p>You are about to upgrade to the <strong id="selected-plan-name"></strong> plan.</p>
                <p>Price: <strong>€<span id="selected-plan-price"></span>/month</strong></p>
                
                <?php if ($currentPlan && $currentPlan['subscription_status'] === 'trial'): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This will convert your trial to a paid subscription.
                    </div>
                <?php endif; ?>
                
                <p class="text-muted small">
                    Note: This is a demo implementation. In a real application, you would integrate with a payment processor like Stripe or PayPal.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Upgrade</button>
                <input type="hidden" name="action" value="upgrade_plan">
                <input type="hidden" name="plan_id" id="upgrade-plan-id">
                <?php echo csrf_field(); ?>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Cancel subscription</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="post">
            <div class="modal-body">
                <p>Are you sure you want to cancel your subscription?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Your account will be downgraded to the Free plan, and you'll lose access to premium features.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep subscription</button>
                <button type="submit" class="btn btn-danger">Cancel subscription</button>
                <input type="hidden" name="action" value="cancel_subscription">
                <?php echo csrf_field(); ?>
            </div>
        </form>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle upgrade button clicks - add protection against multiple event listeners
    const upgradeButtons = document.querySelectorAll('.upgrade-btn');
    
    upgradeButtons.forEach(button => {
        // Remove any existing event listeners to prevent duplicates
        button.removeEventListener('click', handleUpgradeClick);
        
        // Add the event listener
        button.addEventListener('click', handleUpgradeClick);
    });
    
    // Initialize cancel subscription modal
    const cancelButton = document.querySelector('[data-bs-target="#cancelModal"]');
    if (cancelButton) {
        cancelButton.addEventListener('click', function(event) {
            // Show the modal if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                const modalElement = document.getElementById('cancelModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }
        });
    }
    
    function handleUpgradeClick(event) {
        // Prevent default behavior and stop propagation
        event.preventDefault();
        event.stopPropagation();
        
        const button = event.currentTarget;
        const planId = button.dataset.planId;
        const planName = button.dataset.planName;
        const planPrice = button.dataset.planPrice;
        
        // Validate that we have the required data
        if (!planId || !planName || planPrice === undefined) {
            console.error('Missing plan data');
            return;
        }
        
        // Update modal content safely
        const selectedPlanName = document.getElementById('selected-plan-name');
        const selectedPlanPrice = document.getElementById('selected-plan-price');
        const upgradePlanId = document.getElementById('upgrade-plan-id');
        
        if (selectedPlanName && selectedPlanPrice && upgradePlanId) {
            selectedPlanName.textContent = planName;
            selectedPlanPrice.textContent = planPrice;
            upgradePlanId.value = planId;
            
            // Show the modal only if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                const modalElement = document.getElementById('upgradeModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }
        }
    }
});
</script>
