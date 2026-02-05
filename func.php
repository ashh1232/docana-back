<?php
define("MB", 1048576);

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table ");
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }

    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
        return $count;
    } else {
        if ($count > 0) {
            return array("status" => "success", "data" => $data);
        } else {
            return array("status" => "failure");
        }
    }
}
function getData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    } else {
        return $count;
    }
}
function insertData($table, $data, $json = true)
{
    global $con;
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
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}


function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function imageUpload($imageRequest)
{
    global $msgError;
    $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "png", "gif", "mp3", "pdf");
    $strToArray = explode(".", $imagename);
    $ext        = end($strToArray);
    $ext        = strtolower($ext);

    if (!empty($imagename) && !in_array($ext, $allowExt)) {
        $msgError = "EXT";
    }
    if ($imagesize > 2 * MB) {
        $msgError = "size";
    }
    if (empty($msgError)) {
        move_uploaded_file($imagetmp,  "../upload/" . $imagename);
        return $imagename;
    } else {
        return "fail";
    }
}



function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "wael" ||  $_SERVER['PHP_AUTH_PW'] != "wael12345") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }

    // End 
}
function printFailure($message = 'none')
{
    echo    json_encode(array('status' => 'failure', 'message' => $message));
}
function result($count)
{
    if ($count > 0) {
        printSuccess();
    } else {
        printFailure();
    }
}

function printSuccess($message = 'none')
{
    echo    json_encode(array('status' => 'success', 'message' => $message));
}



function sendEmail($to, $title, $body)
{
    $header = "from: ashhsmith3@gmail.com";
    mail($to, $title, $body, $header);
}



function sendGCM($title, $message, $topic, $pageid, $pagename)
{


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        "to" => '/topics/' . $topic,
        'priority' => 'high',
        'content_available' => true,

        'notification' => array(
            "body" =>  $message,
            "title" =>  $title,
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "sound" => "default"

        ),
        'data' => array(
            "pageid" => $pageid,
            "pagename" => $pagename
        )

    );


    $fields = json_encode($fields);
    $headers = array(
        'Authorization: key=' . "AAAAmjSyxoA:APA91bEJIki31UrZ2JAwWa-_M21CojmjD57Ns-ZwoU9YF-pdyQedBIAcLU4bnuGjiBWsnUQBPYykDcIQ_KoHNKiDLAbVJvOtmr3yzVTSVOMUA-GpX7iIZ_FqwGvdmz5e0Wpg89WR8Knf",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    return $result;
    curl_close($ch);
}


function insertNotify($title, $body, $userid, $topic, $pageid, $pagename)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`( `notification_title`, `notification_body`, `notification_userid`) VALUES (?,?,?)");
    $stmt->execute(array($title, $body, $userid));

    sendGCM($title, $body, $topic, $pageid, $pagename);
    $count = $stmt->rowCount();
    return $count;
}



function getPaginatedData($table, $limit, $offset, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table LIMIT ? OFFSET ? ");
        $stmt->execute(array($limit, $offset));
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where LIMIT ? OFFSET ? ");
        $values[] = $limit;
        $values[] = $offset;
        $stmt->execute($values);
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
        return $count;
    } else {
        if ($count > 0) {
            return array("status" => "success", "data" => $data);
        } else {
            return array("status" => "failure");
        }
    }
}





function createOrder($con)
{
    try {
        // Get POST data - No need for manual escaping anymore!
        $userId = $_POST['user_id'];
        $vendorId = $_POST['vendor_id'];
        $total = $_POST['total'];
        $subtotal = $_POST['subtotal'];
        $shipping = $_POST['shipping'];
        $deliveryName = $_POST['delivery_name'];
        $deliveryPhone = $_POST['delivery_phone'];
        $deliveryAddress = $_POST['delivery_address'];
        $deliveryLat = $_POST['lat'];
        $deliveryLong = $_POST['long'];
        // $deliveryCountry = $_POST['delivery_country'];
        $orderItems = $_POST['order_items'] ?? '';
        $orderNotes = isset($_POST['order_notes']) ? $_POST['order_notes'] : '';

        // Start transaction
        $con->beginTransaction();

        // 1. Insert order using Prepared Statements
        $sql = "INSERT INTO orders (
            user_id,vendor_id, order_total, order_subtotal, order_shipping,
            delivery_name, delivery_phone, delivery_address, location_lat, location_long,
             order_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->execute([
            $userId,
            $vendorId,
            $total,
            $subtotal,
            $shipping,
            $deliveryName,
            $deliveryPhone,
            $deliveryAddress,
            $deliveryLat,
            $deliveryLong,
            $orderNotes
        ]);

        $orderId = $con->lastInsertId();

        // 2. Insert order items
        $items = json_decode($orderItems, true);
        if (is_array($items)) {
            $itemSql = "INSERT INTO order_items (
                order_id, vendor_id, product_id, product_name, product_image, product_price, item_quantity, item_total
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $itemStmt = $con->prepare($itemSql);

            foreach ($items as $item) {
                $itemTotal = $item['product_price'] * $item['quantity'];
                $itemStmt->execute([
                    $orderId,
                    $item['vendor_id'], // مهم جداً لفرز الطلبات لاحقاً لكل تاجر
                    $item['product_id'],
                    $item['product_name'],
                    $item['product_image'],
                    $item['product_price'],
                    $item['quantity'],
                    $itemTotal
                ]);
            }
        }

        $con->commit();
        echo json_encode(['status' => 'success', 'order_id' => $orderId]);
    } catch (Exception $e) {
        $con->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function getUserOrders($con)
{
    try {
        // 1. استلام القيمة من POST
        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
            return;
        }

        // 2. كتابة الاستعلام باستخدام Placeholders (?) بدلاً من الـ Escape اليدوي
        $sql = "SELECT 
            o.order_id, o.order_total, o.order_status, o.created_at,
            COUNT(oi.item_id) as item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.order_id
        ORDER BY o.created_at DESC";

        // 3. تحضير وتنفيذ الاستعلام (PDO Prepared Statements)
        $stmt = $con->prepare($sql);
        $stmt->execute([$userId]);

        // 4. جلب النتائج
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $orders]);
    } catch (PDOException $e) {
        // في حال حدوث خطأ في قاعدة البيانات
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
function getOrderDetails($con)
{
    try {
        // 1. التأكد من وجود order_id
        $orderId = $_POST['order_id'] ?? null;
        if (!$orderId) {
            echo json_encode(['status' => 'error', 'message' => 'Order ID is required']);
            return;
        }

        // 2. جلب معلومات الطلب الأساسية
        $orderSql = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $con->prepare($orderSql);
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // 3. جلب عناصر الطلب (Items)
            $itemsSql = "SELECT * FROM order_items WHERE order_id = ?";
            $itemStmt = $con->prepare($itemsSql);
            $itemStmt->execute([$orderId]);
            $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

            // إضافة العناصر إلى مصفوفة الطلب
            $order['items'] = $items;

            echo json_encode(['status' => 'success', 'data' => $order]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Order not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
