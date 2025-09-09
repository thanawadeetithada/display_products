<?php
// save_title_home.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>'Bad CSRF']); exit;
}

$html = $_POST['html'] ?? '';
if ($html === '') { echo json_encode(['ok'=>false, 'error'=>'เนื้อหาห้ามว่าง']); exit; }

/* แนะนำ sanitize ด้วย HTMLPurifier ก่อนบันทึก */
try {
  $stmt = $conn->prepare("INSERT INTO site_title_home (id, html) VALUES (1, ?) ON DUPLICATE KEY UPDATE html=VALUES(html)");
  $stmt->bind_param("s", $html);
  $stmt->execute();
  echo json_encode(['ok'=>true, 'html'=>$html]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
