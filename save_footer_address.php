<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function jexit($arr) {
  echo json_encode($arr, JSON_UNESCAPED_UNICODE);
  exit;
}

$csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  jexit(['ok' => false, 'error' => 'CSRF token ไม่ถูกต้อง']);
}

$title   = trim((string)($_POST['title']   ?? ''));
$address = trim((string)($_POST['address'] ?? ''));

if ($title === '' || $address === '') {
  jexit(['ok' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
}

if (mb_strlen($title) > 200) {
  jexit(['ok' => false, 'error' => 'ความยาวหัวข้อเกินกำหนด']);
}

$stmt = $conn->prepare("UPDATE site_footer_address SET title=?, address=? WHERE id=1");
if (!$stmt) {
  jexit(['ok' => false, 'error' => 'ไม่สามารถเตรียมคำสั่งได้']);
}
$stmt->bind_param('ss', $title, $address);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
  jexit(['ok' => false, 'error' => 'บันทึกไม่สำเร็จ']);
}

$address_html = nl2br(htmlspecialchars($address, ENT_QUOTES, 'UTF-8'));

jexit([
  'ok' => true,
  'title' => $title,
  'address_html' => $address_html,
]);
