<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../connect.php";

function validatePasswordStrengthhh($password) {
    $errors = [];
    
    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "يجب أن تتضمن كلمة المرور رقماً واحداً على الأقل";
    }

    
    return $errors;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Invalid request method', 405);
}

// Validate required fields
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['phone'])) {
    sendErrorResponse('All fields are required', 400);
}

try {
    $username = sanitizeInput($_POST['username']);
    // $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $phone = sanitizeInput($_POST['phone']);
    
    // Validate username
    if (!validateLength($username, 3, 50)) {
        sendErrorResponse('Username must be 3-50 characters', 400);
    }
    
    // Validate email
    // if (!validateEmail($email)) {
    //     sendErrorResponse('Invalid email address', 400);
    // }
    
    // Validate password strength
    $passwordErrors = validatePasswordStrengthhh($password);
    if (!empty($passwordErrors)) {
        sendErrorResponse('كلمه المرور ضعيفه', 400, $passwordErrors);
    }
    
    // Validate phone
    if (!validatePhone($phone)) {
        sendErrorResponse('Invalid phone number format', 400);
    }
    
    // Check if user already exists
    $checkSql = "SELECT user_id FROM users 
                 WHERE user_phone = ? OR user_name = ?
                 LIMIT 1";
    
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->execute([$phone, $username]);
    
    if ($checkStmt->rowCount() > 0) {
        sendErrorResponse(' phone, or username already exists', 409);
    }
    
    // Hash password using bcrypt
    $hashedPassword = hashPassword($password);
    
    // Insert new user
    $insertSql = "INSERT INTO users 
                  (user_name, user_password, user_phone, created_at) 
                  VALUES (?, ?, ?, NOW())";
    
    $insertStmt = $con->prepare($insertSql);
    $insertStmt->execute([$username, $hashedPassword, $phone]);
    
    $userId = $con->lastInsertId();
    
    // Generate token for immediate login
    $token = bin2hex(random_bytes(32));
    
    // Store token in database
    $tokenSql = "INSERT INTO auth_tokens (user_id, token, created_at, expires_at) 
                 VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))";
    
    $tokenStmt = $con->prepare($tokenSql);
    $tokenStmt->execute([$userId, $token]);
    
    // Log successful registration
    logSecurityEvent('USER_REGISTERED', [
        'username' => $username,
        // 'email' => $email,
        'phone' => $phone
    ]);
    
    // Return success response with token
    sendSuccessResponse([
        'token' => $token,
        'user_id' => $userId,
        'user_name' => $username,
        // 'user_email' => $email,
        'user_phone' => $phone
    ], 'Registration successful');
    
} catch (PDOException $e) {
    // Check for specific database errors
    if (strpos($e->getMessage(), 'Duplicate') !== false) {
        logSecurityEvent('SIGNUP_DUPLICATE', ['phone' => $phone]);
        sendErrorResponse('phone already registered', 409);
    } else {
        logSecurityEvent('SIGNUP_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred during registration', 500);
    }
} catch (Exception $e) {
    logSecurityEvent('SIGNUP_EXCEPTION', ['error' => $e->getMessage()]);
    sendErrorResponse('An error occurred', 500);
}
