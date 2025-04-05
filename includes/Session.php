<?php
/**
 * Traballa - Session Handler
 * 
 * @copyright 2025 Marcos Núñez Fernández
 * @license   MIT License
 * @link      https://github.com/markostech/workhours-tfc
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

class Session {
    private $db;
    private $session_table = 'user_sessions';
    private $session_lifetime = 86400;
    private $refresh_threshold = 1800;
    private $cookie_name = 'session';
    private $session_id;
    private $session_data = [];
    private $is_valid_session = false;

    /**
     * Get the current session ID
     * 
     * @return string The current session ID
     */
    public function getSessionId() {
        return $this->session_id;
    }

    public function __construct($db) {
        $this->db = $db;
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->startSession();
    }

    private function startSession() {
        // Check if session cookie exists
        if (isset($_COOKIE[$this->cookie_name])) {
            $this->session_id = $_COOKIE[$this->cookie_name];
            $this->loadSessionFromDB();
        } else {
            // Create new session
            $this->session_id = $this->generateSessionId();
            $this->session_data['last_activity'] = time();
            $this->updateSessionInDB();
        }
        
        // Set/refresh the cookie
        $secure = isset($_SERVER['HTTPS']);
        setcookie(
            $this->cookie_name,
            $this->session_id, 
            [
                'expires' => time() + $this->session_lifetime,
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
        
        // Check if session needs refreshing
        if ($this->shouldRefreshSession()) {
            $this->refreshSession();
        }
        
        $this->cleanExpiredSessions();
        
        // Sync with PHP $_SESSION
        $this->syncWithPHPSession();
    }

    private function generateSessionId() {
        return bin2hex(random_bytes(32)); // 64 character string
    }

    private function loadSessionFromDB() {
        $stmt = $this->db->prepare("SELECT session_data FROM {$this->session_table} WHERE session_id = ? AND expiry > ?");
        $stmt->execute([$this->session_id, time()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->session_data = unserialize($result['session_data']);
            $this->is_valid_session = true;
        } else {
            // Invalid or expired session, create new one
            $this->session_id = $this->generateSessionId();
            $this->session_data = ['last_activity' => time()];
            $this->is_valid_session = false;
            $this->updateSessionInDB();
        }
    }

    public function set($key, $value) {
        $this->session_data[$key] = $value;
        $_SESSION[$key] = $value; // Also update PHP session
        $this->updateSessionInDB();
    }

    public function get($key) {
        return isset($this->session_data[$key]) ? $this->session_data[$key] : null;
    }

    public function destroy() {
        $stmt = $this->db->prepare("DELETE FROM {$this->session_table} WHERE session_id = ?");
        $stmt->execute([$this->session_id]);
        
        // Delete the cookie by setting expiration in the past
        setcookie($this->cookie_name, '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        
        // Clear session data
        $this->session_data = [];
        $this->is_valid_session = false;
        
        // Clear PHP session
        session_unset();
        session_destroy();
    }
    
    /**
     * Check if the session is valid
     * @return bool True if session is valid, false otherwise
     */
    public function isValid() {
        return $this->is_valid_session;
    }
    
    /**
     * Reload PHP session when it's missing
     * @param int $user_id Optional user ID to find existing sessions
     * @return bool True if an existing session was found and loaded
     */
    public function reload($user_id = null) {
        // First check if PHP session exists but our custom session doesn't
        if (!empty($_SESSION) && empty($this->session_data)) {
            // Copy PHP session data to our session
            foreach ($_SESSION as $key => $value) {
                $this->session_data[$key] = $value;
            }
            $this->session_data['reloaded_from_php'] = true;
            $this->updateSessionInDB();
            $this->is_valid_session = true;
            return true;
        }
        
        // If we have a user_id, try to find their last valid session
        if ($user_id !== null) {
            $stmt = $this->db->prepare("SELECT session_id, session_data FROM {$this->session_table} 
                                      WHERE session_data LIKE ? AND expiry > ? 
                                      ORDER BY expiry DESC LIMIT 1");
            // Look for serialized user_id in the session data
            $search_pattern = '%"user_id";i:' . $user_id . ';%';
            $stmt->execute([$search_pattern, time()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Found a valid session for this user
                $this->session_id = $result['session_id'];
                $this->session_data = unserialize($result['session_data']);
                $this->is_valid_session = true;
                
                // Sync with PHP session
                $this->syncWithPHPSession();
                
                // Update the cookie with the found session
                $secure = isset($_SERVER['HTTPS']);
                setcookie(
                    $this->cookie_name,
                    $this->session_id, 
                    [
                        'expires' => time() + $this->session_lifetime,
                        'path' => '/',
                        'domain' => '',
                        'secure' => $secure,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]
                );
                
                return true;
            }
        }
        
        // If no user_id provided or no valid session found,
        // create a new session
        $this->session_id = $this->generateSessionId();
        $this->session_data = ['last_activity' => time()];
        if ($user_id !== null) {
            $this->session_data['user_id'] = $user_id;
        }
        $this->updateSessionInDB();
        $this->syncWithPHPSession();
        
        // Set the cookie with the new session
        $secure = isset($_SERVER['HTTPS']);
        setcookie(
            $this->cookie_name,
            $this->session_id, 
            [
                'expires' => time() + $this->session_lifetime,
                'path' => '/',
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
        
        return false;
    }
    
    /**
     * Sync the custom session data with PHP's $_SESSION
     */
    private function syncWithPHPSession() {
        // First, update our session with any new PHP session data
        foreach ($_SESSION as $key => $value) {
            if (!isset($this->session_data[$key]) || $this->session_data[$key] !== $value) {
                $this->session_data[$key] = $value;
            }
        }
        
        // Then update PHP session with our session data
        foreach ($this->session_data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
    
    private function shouldRefreshSession() {
        // If last_activity is set and is older than refresh_threshold, refresh the session
        if (isset($this->session_data['last_activity']) && 
            (time() - $this->session_data['last_activity'] > $this->refresh_threshold)) {
            return true;
        }
        return false;
    }
    
    private function refreshSession() {
        $this->session_data['last_activity'] = time();
        $this->updateSessionInDB();
    }

    private function updateSessionInDB() {
        $data = serialize($this->session_data);
        $expiry = time() + $this->session_lifetime;
        
        // Always set last_activity when updating session
        if (!isset($this->session_data['last_activity'])) {
            $this->session_data['last_activity'] = time();
        }

        $stmt = $this->db->prepare("INSERT INTO {$this->session_table} 
            (session_id, session_data, expiry) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            session_data = ?, expiry = ?");
        
        $stmt->execute([$this->session_id, $data, $expiry, $data, $expiry]);
    }

    private function cleanExpiredSessions() {
        $stmt = $this->db->prepare("DELETE FROM {$this->session_table} WHERE expiry < ?");
        $stmt->execute([time()]);
    }
}