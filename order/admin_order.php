<?php

include "../connect.php"; // Make sure this file exists with your DB connection
header('Content-Type: application/json');

getAllData('orders',"order_status = 'pending'");
