<?php
/**
 * Traballa - Dashboard
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

// Get current organization ID
$organization_id = isset($_SESSION['current_organization_id']) ? $_SESSION['current_organization_id'] : null;

// Get current work status
$current_status = getCurrentWorkStatus($pdo, $_SESSION['user_id']);
$is_working = $current_status !== false;

// Get work summary with organization filter
$today_hours = getWorkSummary($pdo, $_SESSION['user_id'], 'today', null, $organization_id);
$week_hours = getWorkSummary($pdo, $_SESSION['user_id'], 'week', null, $organization_id);
$month_hours = getWorkSummary($pdo, $_SESSION['user_id'], 'month', null, $organization_id);

// Get user's active projects for the project selector (filtered by organization if selected)
$user_projects = getUserActiveProjects($pdo, $_SESSION['user_id'], $organization_id);

// Check for active break if currently working
$active_break = null;
if ($is_working) {
    $break_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? AND status = 'active' LIMIT 1");
    $break_stmt->execute([$current_status['id']]);
    $active_break = $break_stmt->fetch(PDO::FETCH_ASSOC);
}

// Process clock in/out and break actions
if (isset($_POST['action'])) {
   $action = $_POST['action'];
   $user_id = $_SESSION['user_id'];
   $current_time = date('Y-m-d H:i:s');
   
   if ($action === 'clock_in' && !$is_working) {
       // Check if project_id is provided
       if (!isset($_POST['project_id']) || empty($_POST['project_id'])) {
           $error = "Please select a project before clocking in.";
       } else {
           $project_id = (int)$_POST['project_id'];
           
           // Check if user is a member of this project
           if (isProjectMember($pdo, $user_id, $project_id)) {
               // Clock in
               $stmt = $pdo->prepare("INSERT INTO work_hours (user_id, project_id, clock_in, status) VALUES (?, ?, ?, 'working')");
               if ($stmt->execute([$user_id, $project_id, $current_time])) {
                   // Refresh the page to update status
                   header("Location: index.php?page=dashboard");
                   exit();
               } else {
                   $error = "Error clocking in";
               }
           } else {
               $error = "You are not a member of the selected project.";
           }
       }
   } elseif ($action === 'clock_out' && $is_working) {
       // Check if there's an active break
       $check_break_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? AND status = 'active'");
       $check_break_stmt->execute([$current_status['id']]);
       
       if ($check_break_stmt->fetch()) {
           $error = "Please end your active break before clocking out.";
       } else {
           // Clock out
           $work_id = $current_status['id'];
           $clock_in = $current_status['clock_in'];
           $total_hours = calculateHours($clock_in, $current_time);
           
           // Subtract break times
           $breaks_stmt = $pdo->prepare("SELECT SUM(duration) as total_break_hours FROM breaks WHERE work_hour_id = ? AND status = 'completed'");
           $breaks_stmt->execute([$work_id]);
           $break_result = $breaks_stmt->fetch(PDO::FETCH_ASSOC);
           
           if ($break_result && $break_result['total_break_hours']) {
               $total_hours -= $break_result['total_break_hours'];
           }
           
           $stmt = $pdo->prepare("UPDATE work_hours SET clock_out = ?, total_hours = ?, status = 'completed' WHERE id = ?");
           if ($stmt->execute([$current_time, $total_hours, $work_id])) {
               // Refresh the page to update status
               header("Location: index.php?page=dashboard");
               exit();
           } else {
               $error = "Error clocking out";
           }
       }
   } elseif ($action === 'start_break' && $is_working) {
       // Check if there's already an active break
       $check_break_stmt = $pdo->prepare("SELECT * FROM breaks WHERE work_hour_id = ? AND status = 'active'");
       $check_break_stmt->execute([$current_status['id']]);
       
       if ($check_break_stmt->fetch()) {
           $error = "You already have an active break.";
       } else {
           $break_type = sanitize($_POST['break_type']);
           $notes = isset($_POST['break_notes']) ? sanitize($_POST['break_notes']) : '';
           
           // Start break
           $stmt = $pdo->prepare("INSERT INTO breaks (work_hour_id, start_time, type, notes, status) VALUES (?, ?, ?, ?, 'active')");
           
           if ($stmt->execute([$current_status['id'], $current_time, $break_type, $notes])) {
               // Refresh the page to update status
               header("Location: index.php?page=dashboard");
               exit();
           } else {
               $error = "Error starting break";
           }
       }
   } elseif ($action === 'end_break' && $is_working && $active_break) {
       // End break
       $break_id = $active_break['id'];
       $start_time = $active_break['start_time'];
       $duration = calculateHours($start_time, $current_time);
       
       $stmt = $pdo->prepare("UPDATE breaks SET end_time = ?, duration = ?, status = 'completed' WHERE id = ?");
       
       if ($stmt->execute([$current_time, $duration, $break_id])) {
           // Refresh the page to update status
           header("Location: index.php?page=dashboard");
           exit();
       } else {
           $error = "Error ending break";
       }
   }
}

// Get recent Traballa (last 5 entries) filtered by organization
$recent_work_hours = getUserWorkHours($pdo, $_SESSION['user_id'], null, null, null, $organization_id);
$recent_work_hours = array_slice($recent_work_hours, 0, 5);

// Get weekly hours data for chart
$weekly_hours = [];
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));

// Initialize array with zeros for each day of the week
for ($i = 0; $i < 7; $i++) {
    $day = date('Y-m-d', strtotime($week_start . " +$i days"));
    $weekly_hours[$day] = 0;
}

// Query to get weekly hours
$weekly_query = "SELECT DATE(wh.clock_in) as work_date, SUM(wh.total_hours) as daily_hours 
                FROM work_hours wh 
                JOIN projects p ON wh.project_id = p.id 
                WHERE wh.user_id = ? 
                AND wh.status = 'completed' 
                AND DATE(wh.clock_in) BETWEEN ? AND ?";

$params = [$_SESSION['user_id'], $week_start, $week_end];

if ($organization_id) {
    $weekly_query .= " AND p.organization_id = ?";
    $params[] = $organization_id;
}

$weekly_query .= " GROUP BY DATE(wh.clock_in)";
$weekly_stmt = $pdo->prepare($weekly_query);
$weekly_stmt->execute($params);

while ($row = $weekly_stmt->fetch(PDO::FETCH_ASSOC)) {
    $weekly_hours[$row['work_date']] = (float)$row['daily_hours'];
}

// Get upcoming calendar events
require_once '../includes/Calendar.php';
$calendar = new Calendar($pdo);

// Get events for the next 7 days
$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime('+7 days'));
$user_id = $_SESSION['user_id'];

$upcomingEvents = [];
if(isset($user_id) && $user_id >= 0) {
    $upcomingEvents = $calendar->getEventsForDateRange($today, $nextWeek, $user_id);
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$welcomeMessage = "Welcome back";

// Set the welcome message based on the time of day
$currentHour = date('H');
if ($currentHour < 12) {
    $welcomeMessage = "Good morning";
} elseif ($currentHour < 18) {
    $welcomeMessage = "Good afternoon";
} else {
    $welcomeMessage = "Good evening";
}
?>

<!-- Breadcrumbs -->
<?php
// Initialize breadcrumb with router if available
global $router;
$breadcrumb = new Breadcrumb($router);
echo $breadcrumb->render(current_route());
?>

<div class="row">
  <div class="col-md-8">
      <h1 class="h3 mb-4"><?= $welcomeMessage ?>, <?= $user_name; ?></h1>
      
      <?php if (isset($error)): ?>
          <div class="alert alert-permanent alert-dismissible alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <!-- Clock In/Out Card -->
      <div class="card mb-4">
          <div class="card-body">
              <div class="row align-items-center">
                  <div class="col-md-6">
                      <h5 class="card-title">
                          <?php if ($is_working): ?>
                              <span class="text-success"><i class="fas fa-circle me-2"></i>Currently working</span>
                              <?php if ($active_break): ?>
                                  <span class="badge bg-warning ms-2">On Break</span>
                              <?php endif; ?>
                          <?php else: ?>
                              <span class="text-secondary"><i class="far fa-circle me-2"></i>Not working</span>
                          <?php endif; ?>
                      </h5>
                      <?php if ($is_working): ?>
                          <p class="card-text">
                              Started at: <?php echo formatDateTime($current_status['clock_in']); ?>
                          </p>
                          <p class="card-text">
                              Project: <strong><?php echo sanitize_output($current_status['project_name']); ?></strong>
                          </p>
                          <div id="working-time" data-start="<?php echo strtotime($current_status['clock_in']); ?>">
                              Working for: <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                          </div>
                          <?php if ($active_break): ?>
                              <div class="mt-2 alert alert-warning py-2">
                                  <small>
                                      <strong>Break started:</strong> <?php echo date('h:i A', strtotime($active_break['start_time'])); ?> 
                                      (<?php echo ucfirst($active_break['type']); ?> break)
                                  </small>
                              </div>
                          <?php endif; ?>
                      <?php endif; ?>
                  </div>
                  <div class="col-md-6 text-md-end mt-3 mt-md-0">
                      <form method="post" action="">
                          <?php echo csrf_field(); ?>
                          <?php if ($is_working): ?>
                          
                                <?php if ($active_break): ?>
                                  <input type="hidden" name="action" value="end_break">
                                  <button type="submit" class="btn btn-warning btn-lg mb-2">
                                      <i class="fas fa-mug-hot me-2"></i>End break
                                  </button>
                              <?php else: ?>
                                  <div class="mb-2">
                                      <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#breakModal">
                                          <i class="fas fa-coffee me-2"></i>Take a break
                                      </button>
                                  </div>
                                  <input type="hidden" name="action" value="clock_out">
                                  <button type="submit" class="btn btn-danger btn-lg">
                                      <i class="fas fa-sign-out-alt me-2"></i>Clock out
                                  </button>
                              <?php endif; ?>
                          
                          <?php else: ?>
                              <input type="hidden" name="action" value="clock_in">
                              
                              <?php if (empty($user_projects)): ?>
                                  <div class="alert alert-warning mb-3">
                                      You are not assigned to any active projects<?php echo $organization_id ? ' in the selected organization' : ''; ?>. 
                                      <?php if ($organization_id): ?>
                                          Try selecting a different organization or contact your manager.
                                      <?php else: ?>
                                          Please contact your manager.
                                      <?php endif; ?>
                                  </div>
                              <?php else: ?>
                                  <div class="mb-3">
                                      <label for="project_id" class="form-label">Select project</label>
                                      <select class="form-select mb-3" id="project_id" name="project_id" required>
                                          <option value="">-- Select project --</option>
                                          <?php foreach ($user_projects as $project): ?>
                                              <option value="<?php echo (int)$project['id']; ?>"><?php echo sanitize_output($project['name']); ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn btn-success btn-lg">
                                      <i class="fas fa-sign-in-alt me-2"></i>Clock in
                                  </button>
                              <?php endif; ?>
                          <?php endif; ?>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      
      <!-- Work Summary Cards -->
      <div class="row">
          <div class="col-md-4">
              <div class="card mb-4">
                  <div class="card-body text-center">
                      <h5 class="card-title text-muted">Today</h5>
                      <p class="display-5 fw-bold"><?php echo number_format($today_hours, 1); ?></p>
                      <p class="text-muted">Hours</p>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="card mb-4">
                  <div class="card-body text-center">
                      <h5 class="card-title text-muted">This week</h5>
                      <p class="display-5 fw-bold"><?php echo number_format($week_hours, 1); ?></p>
                      <p class="text-muted">Hours</p>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="card mb-4">
                  <div class="card-body text-center">
                      <h5 class="card-title text-muted">This month</h5>
                      <p class="display-5 fw-bold"><?php echo number_format($month_hours, 1); ?></p>
                      <p class="text-muted">Hours</p>
                  </div>
              </div>
          </div>
      </div>
      
      <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Recent Traballa</h5>
              <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/work-hours' : 'index.php?page=work-hours'; ?>" class="btn btn-sm btn-outline-primary">View all</a>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>Date</th>
                              <th>Project</th>
                              <th>Clock in</th>
                              <th>Clock out</th>
                              <th>Hours</th>
                              <th>Status</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php if (empty($recent_work_hours)): ?>
                              <tr>
                                  <td colspan="6" class="text-center">No Traballa recorded yet.</td>
                              </tr>
                          <?php else: ?>
                              <?php foreach ($recent_work_hours as $work): ?>
                                  <tr>
                                      <td><?php echo date('M d, Y', strtotime($work['clock_in'])); ?></td>
                                      <td><?php echo sanitize_output($work['project_name']); ?></td>
                                      <td><?php echo date('h:i A', strtotime($work['clock_in'])); ?></td>
                                      <td>
                                          <?php if ($work['status'] === 'completed'): ?>
                                              <?php echo date('h:i A', strtotime($work['clock_out'])); ?>
                                          <?php else: ?>
                                              <span class="badge bg-warning">In progress</span>
                                          <?php endif; ?>
                                      </td>
                                      <td>
                                          <?php if ($work['status'] === 'completed'): ?>
                                              <?php echo number_format($work['total_hours'], 1); ?>
                                          <?php else: ?>
                                              -
                                          <?php endif; ?>
                                      </td>
                                      <td>
                                          <?php if ($work['status'] === 'completed'): ?>
                                              <span class="badge bg-success">Completed</span>
                                          <?php else: ?>
                                              <span class="badge bg-primary">Working</span>
                                          <?php endif; ?>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  
  <div class="col-md-4" style="margin-top: 60px;">
    
      <div class="card mb-4 d-none">
          <div class="card-header">
              <h5 class="mb-0">Weekly hours</h5>
          </div>
          <div class="card-body">
              <canvas id="weeklyChart" height="250"></canvas>
          </div>
      </div>
      
      <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">My projects</h5>
              <?php if (hasManagementPermissions()): ?>
                  <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/projects' : 'index.php?page=projects'; ?>" class="btn btn-sm btn-outline-primary">Manage</a>
              <?php endif; ?>
          </div>
          <div class="card-body">
              <?php if (empty($user_projects)): ?>
                  <p class="text-muted">You are not assigned to any active projects<?php echo $organization_id ? ' in the selected organization' : ''; ?>.</p>
              <?php else: ?>
                  <div class="list-group">
                      <?php foreach ($user_projects as $project): ?>
                          <a href="<?php echo function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? '/project-details/' . (int)$project['id'] : 'index.php?page=project-details&id=' . (int)$project['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                              <?php echo sanitize_output($project['name']); ?>
                              <?php if (isset($project['is_manager']) && $project['is_manager']): ?>
                                  <span class="badge bg-primary rounded-pill">Manager</span>
                              <?php endif; ?>
                          </a>
                      <?php endforeach; ?>
                  </div>
              <?php endif; ?>
          </div>
      </div>
    
    <!-- Pomodoro Timer Widget -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-stopwatch me-2"></i>Pomodoro timer</h5>
            <button class="btn btn-sm btn-outline-secondary" id="pomodoro-expand-toggle">
                <i class="fas fa-expand"></i>
            </button>
        </div>
        <div class="card-body">
            <div id="pomodoro-container"></div>
        </div>
    </div>
    <!-- End Pomodoro Timer Widget -->

          <!-- Calendar Widget -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming events</h5>
            <a href="index.php?page=calendar" class="btn btn-sm btn-primary">View calendar</a>
        </div>
        <div class="card-body">
            <?php if (empty($upcomingEvents)): ?>
                <p class="text-muted">No upcoming events</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                <small><?php echo date('M d', strtotime($event['start_date'])); ?></small>
                            </div>
                            <?php if (!empty($event['description'])): ?>
                                <p class="mb-1 small"><?php echo htmlspecialchars($event['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- End Calendar Widget -->
  </div>
</div>

<!-- Edit Hours Modal -->
<div class="modal fade" id="editHoursModal" tabindex="-1" aria-labelledby="editHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHoursModalLabel">Edit traballa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_work_hours">
                    <input type="hidden" name="work_id" id="edit_work_id">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="edit_clock_in" class="form-label">Clock in</label>
                        <input type="datetime-local" class="form-control" id="edit_clock_in" name="clock_in" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_clock_out" class="form-label">Clock out</label>
                        <input type="datetime-local" class="form-control" id="edit_clock_out" name="clock_out" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Break Modal -->
<div class="modal fade" id="breakModal" tabindex="-1" aria-labelledby="breakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="breakModalLabel">Take a break</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="start_break">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="break_type" class="form-label">Break Type</label>
                        <select class="form-select" id="break_type" name="break_type" required>
                            <option value="lunch">Lunch break</option>
                            <option value="break">Short break</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="break_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="break_notes" name="break_notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start break</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Timer for currently working
if (document.getElementById('working-time')) {
  const startTime = parseInt(document.getElementById('working-time').getAttribute('data-start'));
  
  function updateTimer() {
      const now = Math.floor(Date.now() / 1000);
      const diff = now - startTime;
      
      const hours = Math.floor(diff / 3600);
      const minutes = Math.floor((diff % 3600) / 60);
      const seconds = diff % 60;
      
      document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
      document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
      document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
  }
  
  // Update timer immediately and then every second
  updateTimer();
  setInterval(updateTimer, 1000);
}

// Weekly chart with actual data
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('weeklyChart').getContext('2d');
  
  // Get data from PHP
  const weeklyData = [
      <?php
      $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      $day_values = [];
      
      foreach ($weekly_hours as $date => $hours) {
          $day_values[] = $hours;
      }
      
      echo implode(', ', $day_values);
      ?>
  ];
  
  const weeklyChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [{
              label: 'Hours Worked',
              data: weeklyData,
              backgroundColor: 'rgba(13, 110, 253, 0.5)',
              borderColor: 'rgba(13, 110, 253, 1)',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: 'Hours'
                  }
              }
          }
      }
  });
});

// Initialize Pomodoro Timer
document.addEventListener('DOMContentLoaded', function() {
  // Create and initialize the Pomodoro Timer
  const pomodoroTimer = new PomodoroTimer({
    // Callbacks
    onStateChange: function(mode, isRunning) {
      console.log('Pomodoro state changed:', mode, isRunning);
    },
    onComplete: function(mode) {
      console.log('Pomodoro cycle completed, new mode:', mode);
    }
  });
  
  // Attach it to the container
  pomodoroTimer.attachTo('pomodoro-container');
  
  // Set up expand/collapse functionality
  document.getElementById('pomodoro-expand-toggle').addEventListener('click', function() {
    const pomodoroContainer = document.querySelector('.pomodoro-container');
    const isExpanded = pomodoroContainer.classList.contains('pomodoro-expanded');
    
    if (isExpanded) {
      // Collapse
      pomodoroContainer.classList.remove('pomodoro-expanded');
      this.innerHTML = '<i class="fas fa-expand"></i>';
      // Remove backdrop
      const backdrop = document.querySelector('.pomodoro-backdrop');
      if (backdrop) {
        backdrop.remove();
      }
    } else {
      // Expand
      pomodoroContainer.classList.add('pomodoro-expanded');
      this.innerHTML = '<i class="fas fa-compress"></i>';
      // Add backdrop
      const backdrop = document.createElement('div');
      backdrop.className = 'pomodoro-backdrop';
      backdrop.addEventListener('click', function() {
        pomodoroContainer.classList.remove('pomodoro-expanded');
        document.getElementById('pomodoro-expand-toggle').innerHTML = '<i class="fas fa-expand"></i>';
        this.remove();
      });
      document.body.appendChild(backdrop);
    }
  });
});
</script>

