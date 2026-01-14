<?php

include "../connect.php";
$id = filterRequest("pro_id");
getAllData("images", "pro_id = $id ");
