-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gianhost_workhours`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_hours` float NOT NULL,
  `overtime` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `breaks`
--

CREATE TABLE `breaks` (
  `id` int(11) NOT NULL,
  `work_hour_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` float DEFAULT NULL,
  `type` enum('lunch','break','other') NOT NULL DEFAULT 'break',
  `notes` text DEFAULT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `event_type` enum('personal','project','organization') NOT NULL DEFAULT 'personal',
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_columns`
--

CREATE TABLE `kanban_columns` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `tab_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_tabs`
--

CREATE TABLE `kanban_tabs` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_tasks`
--

CREATE TABLE `kanban_tasks` (
  `id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `tab_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','pending','completed') NOT NULL DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_members`
--

CREATE TABLE `organization_members` (
  `id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `joined_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','completed','on_hold') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_kanban_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_manager` tinyint(1) NOT NULL DEFAULT 0,
  `joined_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','employee') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(255) NOT NULL,
  `session_data` text NOT NULL,
  `expiry` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_hours`
--

CREATE TABLE `work_hours` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `clock_in` datetime NOT NULL,
  `clock_out` datetime DEFAULT NULL,
  `total_hours` float DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('working','completed') NOT NULL DEFAULT 'working'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `breaks`
--
ALTER TABLE `breaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_hour_id` (`work_hour_id`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dates` (`start_date`,`end_date`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_project_id` (`project_id`),
  ADD KEY `idx_organization_id` (`organization_id`);

--
-- Indexes for table `kanban_columns`
--
ALTER TABLE `kanban_columns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `tab_id` (`tab_id`);

--
-- Indexes for table `kanban_tabs`
--
ALTER TABLE `kanban_tabs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kanban_tasks`
--
ALTER TABLE `kanban_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `column_id` (`column_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `tab_id` (`tab_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `ip_address` (`ip_address`),
  ADD KEY `attempt_time` (`attempt_time`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_members`
--
ALTER TABLE `organization_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_org_member` (`organization_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_project_member` (`project_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_expiry` (`expiry`);

--
-- Indexes for table `work_hours`
--
ALTER TABLE `work_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `breaks`
--
ALTER TABLE `breaks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kanban_columns`
--
ALTER TABLE `kanban_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kanban_tabs`
--
ALTER TABLE `kanban_tabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kanban_tasks`
--
ALTER TABLE `kanban_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_members`
--
ALTER TABLE `organization_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_hours`
--
ALTER TABLE `work_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analytics`
--
ALTER TABLE `analytics`
  ADD CONSTRAINT `analytics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `analytics_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `breaks`
--
ALTER TABLE `breaks`
  ADD CONSTRAINT `breaks_ibfk_1` FOREIGN KEY (`work_hour_id`) REFERENCES `work_hours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kanban_columns`
--
ALTER TABLE `kanban_columns`
  ADD CONSTRAINT `kanban_columns_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kanban_columns_ibfk_2` FOREIGN KEY (`tab_id`) REFERENCES `kanban_tabs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kanban_tabs`
--
ALTER TABLE `kanban_tabs`
  ADD CONSTRAINT `kanban_tabs_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kanban_tasks`
--
ALTER TABLE `kanban_tasks`
  ADD CONSTRAINT `kanban_tasks_ibfk_1` FOREIGN KEY (`column_id`) REFERENCES `kanban_columns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kanban_tasks_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kanban_tasks_ibfk_3` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kanban_tasks_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kanban_tasks_ibfk_5` FOREIGN KEY (`tab_id`) REFERENCES `kanban_tabs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_members`
--
ALTER TABLE `organization_members`
  ADD CONSTRAINT `organization_members_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `organization_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_hours`
--
ALTER TABLE `work_hours`
  ADD CONSTRAINT `work_hours_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_hours_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Subscription system for Traballa
-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `max_users` int(11) DEFAULT 1,
  `max_projects` int(11) DEFAULT 3,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_subscriptions`
--

CREATE TABLE `organization_subscriptions` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `status` enum('active','expired','cancelled','trial') NOT NULL DEFAULT 'trial',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `trial_end_date` datetime DEFAULT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_usage`
--

CREATE TABLE `subscription_usage` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `users_count` int(11) NOT NULL DEFAULT 0,
  `projects_count` int(11) NOT NULL DEFAULT 0,
  `api_calls` int(11) NOT NULL DEFAULT 0,
  `storage_used` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  UNIQUE KEY `unique_org_month` (`organization_id`, `month`),
  FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Insert default subscription plans
--

INSERT INTO `subscription_plans` (`name`, `description`, `price`, `billing_cycle`, `max_users`, `max_projects`, `features`) VALUES
('Free', 'Perfect for individuals', 0.00, 'monthly', 1, 3, JSON_OBJECT(
    'time_tracking', true,
    'basic_reports', true,
    'team_collaboration', false,
    'advanced_reports', false,
    'api_access', false,
    'priority_support', false,
    'calendar_integration', false,
    'custom_branding', false
)),
('Pro', 'Ideal for small teams', 9.00, 'monthly', 10, -1, JSON_OBJECT(
    'time_tracking', true,
    'basic_reports', true,
    'team_collaboration', true,
    'advanced_reports', true,
    'api_access', false,
    'priority_support', false,
    'calendar_integration', true,
    'custom_branding', false
)),
('Enterprise', 'Built for organizations', 15.00, 'monthly', -1, -1, JSON_OBJECT(
    'time_tracking', true,
    'basic_reports', true,
    'team_collaboration', true,
    'advanced_reports', true,
    'api_access', true,
    'priority_support', true,
    'calendar_integration', true,
    'custom_branding', true
));

-- Add plan_id to organizations table to track current plan
ALTER TABLE `organizations` ADD COLUMN `current_plan_id` int(11) DEFAULT 1 AFTER `description`;
ALTER TABLE `organizations` ADD FOREIGN KEY (`current_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL;

-- Add subscription status to organizations table
ALTER TABLE `organizations` ADD COLUMN `subscription_status` enum('active','trial','expired','cancelled') DEFAULT 'trial' AFTER `current_plan_id`;
ALTER TABLE `organizations` ADD COLUMN `trial_ends_at` datetime DEFAULT NULL AFTER `subscription_status`;

-- Create indexes for better performance
CREATE INDEX `idx_subscription_status` ON `organization_subscriptions` (`status`);
CREATE INDEX `idx_subscription_dates` ON `organization_subscriptions` (`start_date`, `end_date`);
CREATE INDEX `idx_organization_plan` ON `organizations` (`current_plan_id`);

-- Insert default admin user
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@traballa.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());
-- Default password is 'password' - change immediately after setup

-- Create default organization for admin
INSERT IGNORE INTO `organizations` (`id`, `name`, `description`, `current_plan_id`, `subscription_status`, `created_at`) VALUES
(1, 'Default Organization', 'Default organization for initial setup', 1, 'active', NOW());

-- Add admin as organization admin
INSERT IGNORE INTO `organization_members` (`organization_id`, `user_id`, `is_admin`, `joined_at`) VALUES
(1, 1, 1, NOW());
