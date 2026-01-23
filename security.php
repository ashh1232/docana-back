<?php

/**
 * Security functions for database and API protection
 */

// ============================================
// 1. Input Sanitization & Validation
// ============================================

/**
 * Sanitize user input to prevent XSS attacks
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate input length
 */
function validateLength($input, $minLength = 1, $maxLength = 255) {
    $length = strlen($input);
    return $length >= $minLength && $length <= $maxLength;
}

/**
 * Validate phone number format
 */
function validatePhone($phone) {
    return preg_match('/^[0-9\+\-\s\(\)]{7,20}$/', $phone);
}

/**
 * Validate numeric input
 */
function validateNumeric($value, $min = null, $max = null) {
    if (!is_numeric($value)) {
        return false;
    }
    if ($min !== null && $value < $min) {
        return false;
    }
    if ($max !== null && $value > $max) {
        return false;
    }
    return true;
}

// ============================================
// 2. Rate Limiting (Brute Force Protection)
// ============================================

/**
 * Check and enforce rate limiting
 */
function checkRateLimit($identifier, $maxAttempts = 5, $windowSeconds = 300) {
    $cacheFile = sys_get_temp_dir() . '/ratelimit_' . md5($identifier) . '.json';
    
    $attempts = [];
    if (file_exists($cacheFile)) {
        $attempts = json_decode(file_get_contents($cacheFile), true) ?? [];
    }
    
    $now = time();
    $attempts = array_filter($attempts, function($timestamp) use ($now, $windowSeconds) {
        return $timestamp > ($now - $windowSeconds);
    });
    
    if (count($attempts) >= $maxAttempts) {
        http_response_code(429); // Too Many Requests
        echo json_encode([
            "status" => "error",
            "message" => "Too many attempts. Please try again later."
        ]);
        exit;
    }
    
    $attempts[] = $now;
    file_put_contents($cacheFile, json_encode($attempts));
}

/**
 * Get client IP address
 */
function getClientIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ?: 'INVALID';
}

// ============================================
// 3. Security Logging
// ============================================

/**
 * Log security events
 */
function logSecurityEvent($eventType, $details = []) {
    $logFile = __DIR__ . '/logs/security.log';
    
    // Create logs directory if it doesn't exist
    if (!is_dir(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event_type' => $eventType,
        'ip_address' => getClientIP(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN',
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
        'request_path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? 'UNKNOWN',
        'details' => $details
    ];
    
    $isDevelopment = getenv('APP_ENV') === 'development';
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND);
    
    if (!$isDevelopment) {
        // Alert administrators on critical events
        if (in_array($eventType, ['SUSPICIOUS_ACTIVITY', 'INJECTION_ATTEMPT', 'BRUTE_FORCE'])) {
            // You can add email notification here
            error_log("SECURITY ALERT: $eventType - " . json_encode($logEntry));
        }
    }
}

/**
 * Log failed login attempt
 */
function logFailedLogin($username) {
    logSecurityEvent('FAILED_LOGIN', [
        'username' => sanitizeInput($username)
    ]);
    checkRateLimit('login_' . getClientIP(), 5, 300); // 5 attempts per 5 minutes
}

/**
 * Log successful login
 */
function logSuccessfulLogin($userId, $username) {
    logSecurityEvent('SUCCESSFUL_LOGIN', [
        'user_id' => $userId,
        'username' => sanitizeInput($username)
    ]);
}

/**
 * Log suspicious activity
 */
function logSuspiciousActivity($activity, $details = []) {
    logSecurityEvent('SUSPICIOUS_ACTIVITY', array_merge([
        'activity' => $activity
    ], $details));
}

// ============================================
// 4. CSRF Protection
// ============================================

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        logSuspiciousActivity('CSRF_ATTEMPT', ['provided_token' => substr($token, 0, 10) . '...']);
        return false;
    }
    return true;
}

// ============================================
// 5. Password Security
// ============================================

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Validate password strength
 */
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    }
    if (!preg_match('/[!@#$%^&*]/', $password)) {
        $errors[] = "Password must contain at least one special character (!@#$%^&*)";
    }
    
    return $errors;
}

// ============================================
// 6. SQL Injection Detection
// ============================================

/**
 * Detect potential SQL injection attempts
 */
function detectSQLInjection($input) {
    $sqlPatterns = [
        '/union/i',
        '/select/i',
        '/insert/i',
        '/update/i',
        '/delete/i',
        '/drop/i',
        '/create/i',
        '/alter/i',
        '/--|#|\/\*|\*\//i', // SQL comments
    ];
    
    foreach ($sqlPatterns as $pattern) {
        if (preg_match($pattern, $input)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Validate and sanitize all GET/POST parameters
 */
function validateRequestData() {
    $dangerous_inputs = [];
    
    $data = array_merge($_GET, $_POST);
    
    foreach ($data as $key => $value) {
        if (is_string($value) && detectSQLInjection($value)) {
            $dangerous_inputs[$key] = substr($value, 0, 50) . '...';
        }
    }
    
    if (!empty($dangerous_inputs)) {
        logSuspiciousActivity('INJECTION_ATTEMPT', $dangerous_inputs);
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid input detected"
        ]);
        exit;
    }
}

// ============================================
// 7. API Key Validation
// ============================================

/**
 * Validate API key
 */
function validateAPIKey($key) {
    $validKeys = explode(',', getenv('API_KEYS') ?: '');
    return in_array(trim($key), $validKeys);
}

/**
 * Get API key from request header
 */
function getAPIKey() {
    $headers = getallheaders();
    return $headers['Authorization'] ?? $headers['X-API-Key'] ?? null;
}

// ============================================
// 8. Session Security
// ============================================

/**
 * Initialize secure session
 */
function initSecureSession() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

/**
 * Regenerate session ID after login
 */
function regenerateSessionID() {
    session_regenerate_id(true);
}

/**
 * Destroy session securely
 */
function destroySecureSession() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

// ============================================
// 9. XSS Protection
// ============================================

/**
 * Escape output to prevent XSS
 */
function escapeOutput($data) {
    if (is_array($data)) {
        return array_map('escapeOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Add security headers
 */
function addSecurityHeaders() {
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("Content-Security-Policy: default-src 'self'");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

// ============================================
// 10. Response Helper
// ============================================

/**
 * Send error response
 */
function sendErrorResponse($message, $code = 400, $details = []) {
    http_response_code($code);
    echo json_encode([
        "status" => "error",
        "message" => $message,
        "details" => $details
    ]);
    exit;
}

/**
 * Send success response
 */
function sendSuccessResponse($data = [], $message = "Success") {
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => $message,
        "data" => $data
    ]);
    exit;
}
