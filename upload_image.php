<?php
include "./connect.php"; // This file defines $con as a PDO object

header('Content-Type: application/json');

if (isset($_FILES['file']) && isset($_POST['product_id'])) {
    
    $product_id = $_POST['product_id'];
    
    // CHANGED: Path points one level up to the img folder
    $target_dir = "../../img/"; 

    // OPTIONAL: Auto-create the folder if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Create unique filename
    $filename = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        try {
            // Using PDO syntax
            $sql = "UPDATE products SET product_image = ? WHERE product_id = ?";
            $stmt = $con->prepare($sql);
            
            if ($stmt->execute([$filename, $product_id])) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Product image updated successfully",
                    "filename" => $filename
                ]);
            } else {
                echo json_encode(["status" => "failure", "message" => "Database update failed"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode([
            "status" => "failure", 
            "message" => "File move failed. Check if $target_dir is writable."
        ]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Missing fields: file or product_id"]);
}
?>
