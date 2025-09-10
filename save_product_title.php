<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/db.php';

function jerr($msg, $code = 400) {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}

// ตรวจ CSRF
if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
  jerr('CSRF ไม่ถูกต้อง', 403);
}

$html = $_POST['html'] ?? '';
if ($html === '') {
  jerr('ไม่มีข้อมูล html');
}

$danger = ['<script', 'javascript:', 'onerror', 'onload', 'onclick'];
foreach ($danger as $bad) {
  if (stripos($html, $bad) !== false) {
    jerr('พบโค้ดไม่ปลอดภัยในเนื้อหา');
  }
}

try {
  $sql = "INSERT INTO site_product_title (id, html)
          VALUES (1, ?)
          ON DUPLICATE KEY UPDATE html = VALUES(html), updated_at = CURRENT_TIMESTAMP()";
  $stmt = $conn->prepare($sql);
  if (!$stmt) jerr('เตรียมคำสั่งล้มเหลว');
  $stmt->bind_param('s', $html);
  $ok = $stmt->execute();
  if (!$ok) jerr('บันทึกลงฐานข้อมูลไม่สำเร็จ');

  echo json_encode(['ok' => true, 'html' => $html], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  jerr('ข้อผิดพลาด: ' . $e->getMessage(), 500);
}
