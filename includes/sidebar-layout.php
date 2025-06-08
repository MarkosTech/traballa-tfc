<?php
/**
 * Traballa - Sidebar layout
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


// Handle organization switching
if (isset($_GET['set_organization'])) {
   $org_id = (int)$_GET['set_organization'];
   if($org_id < 0) {
       unset($_SESSION['current_organization_id']);
   } else if (isOrganizationMember($pdo, $_SESSION['user_id'], $org_id)) {
       $_SESSION['current_organization_id'] = $org_id;
   }

    // Redirect to remove the query parameter
    $redirect_url = 'index.php';
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        // Use friendly URL if .htaccess is enabled
        if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
            $redirect_url = $page;
        } else {
            $redirect_url .= '?page=' . $page;
        }
    }
    header("Location: $redirect_url");
    exit();
}

// Get user's organizations for the dropdown - do this after potential redirects
$user_organizations = getUserOrganizations($pdo, $_SESSION['user_id']);

// Update last_activity timestamp on page load to keep session alive
if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

?>

<!-- Preloader -->
<div class="preloader" id="preloader">
    <div class="spinner"></div>
</div>

<div class="modern-layout">
    <!-- Left Sidebar Navigation -->
    <div class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <img src="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/img/traballa-logo-noBgWhite.png" alt="Traballa" class="sidebar-logo">
            <button class="sidebar-toggle btn btn-link text-white" id="toggle-sidebar" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="sidebar-content">
            <ul class="nav flex-column">
                
                <li class="nav-item">
                    <a href="<?php echo route_url('dashboard'); ?>" class="nav-link <?php echo is_current_route('dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route_url('kanban'); ?>" class="nav-link <?php echo is_current_route('kanban') ? 'active' : ''; ?>" data-bs-toggle="modal" data-bs-target="#kanbanProjectModal">
                        <i class="fas fa-columns"></i> <span>Kanban</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route_url('work-hours'); ?>" class="nav-link <?php echo is_current_route('work-hours') ? 'active' : ''; ?>">
                        <i class="fas fa-clock"></i> <span>Work hours</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route_url('reports'); ?>" class="nav-link <?php echo is_current_route('reports') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar"></i> <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route_url('calendar'); ?>" class="nav-link <?php echo is_current_route('calendar') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt"></i> <span>Calendar</span>
                    </a>
                </li>
                
                <?php if (hasManagementPermissions()): ?>
                <li class="nav-item nav-section">
                    <span>Management</span>
                </li>
                <li class="nav-item">
                    <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>" class="nav-link <?php echo ($page == 'projects') ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram"></i> <span>Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/organizations' : 'index.php?page=organizations'; ?>" class="nav-link <?php echo ($page == 'organizations') ? 'active' : ''; ?>">
                        <i class="fas fa-building"></i> <span>Organizations</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item nav-section">
                    <span>User</span>
                </li>
                <li class="nav-item">
                    <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/profile' : 'index.php?page=profile'; ?>" class="nav-link <?php echo ($page == 'profile') ? 'active' : ''; ?>">
                        <i class="fas fa-id-card"></i> <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/settings' : 'index.php?page=settings'; ?>" class="nav-link <?php echo ($page == 'settings') ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                </li>
                
                <!-- Global Help Button -->
                <li class="nav-item">
                    <a href="#" class="nav-link" id="global-help-btn" title="Ayuda (F1)">
                        <i class="fas fa-question-circle"></i> <span>Ayuda</span>
                    </a>
                </li>
            </ul>

            <?php if (isAdmin()): ?>
            <li class="nav-item nav-section">
                <span>Administration</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/users' : 'index.php?page=users'; ?>" class="nav-link <?php echo ($page == 'users') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> <span>User management</span>
                </a>
            </li>
            <?php endif; ?>
                

            <?php if (!empty($user_organizations)): ?>
            <div class="organization-selector mt-3 p-3">
                <small class="text-muted d-block mb-2">Current organization</small>
                <div class="dropdown w-100">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle w-100 text-start" type="button" id="orgDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-building me-1"></i> 
                        <?php 
                        $current_org = isset($_SESSION['current_organization_id']) ? 
                            getOrganizationById($pdo, $_SESSION['current_organization_id']) : null;
                        echo sanitize_output($current_org ? $current_org['name'] : 'Select organization');
                        ?>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="orgDropdown">
                        <li>
                            <a class="dropdown-item <?php echo (!isset($_SESSION['current_organization_id'])) ? 'active' : ''; ?>" 
                               href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) 
                                      ? '/' . ($page != 'dashboard' ? $page : '') . '?set_organization=-1' 
                                      : 'index.php?page=' . $page . '&set_organization=-1'; ?>">
                                All organizations
                            </a>
                        </li>
                        <?php foreach ($user_organizations as $org): ?>
                        <li>
                            <a class="dropdown-item <?php echo (isset($_SESSION['current_organization_id']) && $_SESSION['current_organization_id'] == $org['id']) ? 'active' : ''; ?>" 
                               href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) 
                                      ? '/' . ($page != 'dashboard' ? $page : '') . '?set_organization=' . $org['id'] 
                                      : 'index.php?page=' . $page . '&set_organization=' . $org['id']; ?>">
                                <?php echo sanitize_output($org['name']); ?>
                                <?php if ($org['is_admin']): ?>
                                    <span class="badge bg-primary ms-1">Admin</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="content-wrapper">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            
            <!-- Content will be included here -->
            <?php include $page_file; ?>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include __DIR__ . '/footer.php'; ?>
</div>

<link rel="stylesheet" href="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/css/sidebar.css">
<script src="<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>assets/js/sidebar.js"></script>

<!-- Modal para seleccionar proyecto para Kanban -->
<div class="modal fade" id="kanbanProjectModal" tabindex="-1" aria-labelledby="kanbanProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kanbanProjectModalLabel">Seleccionar proyecto para Kanban</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Obtener los proyectos del usuario (filtrados por organización si está seleccionada)
                $organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;
                $user_projects = getUserActiveProjects($pdo, $_SESSION['user_id'], $organization_id);
                
                if (empty($user_projects)) {
                    echo '<div class="alert alert-info">No tienes proyectos activos';
                    if ($organization_id) {
                        echo ' en la organización actual. Prueba a seleccionar otra organización o contacta con tu administrador.';
                    } else {
                        echo '. Contacta con tu administrador para ser asignado a un proyecto.';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="list-group">';
                    foreach ($user_projects as $project) {
                        $url = function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) 
                               ? '/kanban/' . $project['id'] 
                               : 'index.php?page=kanban&id=' . $project['id'];
                        echo '<a href="' . $url . '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">';
                        echo sanitize_output($project['name']);
                        
                        // Mostrar badge si el usuario es manager del proyecto
                        if (isset($project['is_manager']) && $project['is_manager']) {
                            echo '<span class="badge bg-primary rounded-pill">Manager</span>';
                        }
                        
                        echo '</a>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
