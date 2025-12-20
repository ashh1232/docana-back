<?php

include "./connect.php";

$password = sha1($_POST["password"]);
$username = filterRequest("username");


getData("users", "users_name = ? AND users_password = ?", array($username, $password));
