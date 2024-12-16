<?php

include "./connect.php";

$userid = filterRequest('id');

getAllData("notification", "notification_id = $userid ORDER BY $userid DESC ");