<?php
/**
 * Traballa - Calendar
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

class Calendar {
    private $db;
    private $max_visible_events = 3; // Maximum number of events to show before "View More"
    private $events_table = 'calendar_events';
    private $event_limit = 1000; // Limit the maximum number of events to be returned
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function generateCalendarHTML($month = null, $year = null, $user_id = null, $organization_id = null) {
        if ($month === null) $month = date('n');
        if ($year === null) $year = date('Y');
        
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = date('t', $firstDay);
        $dayOfWeek = date('w', $firstDay);
        $monthName = date('F', $firstDay);
        $today = date('Y-m-d');
        
        
        $html = '<div class="calendar">';
        $html .= '<div class="calendar-header">';
        $html .= '<h2>' . $monthName . ' ' . $year . '</h2>';
        $html .= '</div>';
        
        // Days of week header
        $html .= '<div class="calendar-weekdays">';
        $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($weekDays as $day) {
            $html .= "<div class='weekday'>$day</div>";
        }
        $html .= '</div>';
        
        // Calendar days
        $html .= '<div class="calendar-days">';
        
        // Empty cells before start of month
        for ($i = 0; $i < $dayOfWeek; $i++) {
            $html .= '<div class="calendar-day empty"></div>';
        }
        
        // Days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isToday = ($date === $today) ? ' today' : '';
            
            // Get events for just this specific date instead of the whole month
            $dayEvents = $this->getEventsForDate($date, $user_id, $organization_id);
            $hasEvents = !empty($dayEvents) ? ' has-events' : '';
            
            $html .= "<div class='calendar-day{$isToday}{$hasEvents}' data-date='$date'>";
            $html .= "<span class='day-number'>$day</span>";
            
            if (!empty($dayEvents)) {
                $html .= '<div class="event-list">';
                
                // Show only a limited number of events
                $visibleEvents = array_slice($dayEvents, 0, $this->max_visible_events);
                foreach ($visibleEvents as $event) {
                    $eventTypeClass = 'event-' . $event['event_type'];
                    $html .= "<div class='event-item {$eventTypeClass}' data-event-id='{$event['id']}' title='" . htmlspecialchars($event['title']) . "'>";
                    $html .= "<span class='event-title'>" . htmlspecialchars($event['title']) . "</span>";
                    $html .= "</div>";
                }
                
                // Add "View More" if there are more events
                if (count($dayEvents) > $this->max_visible_events) {
                    $remainingCount = count($dayEvents) - $this->max_visible_events;
                    $html .= '<div class="event-more" data-date="' . $date . '">';
                    $html .= '<span class="event-more-text">+ ' . $remainingCount . ' more</span>';
                    $html .= '</div>';
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
            
            // Free memory by clearing the day events after use
            unset($dayEvents);
        }
        
        $html .= '</div></div>';
        return $html;
    }
    
    public function addEvent($title, $description, $start_date, $end_date = null, $event_type = 'personal', $user_id = null, $project_id = null, $organization_id = null) {
        // Convert empty values to NULL for integer columns
        $user_id = ($user_id === '' || $user_id === null) ? null : $user_id;
        $project_id = ($project_id === '' || $project_id === null) ? null : $project_id;
        $organization_id = ($organization_id === '' || $organization_id === null) ? null : $organization_id;
        $end_date = ($end_date === '') ? null : $end_date;
        $stmt = $this->db->prepare("INSERT INTO {$this->events_table} 
            (title, description, start_date, end_date, event_type, user_id, project_id, organization_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $start_date, $end_date, $event_type, $user_id, $project_id, $organization_id]);
    }
    
    public function updateEvent($event_id, $title, $description, $start_date, $end_date = null, $event_type = null, $user_id = null, $project_id = null, $organization_id = null) {
        $updates = [];
        $params = [];
        // Convert empty values to NULL for integer columns
        $user_id = ($user_id === '') ? null : $user_id;
        $project_id = ($project_id === '') ? null : $project_id;
        $organization_id = ($organization_id === '') ? null : $organization_id;
        $end_date = ($end_date === '') ? null : $end_date;
        
        if ($title !== null) {
            $updates[] = "title = ?";
            $params[] = $title;
        }
        
        if ($description !== null) {
            $updates[] = "description = ?";
            $params[] = $description;
        }
        
        if ($start_date !== null) {
            $updates[] = "start_date = ?";
            $params[] = $start_date;
        }
        
        if ($end_date !== null) {
            $updates[] = "end_date = ?";
            $params[] = $end_date;
        }
        
        if ($event_type !== null) {
            $updates[] = "event_type = ?";
            $params[] = $event_type;
        }
        
        if ($user_id !== null) {
            $updates[] = "user_id = ?";
            $params[] = $user_id;
        }
        
        if ($project_id !== null) {
            $updates[] = "project_id = ?";
            $params[] = $project_id;
        }
        
        if ($organization_id !== null) {
            $updates[] = "organization_id = ?";
            $params[] = $organization_id;
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $params[] = $event_id;
        $sql = "UPDATE {$this->events_table} SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function getEventsForDate($date, $user_id = null, $organization_id = null) {
        $sql = "SELECT e.id, e.title, e.event_type 
            FROM {$this->events_table} e
            LEFT JOIN projects p ON e.project_id = p.id
            LEFT JOIN organizations o ON e.organization_id = o.id
            WHERE DATE(e.start_date) <= ? 
            AND (e.end_date IS NULL OR DATE(e.end_date) >= ?)";
        
        $params = [$date, $date];
        
        // Filter by user_id for personal events
        if ($user_id !== null) {
            $sql .= " AND (e.event_type != 'personal' OR e.user_id = ?)";
            $params[] = $user_id;
        }
        
        // Filter by organization_id for organization events
        if ($organization_id !== null) {
            $sql .= " AND (e.event_type != 'organization' OR e.organization_id = ?)";
            $params[] = $organization_id;
        }
        
        $sql .= " ORDER BY e.start_date ASC LIMIT " . $this->event_limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEventsForDateRange($start_date, $end_date, $user_id = null, $organization_id = null) {
        $sql = "SELECT e.id, e.title, e.event_type, e.start_date, e.end_date
            FROM {$this->events_table} e
            LEFT JOIN projects p ON e.project_id = p.id
            LEFT JOIN organizations o ON e.organization_id = o.id
            WHERE (DATE(e.start_date) BETWEEN ? AND ?
            OR (e.end_date IS NOT NULL AND DATE(e.end_date) BETWEEN ? AND ?))";
        
        $params = [$start_date, $end_date, $start_date, $end_date];
        
        // Filter by user_id for personal events
        if ($user_id !== null) {
            $sql .= " AND (e.event_type != 'personal' OR e.user_id = ?)";
            $params[] = $user_id;
        }
        
        // Filter by organization_id for organization events
        if ($organization_id !== null) {
            $sql .= " AND (e.event_type != 'organization' OR e.organization_id = ?)";
            $params[] = $organization_id;
        }
        
        $sql .= " ORDER BY e.start_date ASC LIMIT " . $this->event_limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEventById($event_id) {
        $stmt = $this->db->prepare("SELECT e.*, p.name as project_name, o.name as organization_name 
            FROM {$this->events_table} e
            LEFT JOIN projects p ON e.project_id = p.id
            LEFT JOIN organizations o ON e.organization_id = o.id
            WHERE e.id = ?");
        $stmt->execute([$event_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteEvent($event_id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->events_table} WHERE id = ?");
        return $stmt->execute([$event_id]);
    }
    
    public function getProjects() {
        // Get the user ID from the session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        if (!$user_id) {
            return [];
        }
        
        // Get all projects the user has access to
        $sql = "SELECT DISTINCT p.id, p.name 
                FROM projects p
                JOIN project_members pm ON p.id = pm.project_id
                WHERE pm.user_id = ? AND p.status = 'active'
                ORDER BY p.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrganizations() {
        // Get the user ID from the session
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        if (!$user_id) {
            return [];
        }
        
        // Get all organizations the user has access to
        $sql = "SELECT DISTINCT o.id, o.name 
                FROM organizations o
                JOIN organization_members om ON o.id = om.organization_id
                WHERE om.user_id = ?
                ORDER BY o.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}