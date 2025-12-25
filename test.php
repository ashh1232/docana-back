<?php
include "./connect.php";

session_unset();
$username = htmlspecialchars(strip_tags($_POST['username']));
$email    = htmlspecialchars(strip_tags($_POST['email']));
$password = htmlspecialchars(strip_tags($_POST['password']));
$phone = htmlspecialchars(strip_tags($_POST['phone']));

$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? OR users_phone = ?");
$stmt->execute(array($email, $phone));
$count = $stmt->rowCount();
if ($count > 0) {
    printFailure("PHONE OR EMAIL");
} else {
    $data = array(
        "users_name" => $username,
        "users_email" => $email,
        "users_password" => $password,
        "users_phone" => $phone,
        "users_verify" => '123',


    );
    // sendEmail($email , 'verify code',"verify $verify");
    insertData("users", $data);
    // $url = "../admin/sign-up.php?error".$error;
    // header('location:'.$url);

}
///////////////////////////
<?php
// 1. Set response header to JSON (standard for modern APIs)
header('Content-Type: application/json; charset=UTF-8');

// 2. Database connection
include "./connect.php";

try {
    // 3. Secure Input Validation
    // Use filter_input to sanitize and ensure 'page' is a positive integer
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if ($page === null || $page === false || $page < 1) {
        $page = 1;
    }

    $limit = 10;
    $offset = ($page - 1) * $limit;

    // 4. (Optional but Recommended) Get total record count for metadata
    // This allows the frontend to know how many pages exist total
    $totalStmt = $con->query("SELECT COUNT(*) FROM productview");
    $totalRecords = $totalStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // 5. Secure Prepared Statement with explicit ORDER BY
    // Explicit ordering ensures consistent results across different page requests
    $query = "SELECT * FROM productview ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt = $con->prepare($query);

    // 6. Bind parameters as Integers
    // PDO sometimes treats array values as strings; explicit binding as INT is safer for LIMIT/OFFSET
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7. Structured JSON Output
    // Returning 'metadata' alongside 'data' is the 2025 industry standard
    echo json_encode([
        "status" => "success",
        "data" => $products,
        "metadata" => [
            "current_page" => $page,
            "per_page" => $limit,
            "total_records" => (int)$totalRecords,
            "total_pages" => $totalPages,
            "has_more" => $page < $totalPages
        ]
    ]);

} catch (Exception $e) {
    // 8. Basic Error Handling
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "An internal server error occurred."
    ]);
}

