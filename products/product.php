<?php
include "../connect.php";
$vendorId = filterRequest('vendor');
getAllData("productview","vendor_id = '$vendorId'");
