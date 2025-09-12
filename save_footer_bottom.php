<?php
// save_footer_bottom.php
declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function jexit($arr) { echo json_encode($arr, JSON_UNESCAPED_UNICODE); exit; }

// ตรวจ CSRF
$csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  jexit(['ok'=>false,'error'=>'CSRF token ไม่ถูกต้อง']);
}

$copy  = trim($_POST['copy_text'] ?? '');
$link1 = trim($_POST['link1'] ?? '');
$link2 = trim($_POST['link2'] ?? '');
$link3 = trim($_POST['link3'] ?? '');

if ($copy==='' || $link1==='' || $link2==='' || $link3==='') {
  jexit(['ok'=>false,'error'=>'กรุณากรอกข้อมูลให้ครบถ้วน']);
}

$stmt = $conn->prepare("UPDATE site_footer_bottom SET copy_text=?, link1=?, link2=?, link3=? WHERE id=1");
if (!$stmt) jexit(['ok'=>false,'error'=>'ไม่สามารถเตรียมคำสั่งได้']);
$stmt->bind_param('ssss', $copy, $link1, $link2, $link3);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) jexit(['ok'=>false,'error'=>'บันทึกไม่สำเร็จ']);

jexit(['ok'=>true,'copy_text'=>$copy,'link1'=>$link1,'link2'=>$link2,'link3'=>$link3]);
