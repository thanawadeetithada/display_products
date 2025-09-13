



<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ---- ตั้งค่าการเชื่อมต่อ (InfinityFree) ----
$DB_HOST = "localhost";    // host จาก InfinityFree
$DB_USER = "root";               // user จาก InfinityFree
$DB_PASS = "";             // password จาก InfinityFree
$DB_NAME = "productDB";     // ชื่อ DB ต้องมี prefix ให้ตรง
$DB_PORT = 3306;                         // ปกติ 3306

// โหมดแสดง error ระหว่างพัฒนา (เปลี่ยนเป็น '0' เมื่อขึ้น production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

function db_connect(): mysqli {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT;

    // สร้างและเชื่อมต่อ
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

    // ตั้ง charset ให้รองรับไทย/อีโมจิครบ
    $conn->set_charset('utf8mb4');

    // (ทางเลือก) ตั้ง timezone ให้ตรงไทย
    // $conn->query("SET time_zone = '+07:00'");

    return $conn;
}

try {
    $conn = db_connect();

    // (ทางเลือก) ทดสอบ ping
    if (!$conn->ping()) {
        throw new Exception('Ping to MySQL server failed.');
    }

} catch (Throwable $e) {
    // ระหว่างดีบัก: แสดงรายละเอียด error เพื่อหาสาเหตุ
    // ขึ้น production ควรเปลี่ยนเป็นข้อความกลาง ๆ และ log ไว้แทน
    die('Database connection error: ' . $e->getMessage());
}
