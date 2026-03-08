<?php

include "../connect.php";

/**
 * Verify authentication token from request headers
 * Returns user data if valid, otherwise sends error response
 */
function verifyAuthToken() {
    global $con;
    
    // Get Authorization header
    $headers = getallheaders();
    $token = null;
    
    // Check for Authorization: Bearer <token>
    if (!empty($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }
    }
    
    // Fallback to X-Auth-Token header
    if (!$token && !empty($headers['X-Auth-Token'])) {
        $token = $headers['X-Auth-Token'];
    }
    
    // Fallback to POST data (for development/testing only)
    if (!$token && !empty($_POST['token'])) {
        $token = sanitizeInput($_POST['token']);
    }
    
    if (empty($token)) {
        sendErrorResponse('Authentication token is required', 401);
    }
    
    try {
        // Verify token exists and is not expired
        $sql = "SELECT at.token_id, at.user_id, at.expires_at, 
                       u.user_id, u.user_name, u.user_email, u.user_phone, u.user_image
                FROM auth_tokens at
                JOIN users u ON at.user_id = u.user_id
                WHERE at.token = ? 
                AND (at.expires_at IS NULL OR at.expires_at > NOW())
                LIMIT 1";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() === 0) {
            logSecurityEvent('INVALID_TOKEN', ['token' => substr($token, 0, 10) . '...']);
            sendErrorResponse('Invalid or expired token', 401);
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Update last_used_at
        $updateSql = "UPDATE auth_tokens SET last_used_at = NOW() WHERE token_id = ?";
        $updateStmt = $con->prepare($updateSql);
        $updateStmt->execute([$result['token_id']]);
        
        return [
            'token_id' => $result['token_id'],
            'user_id' => $result['user_id'],
            'user_name' => $result['user_name'],
            'user_email' => $result['user_email'],
            'user_phone' => $result['user_phone'],
            'user_image' => $result['user_image']
        ];
        
    } catch (PDOException $e) {
        logSecurityEvent('TOKEN_VERIFY_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}

/**
 * Logout - invalidate token
 */
function logout() {
    global $con;
    
    try {
        $headers = getallheaders();
        $token = null;
        
        if (!empty($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }
        
        if ($token) {
            $sql = "DELETE FROM auth_tokens WHERE token = ?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$token]);
            
            logSecurityEvent('LOGOUT', []);
            sendSuccessResponse([], 'Logged out successfully');
        } else {
            sendErrorResponse('No token provided', 400);
        }
        
    } catch (PDOException $e) {
        logSecurityEvent('LOGOUT_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}

/**
 * Refresh token
 */
function refreshToken() {
    global $con;
    
    try {
        // Verify current token
        $user = verifyAuthToken();
        
        // Generate new token
        $newToken = bin2hex(random_bytes(32));
        
        // Update token in database
        $sql = "UPDATE auth_tokens 
                SET token = ?, 
                    expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY),
                    last_used_at = NOW()
                WHERE user_id = ?
                ORDER BY created_at DESC
                LIMIT 1";
        
        $stmt = $con->prepare($sql);
        $stmt->execute([$newToken, $user['user_id']]);
        
        logSecurityEvent('TOKEN_REFRESHED', ['user_id' => $user['user_id']]);
        
        sendSuccessResponse([
            'token' => $newToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
        ], 'Token refreshed successfully');
        
    } catch (PDOException $e) {
        logSecurityEvent('TOKEN_REFRESH_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}
