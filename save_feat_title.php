<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json; charset=utf-8');

$csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'CSRF invalid']);
  exit;
}

$html = isset($_POST['html']) ? trim((string)$_POST['html']) : '';

if ($html === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'ไม่มีข้อมูล']);
  exit;
}

// เก็บ HTML ตรง ๆ (ให้ควบคุมความปลอดภัยที่การแสดงผลหน้าเว็บแทน)
$stmt = $conn->prepare("UPDATE site_feat_title SET html=?, updated_at=NOW() WHERE id=1");
$stmt->bind_param('s', $html);

if ($stmt->execute()) {
  echo json_encode(['ok' => true, 'html' => $html]);
} else {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'บันทึกไม่สำเร็จ']);
}
