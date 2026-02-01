<?php

// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
   $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   foreach ($lines as $line) {
      $line = trim($line);
      if (empty($line) || strpos($line, '#') === 0) {
         continue;
      }
      if (strpos($line, '=') !== false) {
         list($key, $value) = explode('=', $line, 2);
         putenv(trim($key) . '=' . trim($value));
      }
   }
}

// Get database configuration from environment variables
$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

$option = array(
   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci",
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES => false,
);

$countrowinpage = 9;

try {

   $con = new PDO($dsn, $dbUser, $dbPass, $option);

   $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Set allowed origins from environment variable
   $allowedOrigins = explode(',', getenv('ALLOWED_ORIGINS') ?: 'http://localhost');
   $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

   if (in_array($origin, $allowedOrigins)) {
      header("Access-Control-Allow-Origin: $origin");
   }

   header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

   header("Access-Control-Allow-Methods: POST, OPTIONS, GET");

   header("Access-Control-Max-Age: 3600");

   header("Content-Type: application/json; charset=UTF-8");


   if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      http_response_code(204); // No content
      exit;
   }

   // Include security functions
   include_once "security.php";

   // Add security headers
   addSecurityHeaders();

   // Validate request data for SQL injection attempts
   validateRequestData();

   include_once "func.php";
} catch (PDOException $e) {

   $isDevelopment = getenv('APP_ENV') === 'development';

   if ($isDevelopment) {
      echo json_encode(["status" => "error", "message" => $e->getMessage()]);
   } else {
      echo json_encode(["status" => "error", "message" => "Database connection failed"]);
      error_log("Database Error: " . $e->getMessage());
   }
   exit;
}
