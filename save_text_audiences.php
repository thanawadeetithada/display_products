<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function fail($m,$c=400){
  http_response_code($c);
  echo json_encode(['ok'=>false,'error'=>$m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  fail('CSRF ไม่ถูกต้อง', 403);
}

$html = $_POST['html'] ?? null;
if ($html === null) fail('ไม่มีข้อมูล html');

$stmt = $conn->prepare(
  "INSERT INTO site_text_audiences (id, html) VALUES (1, ?)
   ON DUPLICATE KEY UPDATE html = VALUES(html), updated_at = NOW()"
);
if (!$stmt) fail('เตรียมคำสั่งล้มเหลว', 500);
$stmt->bind_param('s', $html);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) fail('บันทึกฐานข้อมูลล้มเหลว', 500);

echo json_encode(['ok'=>true, 'html'=>$html], JSON_UNESCAPED_UNICODE);
