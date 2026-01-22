<?php

$dsn = "mysql:host=localhost;dbname=docana;charset=utf8mb4";
// $user = "id22046333_webson";
$user = "root";
// $user = "ashh";
// $pass = "Web!son1232";
// hkjhgh
// $pass = "hell1232";
$pass = "";

$option = array(
   // التعديل الأهم: تحويل لـ utf8mb4 لدعم الإيموجي والأرقام العربية والزخرفة
   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci",
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES => false,
);

$countrowinpage = 9;

try {

   $con = new PDO($dsn, $user, $pass, $option);

   $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   header("Access-Control-Allow-Origin: *");

   header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");

   header("Access-Control-Allow-Methods: POST, OPTIONS , GET");

   include "func.php";

   if (!isset($notAuth)) {

      // checkAuthenticate();

   }
   /////
   // } catch (PDOException $e) {
   //    // في بيئة الإنتاج يفضل تسجيل الخطأ بدلاً من عرضه
   //    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
   // }
   ///
} catch (PDOException $e) {

   echo $e->getMessage();
}
