<?php
/**
 * Traballa - Reports
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

// Default date range (current month)
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');

// Get current organization ID
$organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;

// Process date filter and other filters
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
   $start_date = $_GET['start_date'];
   $end_date = $_GET['end_date'];
}

// Get project filter
$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;

// Get user's projects for filter dropdown
if ($organization_id) {
   $user_projects = getUserProjects($pdo, $_SESSION['user_id'], $organization_id);
} else {
   $user_projects = getUserProjects($pdo, $_SESSION['user_id']);
}

// Get Traballa for the selected period with filters
$work_hours = getUserWorkHours($pdo, $_SESSION['user_id'], $start_date, $end_date, $project_id, $organization_id);

// Calculate daily totals for chart
$daily_totals = [];
$current_date = new DateTime($start_date);
$end_date_obj = new DateTime($end_date);

while ($current_date <= $end_date_obj) {
   $date_str = $current_date->format('Y-m-d');
   $daily_totals[$date_str] = 0;
   $current_date->modify('+1 day');
}

// Project totals for pie chart
$project_totals = [];
$project_names = [];

foreach ($work_hours as $work) {
   if ($work['status'] === 'completed') {
       $date = date('Y-m-d', strtotime($work['clock_in']));
       if (isset($daily_totals[$date])) {
           $daily_totals[$date] += $work['total_hours'];
       }
       
       // Track hours by project
       $project_name = $work['project_name'];
       if (!isset($project_totals[$project_name])) {
           $project_totals[$project_name] = 0;
           $project_names[] = $project_name;
       }
       $project_totals[$project_name] += $work['total_hours'];
   }
}

// Calculate summary statistics
$total_hours = array_sum($daily_totals);
$work_days = count(array_filter($daily_totals));
$avg_hours = $work_days > 0 ? $total_hours / $work_days : 0;
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
echo $breadcrumb->render(current_route());
?>

<div class="d-flex justify-content-between align-items-center mb-4">
   <h1 class="h3 mb-0">Reports & Analytics</h1>
</div>

<!-- Filter Card -->
<div class="card mb-4">
   <div class="card-body">
       <form method="get" action="" class="row g-3">
           <input type="hidden" name="page" value="reports">
           <div class="col-md-3">
               <label for="start_date" class="form-label">Start date</label>
               <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
           </div>
           <div class="col-md-3">
               <label for="end_date" class="form-label">End date</label>
               <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
           </div>
           <div class="col-md-3">
               <label for="project_id" class="form-label">Project</label>
               <select class="form-select" id="project_id" name="project_id">
                   <option value="">All projects</option>
                   <?php foreach ($user_projects as $project): ?>
                       <option value="<?php echo $project['id']; ?>" <?php echo ($project_id == $project['id']) ? 'selected' : ''; ?>>
                           <?php echo $project['name']; ?>
                       </option>
                   <?php endforeach; ?>
               </select>
           </div>
           <div class="col-md-3 d-flex align-items-end">
               <button type="submit" class="btn btn-primary me-2">
                   <i class="fas fa-filter me-1"></i> Generate report
               </button>
               <button type="button" class="btn btn-outline-primary" onclick="printReport()">
                   <i class="fas fa-print me-1"></i> Print
               </button>
           </div>
       </form>
   </div>
</div>

<div id="reportContent">
   <!-- Report Header -->
   <div class="d-none d-print-block mb-4">
       <h2 class="text-center">Traballa report</h2>
       <p class="text-center">
           Period: <?php echo date('M d, Y', strtotime($start_date)); ?> to 
           <?php echo date('M d, Y', strtotime($end_date)); ?>
       </p>
       <p class="text-center">
           Employee: <?php echo $_SESSION['user_name']; ?>
       </p>
       <?php if ($organization_id): ?>
           <p class="text-center">
               Organization: <?php echo getOrganizationById($pdo, $organization_id)['name']; ?>
           </p>
       <?php endif; ?>
       <?php if ($project_id): ?>
           <p class="text-center">
               Project: <?php echo getProjectById($pdo, $project_id)['name']; ?>
           </p>
       <?php endif; ?>
   </div>

   <!-- Summary Cards -->
   <div class="row mb-4">
       <div class="col-md-4">
           <div class="card">
               <div class="card-body text-center">
                   <h5 class="card-title">Total hours</h5>
                   <p class="display-4 fw-bold"><?php echo number_format($total_hours, 1); ?></p>
               </div>
           </div>
       </div>
       <div class="col-md-4">
           <div class="card">
               <div class="card-body text-center">
                   <h5 class="card-title">Work days</h5>
                   <p class="display-4 fw-bold"><?php echo $work_days; ?></p>
               </div>
           </div>
       </div>
       <div class="col-md-4">
           <div class="card">
               <div class="card-body text-center">
                   <h5 class="card-title">Average hours/day</h5>
                   <p class="display-4 fw-bold"><?php echo number_format($avg_hours, 1); ?></p>
               </div>
           </div>
       </div>
   </div>

   <!-- Charts -->
   <div class="row mb-4">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">
                   <h5 class="mb-0">Daily hours</h5>
               </div>
               <div class="card-body">
                   <canvas id="dailyChart" height="300"></canvas>
               </div>
           </div>
       </div>
       <div class="col-md-4">
           <div class="card">
               <div class="card-header">
                   <h5 class="mb-0">Project distribution</h5>
               </div>
               <div class="card-body">
                   <canvas id="projectDistributionChart" height="300"></canvas>
               </div>
           </div>
       </div>
   </div>

   <!-- Traballa Table -->
   <div class="card">
       <div class="card-header d-flex justify-content-between align-items-center">
           <h5 class="mb-0">Detailed Traballa</h5>
           <button type="button" class="btn btn-sm btn-outline-primary no-print" onclick="exportTableToCSV('work_hours_report.csv')">
               <i class="fas fa-download me-1"></i> Export CSV
           </button>
       </div>
       <div class="card-body">
           <div class="table-responsive">
               <table class="table table-bordered" id="workHoursTable">
                   <thead>
                       <tr>
                           <th>Date</th>
                           <th>Project</th>
                           <th>Clock in</th>
                           <th>Clock out</th>
                           <th>Hours</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php if (empty($work_hours)): ?>
                           <tr>
                               <td colspan="5" class="text-center">No Traballa found for the selected period.</td>
                           </tr>
                       <?php else: ?>
                           <?php foreach ($work_hours as $work): ?>
                               <?php if ($work['status'] === 'completed'): ?>
                                   <tr>
                                       <td><?php echo date('M d, Y', strtotime($work['clock_in'])); ?></td>
                                       <td><?php echo $work['project_name']; ?></td>
                                       <td><?php echo date('h:i A', strtotime($work['clock_in'])); ?></td>
                                       <td><?php echo date('h:i A', strtotime($work['clock_out'])); ?></td>
                                       <td><?php echo number_format($work['total_hours'], 1); ?></td>
                                   </tr>
                               <?php endif; ?>
                           <?php endforeach; ?>
                       <?php endif; ?>
                   </tbody>
               </table>
           </div>
       </div>
   </div>
</div>

<script>
// Set chart data for JavaScript access
window.dailyLabels = [];
window.dailyData = [];
window.projectLabels = [];
window.projectData = [];

<?php
$current_date = new DateTime($start_date);
$end_date_obj = new DateTime($end_date);

while ($current_date <= $end_date_obj) {
    $date_str = $current_date->format('Y-m-d');
    $date_label = $current_date->format('M d');
    echo "window.dailyLabels.push('$date_label');\n";
    echo "window.dailyData.push(" . $daily_totals[$date_str] . ");\n";
    $current_date->modify('+1 day');
}

foreach ($project_names as $index => $name) {
    echo "window.projectLabels.push('$name');\n";
    echo "window.projectData.push(" . $project_totals[$name] . ");\n";
}
?>
</script>
<script src="assets/js/reports.js"></script>

<style>
@media print {
   .navbar, .btn, form, .no-print {
       display: none !important;
   }
   
   .card {
       border: 1px solid #ddd !important;
       break-inside: avoid;
   }
   
   .card-header {
       background-color: #f8f9fa !important;
       border-bottom: 1px solid #ddd !important;
   }
   
   body {
       padding: 20px !important;
   }
}
</style>

