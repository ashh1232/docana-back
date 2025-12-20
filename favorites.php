<?php
// favorites.php - Favorites/Wishlist Management API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
include "connect.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add_favorite') {
        addFavorite($con);
    } elseif ($action === 'remove_favorite') {
        removeFavorite($con);
    } elseif ($action === 'get_favorites') {
        getFavorites($con);
    } elseif ($action === 'check_favorite') {
        checkFavorite($con);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Add product to favorites
function addFavorite($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    $productId = mysqli_real_escape_string($con, $_POST['product_id']);
    
    $sql = "INSERT INTO favorites (user_id, product_id) VALUES ('$userId', '$productId')";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Added to favorites']);
    } else {
        // Check if already exists
        if (mysqli_errno($con) == 1062) {
            echo json_encode(['status' => 'info', 'message' => 'Already in favorites']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add favorite']);
        }
    }
}

// Remove product from favorites
function removeFavorite($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    $productId = mysqli_real_escape_string($con, $_POST['product_id']);
    
    $sql = "DELETE FROM favorites WHERE user_id = '$userId' AND product_id = '$productId'";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Removed from favorites']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove favorite']);
    }
}

// Get user's favorites with product details
function getFavorites($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    
    $sql = "SELECT p.*, f.created_at as favorited_at
    FROM favorites f
    INNER JOIN product p ON f.product_id = p.product_id
    WHERE f.user_id = '$userId'
    ORDER BY f.created_at DESC";
    
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        $favorites = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $favorites[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $favorites]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch favorites']);
    }
}

// Check if product is in favorites
function checkFavorite($con) {
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    $productId = mysqli_real_escape_string($con, $_POST['product_id']);
    
    $sql = "SELECT * FROM favorites WHERE user_id = '$userId' AND product_id = '$productId'";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 'success', 'is_favorite' => true]);
    } else {
        echo json_encode(['status' => 'success', 'is_favorite' => false]);
    }
}
?>
