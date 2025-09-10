<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['ok'=>false,'error'=>'Invalid method']); exit; }

$csrf = $_POST['csrf'] ?? '';
$key  = $_POST['key']  ?? '';
$html = $_POST['html'] ?? '';

if (!hash_equals($_SESSION['csrf'] ?? '', $csrf)) { echo json_encode(['ok'=>false,'error'=>'CSRF failed']); exit; }
if ($key === '') { echo json_encode(['ok'=>false,'error'=>'Missing key']); exit; }

$allowed = ['feat1_title','feat1_content','feat2_title','feat2_content','feat3_title','feat3_content'];
if (!in_array($key, $allowed, true)) {
  echo json_encode(['ok'=>false,'error'=>'Invalid key']); exit;
}

$sql = "INSERT INTO site_feat_parts(`key`, html, updated_at)
        VALUES(?, ?, NOW())
        ON DUPLICATE KEY UPDATE html = VALUES(html), updated_at = NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $key, $html);

echo $stmt->execute()
  ? json_encode(['ok'=>true, 'html'=>$html])
  : json_encode(['ok'=>false, 'error'=>'DB error']);
