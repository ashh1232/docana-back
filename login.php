<?php

include "./connect.php";

$username = filterRequest("username");
$password = sha1($_POST["password"]);

getData("users", "users_name = ? AND users_password = ?", array($username, $password));
