<?php
// api_contact_card_save.php
require_once __DIR__ . '/db.php';   // ถ้าเก็บไว้ใน /api ให้แก้เป็น __DIR__.'/../db.php'

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'msg'=>'Method not allowed']); exit;
}

if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'msg'=>'CSRF ไม่ถูกต้อง']); exit;
}

$address_html = (string)($_POST['address_html'] ?? '');
$phone_html   = (string)($_POST['phone_html']   ?? '');
$email_html   = (string)($_POST['email_html']   ?? '');
$time_html    = (string)($_POST['time_html']    ?? '');

$stmt = $conn->prepare("
  INSERT INTO contact_page_contact (id, address_html, phone_html, email_html, time_html)
  VALUES (1, ?, ?, ?, ?)
  ON DUPLICATE KEY UPDATE
    address_html=VALUES(address_html),
    phone_html  =VALUES(phone_html),
    email_html  =VALUES(email_html),
    time_html   =VALUES(time_html),
    updated_at  =NOW()
");
$stmt->bind_param('ssss', $address_html, $phone_html, $email_html, $time_html);
$ok = $stmt->execute();
$stmt->close();

if ($ok) echo json_encode(['ok'=>true, 'msg'=>'บันทึกสำเร็จ']);
else { http_response_code(500); echo json_encode(['ok'=>false, 'msg'=>'บันทึกไม่สำเร็จ']); }
