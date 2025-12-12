<?php
include "./connect.php";

$username     = filterRequest("username");
$addressid   = filterRequest("addressid");
$ordersprice = filterRequest("ordersprice");
$phone       = filterRequest("phone");
$cartData    = filterRequest("cart");

$data = array(
    "orders_username" => $username,
    // "orders_address"  => $addressid,
    // "orders_price"    => $ordersprice,
    // "phone"           => $phone,
    // "cart_data"       =>$cartData
);
echo json_encode($data);

// insertData("doc_orders", $data );
foreach ($data as $field => $v)
    $ins[] = ':' . $field;
$ins = implode(',', $ins);
$fields = implode(',', array_keys($data));
$sql = "INSERT INTO $table ($fields) VALUES ($ins)";

$stmt = $con->prepare($sql);
foreach ($data as $f => $v) {
    $stmt->bindValue(':' . $f, $v);
}
$stmt->execute();
$count = $stmt->rowCount();
echo json_encode($count);

if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
}
