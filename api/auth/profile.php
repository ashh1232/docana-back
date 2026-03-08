<?php
// profile.php - User Profile Management API
header('Content-Type: application/json');

// Database configuration
include "../connect.php"; // PDO connection from connect.php

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Handle different request types
if ($requestMethod === 'POST') {
    $action = isset($_POST['action']) ? sanitizeInput($_POST['action']) : '';

    if ($action === 'get_profile') {
        getProfile($con);
    } elseif ($action === 'update_profile') {
        updateProfile($con);
    } elseif ($action === 'upload_image') {
        uploadProfileImage($con);
    } else {
        sendErrorResponse('Invalid action', 400);
    }
} else {
    sendErrorResponse('Invalid request method', 405);
}

// Function to get user profile using PDO
function getProfile($con)
{
    try {
        // Validate user_id is provided
        if (empty($_POST['user_id'])) {
            sendErrorResponse('User ID is required', 400);
        }

        $userId = sanitizeInput($_POST['user_id']);

        // Validate that user_id is numeric
        if (!validateNumeric($userId)) {
            sendErrorResponse('Invalid user ID format', 400);
        }

        // Use prepared statement with PDO
        $sql = "SELECT 
            user_id, user_name, user_email, user_phone, user_image,
            user_address, created_at
        FROM users 
        WHERE user_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->execute([$userId]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            sendSuccessResponse($user, 'Profile retrieved successfully');
        } else {
            sendErrorResponse('User not found', 404);
        }
    } catch (PDOException $e) {
        logSecurityEvent('PROFILE_GET_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}

// Function to update user profile
function updateProfile($con)
{
    try {
        // Validate required fields
        if (empty($_POST['user_id']) || empty($_POST['user_name'])) {
            sendErrorResponse('User ID and name are required', 400);
        }

        $userId = sanitizeInput($_POST['user_id']);
        $userName = sanitizeInput($_POST['user_name']);
        $userPhone = sanitizeInput($_POST['user_phone'] ?? '');
        $userAddress = sanitizeInput($_POST['user_address'] ?? '');
        $userCity = sanitizeInput($_POST['user_city'] ?? '');
        $userCountry = sanitizeInput($_POST['user_country'] ?? '');

        // Validate data
        if (!validateNumeric($userId)) {
            sendErrorResponse('Invalid user ID format', 400);
        }

        if (!validateLength($userName, 2, 100)) {
            sendErrorResponse('User name must be 2-100 characters', 400);
        }

        if (!empty($userPhone) && !validatePhone($userPhone)) {
            sendErrorResponse('Invalid phone number format', 400);
        }

        // Use prepared statement with PDO
        $sql = "UPDATE users SET 
            user_name = ?,
            user_phone = ?,
            user_address = ?,
            -- user_city = ?,
            -- user_country = ?,
            updated_at = NOW()
        WHERE user_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->execute([
            $userName,
            $userPhone,
            $userAddress,
            //  $userCity, $userCountry,
            $userId
        ]);

        if ($stmt->rowCount() > 0) {
            logSecurityEvent('PROFILE_UPDATED', ['user_id' => $userId]);
            sendSuccessResponse([
                'user_id' => $userId,
                'user_name' => $userName,
                'user_phone' => $userPhone,
                'user_address' => $userAddress,
                // 'user_city' => $userCity,
                // 'user_country' => $userCountry
            ], 'Profile updated successfully');
        } else {
            sendErrorResponse('User not found or no changes made', 404);
        }
    } catch (PDOException $e) {
        logSecurityEvent('PROFILE_UPDATE_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}

// Function to upload profile image
function uploadProfileImage($con)
{
    try {
        // Validate user_id
        if (empty($_POST['user_id'])) {
            sendErrorResponse('User ID is required', 400);
        }

        $userId = sanitizeInput($_POST['user_id']);

        if (!validateNumeric($userId)) {
            sendErrorResponse('Invalid user ID format', 400);
        }

        // Validate file upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = 'No image uploaded';
            if (isset($_FILES['image']['error'])) {
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
                    UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
                    UPLOAD_ERR_PARTIAL => 'File uploaded partially',
                    UPLOAD_ERR_NO_FILE => 'No file uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'No temp directory',
                    UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload blocked by extension'
                ];
                $errorMessage = $uploadErrors[$_FILES['image']['error']] ?? 'Unknown upload error';
            }
            sendErrorResponse($errorMessage, 400);
        }

        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];

        // Validate file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowed)) {
            sendErrorResponse('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed', 400);
        }

        // Validate file size (5MB max)
        if ($fileSize > 5000000) {
            sendErrorResponse('File size too large. Maximum 5MB allowed', 400);
        }

        // Validate MIME type
        $mimeType = mime_content_type($fileTmpName);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedMimes)) {
            sendErrorResponse('Invalid file type', 400);
        }

        // Generate unique filename
        $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExt;
        $uploadDir = __DIR__ . '/../uploads/profiles/';
        $uploadPath = $uploadDir . $newFileName;

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file
        if (!move_uploaded_file($fileTmpName, $uploadPath)) {
            logSecurityEvent('IMAGE_UPLOAD_ERROR', ['user_id' => $userId, 'file' => $fileName]);
            sendErrorResponse('Failed to upload file', 500);
        }

        // Update database using prepared statement
        $imageUrl = 'uploads/profiles/' . $newFileName;
        $sql = "UPDATE users SET user_image = ?, updated_at = NOW() WHERE user_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->execute([$imageUrl, $userId]);

        if ($stmt->rowCount() > 0) {
            logSecurityEvent('IMAGE_UPLOADED', ['user_id' => $userId, 'file' => $newFileName]);
            sendSuccessResponse([
                'image_url' => $imageUrl,
                'file_name' => $newFileName
            ], 'Image uploaded successfully');
        } else {
            // Delete uploaded file if database update fails
            @unlink($uploadPath);
            sendErrorResponse('Failed to update user profile', 500);
        }
    } catch (PDOException $e) {
        logSecurityEvent('IMAGE_UPLOAD_DB_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    } catch (Exception $e) {
        logSecurityEvent('IMAGE_UPLOAD_EXCEPTION', ['error' => $e->getMessage()]);
        sendErrorResponse('An error occurred', 500);
    }
}
