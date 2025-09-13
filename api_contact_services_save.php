<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'msg'=>'Method not allowed']); exit;
}
if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'msg'=>'CSRF ไม่ถูกต้อง']); exit;
}

$item1 = (string)($_POST['item1_html'] ?? '');
$item2 = (string)($_POST['item2_html'] ?? '');
$item3 = (string)($_POST['item3_html'] ?? '');
$item4 = (string)($_POST['item4_html'] ?? '');

$stmt = $conn->prepare("
  INSERT INTO contact_page_services (id, item1_html, item2_html, item3_html, item4_html)
  VALUES (1, ?, ?, ?, ?)
  ON DUPLICATE KEY UPDATE
    item1_html=VALUES(item1_html),
    item2_html=VALUES(item2_html),
    item3_html=VALUES(item3_html),
    item4_html=VALUES(item4_html),
    updated_at=NOW()
");
$stmt->bind_param('ssss', $item1, $item2, $item3, $item4);
$ok = $stmt->execute();
$stmt->close();

echo $ok ? json_encode(['ok'=>true, 'msg'=>'บันทึกสำเร็จ'])
         : (http_response_code(500) || true) && json_encode(['ok'=>false, 'msg'=>'บันทึกไม่สำเร็จ']);
