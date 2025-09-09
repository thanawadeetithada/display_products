<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>'Bad CSRF']); exit;
}

$name = $_POST['name'] ?? '';
if ($name === '') { echo json_encode(['ok'=>false, 'error'=>'ชื่อห้ามว่าง']); exit; }

// TODO แนะนำใช้ HTMLPurifier sanitize $name ก่อนเก็บจริง
try {
  $stmt = $conn->prepare("INSERT INTO site_header (id, name) VALUES (1, ?) ON DUPLICATE KEY UPDATE name=VALUES(name)");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  echo json_encode(['ok'=>true, 'name'=>$name]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
