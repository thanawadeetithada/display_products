<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false, 'msg'=>'Method not allowed']); exit;
}
if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'msg'=>'CSRF ไม่ถูกต้อง']); exit;
}

$line_html     = (string)($_POST['line_html']     ?? '');
$whatsapp_html = (string)($_POST['whatsapp_html'] ?? '');
$facebook_html = (string)($_POST['facebook_html'] ?? '');

$stmt = $conn->prepare("
  INSERT INTO contact_page_channels (id, line_html, whatsapp_html, facebook_html)
  VALUES (1, ?, ?, ?)
  ON DUPLICATE KEY UPDATE
    line_html=VALUES(line_html),
    whatsapp_html=VALUES(whatsapp_html),
    facebook_html=VALUES(facebook_html),
    updated_at=NOW()
");
$stmt->bind_param('sss', $line_html, $whatsapp_html, $facebook_html);
$ok = $stmt->execute();
$stmt->close();

echo $ok ? json_encode(['ok'=>true, 'msg'=>'บันทึกสำเร็จ'])
         : (http_response_code(500) || true) && json_encode(['ok'=>false,'msg'=>'บันทึกไม่สำเร็จ']);
