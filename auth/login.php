<?php

include "../connect.php";

$password = sha1($_POST["password"]);
$username = filterRequest("username");


getData("users", "user_name = ? AND user_password = ?", array($username, $password));
