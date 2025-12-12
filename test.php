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
    // $error[] = 'هنالك خطأ';
    // header('location:../admin/sign-up.php');
    printFailure('asd');
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
