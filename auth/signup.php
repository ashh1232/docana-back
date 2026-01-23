<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../connect.php";
$username = filterRequest("username");
$email = filterRequest("email");
$password = sha1($_POST["password"]);
$phone = filterRequest("phone");
// $verify = rand(10000, 99999);

$stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? OR user_phone = ?");
$stmt->execute(array($email, $phone));
$count = $stmt->rowCount();
if ($count > 0) {
    printFailure("PHONE OR EMAIL");
} else {
    $data = array(
        "user_name" => $username,
        "user_email" => $email,
        "user_password" => $password,
        "user_phone" => $phone,
        // "user_verify" => $verify,
    );
    // sendEmail($email , 'verify code',"verify $verify");
    insertData("users", $data);
}
