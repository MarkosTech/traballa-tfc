<?php
/**
 * Subscription Manager for Traballa
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 */

class SubscriptionManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all available subscription plans
     */
    public function getPlans() {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get a specific plan by ID
     */
    public function getPlan($plan_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription_plans WHERE id = ? AND is_active = 1");
        $stmt->execute([$plan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get a specific plan by name
     */
    public function getPlanByName($plan_name) {
        $stmt = $this->pdo->prepare("SELECT * FROM subscription_plans WHERE name = ? AND is_active = 1");
        $stmt->execute([$plan_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get organization's current subscription
     */
    public function getOrganizationSubscription($organization_id) {
        $stmt = $this->pdo->prepare("
            SELECT os.*, sp.name as plan_name, sp.price, sp.max_users, sp.max_projects, sp.features
            FROM organization_subscriptions os
            JOIN subscription_plans sp ON os.plan_id = sp.id
            WHERE os.organization_id = ? AND os.status = 'active'
            ORDER BY os.created_at DESC LIMIT 1
        ");
        $stmt->execute([$organization_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get organization's current plan from organizations table
     */
    public function getOrganizationPlan($organization_id) {
        $stmt = $this->pdo->prepare("
            SELECT o.current_plan_id, o.subscription_status, o.trial_ends_at,
                   sp.name, sp.price, sp.max_users, sp.max_projects, sp.features
            FROM organizations o
            LEFT JOIN subscription_plans sp ON o.current_plan_id = sp.id
            WHERE o.id = ?
        ");
        $stmt->execute([$organization_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if organization can perform an action based on their plan
     */
    public function canPerformAction($organization_id, $action) {
        $plan = $this->getOrganizationPlan($organization_id);
        
        if (!$plan) {
            return false;
        }
        
        // Check if subscription is active or in trial
        if (!in_array($plan['subscription_status'], ['active', 'trial'])) {
            return false;
        }
        
        // Check trial expiration
        if ($plan['subscription_status'] === 'trial' && $plan['trial_ends_at']) {
            if (new DateTime() > new DateTime($plan['trial_ends_at'])) {
                return false;
            }
        }
        
        $features = json_decode($plan['features'], true);
        
        switch ($action) {
            case 'add_user':
                return $this->canAddUser($organization_id, $plan);
            case 'create_project':
                return $this->canCreateProject($organization_id, $plan);
            case 'team_collaboration':
                return isset($features['team_collaboration']) && $features['team_collaboration'];
            case 'advanced_reports':
                return isset($features['advanced_reports']) && $features['advanced_reports'];
            case 'api_access':
                return isset($features['api_access']) && $features['api_access'];
            case 'priority_support':
                return isset($features['priority_support']) && $features['priority_support'];
            case 'calendar_integration':
                return isset($features['calendar_integration']) && $features['calendar_integration'];
            default:
                return true; // Basic features are available to all plans
        }
    }
    
    /**
     * Check if organization can add more users
     */
    private function canAddUser($organization_id, $plan) {
        if ($plan['max_users'] == -1) { // Unlimited
            return true;
        }
        
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM organization_members WHERE organization_id = ?");
        $stmt->execute([$organization_id]);
        $current_users = $stmt->fetchColumn();
        
        return $current_users < $plan['max_users'];
    }
    
    /**
     * Check if organization can create more projects
     */
    private function canCreateProject($organization_id, $plan) {
        if ($plan['max_projects'] == -1) { // Unlimited
            return true;
        }
        
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM projects WHERE organization_id = ?");
        $stmt->execute([$organization_id]);
        $current_projects = $stmt->fetchColumn();
        
        return $current_projects < $plan['max_projects'];
    }
    
    /**
     * Get usage statistics for an organization
     */
    public function getUsageStats($organization_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM organization_members WHERE organization_id = ?) as users_count,
                (SELECT COUNT(*) FROM projects WHERE organization_id = ?) as projects_count
        ");
        $stmt->execute([$organization_id, $organization_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Upgrade organization to a new plan
     */
    public function upgradePlan($organization_id, $new_plan_id, $payment_method = null, $payment_id = null) {
        try {
            $this->pdo->beginTransaction();
            
            // Update organization's current plan
            $stmt = $this->pdo->prepare("
                UPDATE organizations 
                SET current_plan_id = ?, subscription_status = 'active', trial_ends_at = NULL
                WHERE id = ?
            ");
            $stmt->execute([$new_plan_id, $organization_id]);
            
            // Create new subscription record
            $stmt = $this->pdo->prepare("
                INSERT INTO organization_subscriptions 
                (organization_id, plan_id, status, start_date, payment_method, payment_id)
                VALUES (?, ?, 'active', NOW(), ?, ?)
            ");
            $stmt->execute([$organization_id, $new_plan_id, $payment_method, $payment_id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Start trial for organization
     */
    public function startTrial($organization_id, $plan_id = 2, $trial_days = 14) {
        $trial_end = new DateTime();
        $trial_end->add(new DateInterval("P{$trial_days}D"));
        
        try {
            $this->pdo->beginTransaction();
            
            // Update organization with trial plan
            $stmt = $this->pdo->prepare("
                UPDATE organizations 
                SET current_plan_id = ?, subscription_status = 'trial', trial_ends_at = ?
                WHERE id = ?
            ");
            $stmt->execute([$plan_id, $trial_end->format('Y-m-d H:i:s'), $organization_id]);
            
            // Create trial subscription record
            $stmt = $this->pdo->prepare("
                INSERT INTO organization_subscriptions 
                (organization_id, plan_id, status, start_date, trial_end_date)
                VALUES (?, ?, 'trial', NOW(), ?)
            ");
            $stmt->execute([$organization_id, $plan_id, $trial_end->format('Y-m-d H:i:s')]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Cancel subscription
     */
    public function cancelSubscription($organization_id) {
        try {
            $this->pdo->beginTransaction();
            
            // Update organization to free plan
            $stmt = $this->pdo->prepare("
                UPDATE organizations 
                SET current_plan_id = 1, subscription_status = 'cancelled'
                WHERE id = ?
            ");
            $stmt->execute([$organization_id]);
            
            // Update current subscription
            $stmt = $this->pdo->prepare("
                UPDATE organization_subscriptions 
                SET status = 'cancelled', end_date = NOW()
                WHERE organization_id = ? AND status = 'active'
            ");
            $stmt->execute([$organization_id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Update usage statistics
     */
    public function updateUsageStats($organization_id) {
        if (!$organization_id) {
            return; // Skip if no organization_id
        }
        
        $current_month = date('Y-m-01');
        $stats = $this->getUsageStats($organization_id);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO subscription_usage (organization_id, month, users_count, projects_count)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            users_count = VALUES(users_count),
            projects_count = VALUES(projects_count),
            updated_at = NOW()
        ");
        
        $stmt->execute([
            $organization_id,
            $current_month,
            $stats['users_count'],
            $stats['projects_count']
        ]);
    }
    
    /**
     * Assign a plan to an organization (for new organizations)
     */
    public function assignPlan($organization_id, $plan_id) {
        try {
            $this->pdo->beginTransaction();
            
            // Update organization with plan
            $stmt = $this->pdo->prepare("
                UPDATE organizations 
                SET current_plan_id = ?, subscription_status = 'active'
                WHERE id = ?
            ");
            $stmt->execute([$plan_id, $organization_id]);
            
            // Create subscription record
            $stmt = $this->pdo->prepare("
                INSERT INTO organization_subscriptions 
                (organization_id, plan_id, status, start_date)
                VALUES (?, ?, 'active', NOW())
            ");
            $stmt->execute([$organization_id, $plan_id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Get plan limits as formatted string
     */
    public function getPlanLimitsText($plan) {
        $limits = [];
        
        if ($plan['max_users'] == -1) {
            $limits[] = 'Unlimited users';
        } else {
            $limits[] = $plan['max_users'] . ' user' . ($plan['max_users'] > 1 ? 's' : '');
        }
        
        if ($plan['max_projects'] == -1) {
            $limits[] = 'Unlimited projects';
        } else {
            $limits[] = $plan['max_projects'] . ' project' . ($plan['max_projects'] > 1 ? 's' : '');
        }
        
        return implode(', ', $limits);
    }
}
