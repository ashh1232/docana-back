<?php
header('Content-Type: application/json; charset=UTF-8');
include '../shemo_connect.php';
// getAllData('products');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    // 1. Validate Page Input
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if ($page === null || $page === false || $page < 1) {
        $page = 1;
    }

    $limit = 10;
    $offset = ($page - 1) * $limit;

    // 2. Get Total Count (for pagination metadata)
    $totalStmt = $con->query("SELECT COUNT(*) FROM products");
    $totalRecords = $totalStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // 3. Prepare the Query ONCE
    // It is better to use ORDER BY to keep the list stable during scrolling
    $query = "SELECT * FROM products ORDER BY product_id DESC LIMIT :limit OFFSET :offset";
    $stmt = $con->prepare($query);

    // 4. Bind values as Integers (Crucial for LIMIT/OFFSET)
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    // 5. Execute WITHOUT an array inside execute()
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 6. Return Structured JSON
    echo json_encode([
        "status" => "success",
        "data" => $products,
        "metadata" => [
            "current_page" => $page,
            "per_page" => $limit,
            "total_records" => (int)$totalRecords,
            "total_pages" => (int)$totalPages,
            "has_more" => $page < $totalPages
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(), // This will show the actual SQL/PHP error
        "line" => $e->getLine(),       // The line number where it failed
        "file" => $e->getFile()
    ]);
}
