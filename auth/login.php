<?php

include "../connect.php";

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Invalid request method', 405);
}

// Validate input data
if (empty($_POST['username']) || empty($_POST['password'])) {
    sendErrorResponse('Username and password are required', 400);
}

try {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    // Validate input length
    if (!validateLength($username, 3, 50)) {
        logFailedLogin($username);
        sendErrorResponse('Invalid username format', 400);
    }
    
    if (!validateLength($password, 1, 255)) {
        logFailedLogin($username);
        sendErrorResponse('Invalid password', 400);
    }
    
    // Rate limiting - prevent brute force
    checkRateLimit('login_' . getClientIP(), 5, 300);
    
    // Prepare and execute query
    $sql = "SELECT user_id, user_name, user_email, user_phone, user_image, user_password 
            FROM users 
            WHERE user_name = ? OR user_email = ?
            LIMIT 1";
    
    $stmt = $con->prepare($sql);
    $stmt->execute([$username, $username]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password using bcrypt
        if (verifyPassword($password, $user['user_password'])) {
            // Password correct - login successful
            logSuccessfulLogin($user['user_id'], $user['user_name']);
            
            // Generate a secure token (could be JWT or simple token)
            $token = bin2hex(random_bytes(32));
            
            // Store token in database (optional but recommended)
            $tokenSql = "INSERT INTO auth_tokens (user_id, token, created_at, expires_at) 
                         VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))
                         ON DUPLICATE KEY UPDATE token = ?, created_at = NOW(), expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)";
            
            $tokenStmt = $con->prepare($tokenSql);
            $tokenStmt->execute([$user['user_id'], $token, $token]);
            
            // Return successful response with user data
            sendSuccessResponse([
                'token' => $token,
                'user_id' => $user['user_id'],
                'user_name' => $user['user_name'],
                'user_email' => $user['user_email'],
                'user_phone' => $user['user_phone'],
                'user_image' => $user['user_image']
            ], 'Login successful');
        } else {
            // Password incorrect
            logFailedLogin($username);
            sendErrorResponse('Invalid credentials', 401);
        }
    } else {
        // User not found
        logFailedLogin($username);
        sendErrorResponse('Invalid credentials', 401);
    }
    
} catch (PDOException $e) {
    logSecurityEvent('LOGIN_ERROR', ['error' => $e->getMessage()]);
    sendErrorResponse('An error occurred', 500);
} catch (Exception $e) {
    logSecurityEvent('LOGIN_EXCEPTION', ['error' => $e->getMessage()]);
    sendErrorResponse('An error occurred', 500);
}
