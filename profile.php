<?php
// profile.php - User Profile Management API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
include "connect.php"; // Make sure this file exists with your DB connection

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Handle different request types
if ($requestMethod === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'get_profile') {
        getProfile($con);
    } elseif ($action === 'update_profile') {
        updateProfile($con);
    } elseif ($action === 'upload_image') {
        uploadProfileImage($con);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Function to get user profile
function getProfile($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    
    $sql = "SELECT 
        user_id, user_name, user_email, user_phone, user_image,
        user_address, user_city, user_country, created_at
    FROM users 
    WHERE user_id = '$userId'";
    
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
}

// Function to update user profile
function updateProfile($con) {
    try {
        $userId = mysqli_real_escape_string($con, $_POST['user_id']);
        $userName = mysqli_real_escape_string($con, $_POST['user_name']);
        $userPhone = mysqli_real_escape_string($con, $_POST['user_phone']);
        $userAddress = isset($_POST['user_address']) ? mysqli_real_escape_string($con, $_POST['user_address']) : '';
        $userCity = isset($_POST['user_city']) ? mysqli_real_escape_string($con, $_POST['user_city']) : '';
        $userCountry = isset($_POST['user_country']) ? mysqli_real_escape_string($con, $_POST['user_country']) : '';
        
        // Validate required fields
        if (empty($userId) || empty($userName)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }
        
        $sql = "UPDATE users SET 
            user_name = '$userName',
            user_phone = '$userPhone',
            user_address = '$userAddress',
            user_city = '$userCity',
            user_country = '$userCountry'
        WHERE user_id = '$userId'";
        
        if (mysqli_query($con, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update profile: ' . mysqli_error($con)]);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
}

// Function to upload profile image
function uploadProfileImage($con) {
    try {
        $userId = mysqli_real_escape_string($con, $_POST['user_id']);
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded or upload error']);
            return;
        }
        
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        
        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($fileExt, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed']);
            return;
        }
        
        if ($fileSize > 5000000) { // 5MB max
            echo json_encode(['status' => 'error', 'message' => 'File size too large. Max 5MB']);
            return;
        }
        
        // Generate unique filename
        $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExt;
        $uploadPath = '../uploads/profiles/' . $newFileName;
        
        // Create directory if it doesn't exist
        if (!file_exists('../uploads/profiles/')) {
            mkdir('../uploads/profiles/', 0777, true);
        }
        
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Update database
            $imageUrl = 'uploads/profiles/' . $newFileName;
            $sql = "UPDATE users SET user_image = '$imageUrl' WHERE user_id = '$userId'";
            
            if (mysqli_query($con, $sql)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Image uploaded successfully',
                    'image_url' => $imageUrl
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
}
?>
