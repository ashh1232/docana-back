<?php


include "./connect.php";

$id = filterRequest('pro_id');
getAllData("images", "image_pro = $id" );