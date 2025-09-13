<?php
// api_contact_title_save.php
require_once __DIR__ . '/db.php';

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

$html = (string)($_POST['html'] ?? '');

$stmt = $conn->prepare(
  "INSERT INTO contact_page_title (id, html)
   VALUES (1, ?)
   ON DUPLICATE KEY UPDATE html=VALUES(html), updated_at=NOW()"
);
$stmt->bind_param('s', $html);
$ok = $stmt->execute();
$stmt->close();

if ($ok) echo json_encode(['ok'=>true,'msg'=>'บันทึกสำเร็จ']);
else { http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'บันทึกไม่สำเร็จ']); }
