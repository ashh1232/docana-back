<?php
// if (empty($_POST['username'])){
//     die('name is required');
// }
// print_r($_POST);

include "./connect.php";

$username = filterRequest("username");
$password = sha1("password");

$stmt = $con->prepare("SELECT * FROM users WHERE users_name = ? OR users_password = ?");
$stmt->execute(array($username, $password));
$count = $stmt->rowCount();
result($count);
