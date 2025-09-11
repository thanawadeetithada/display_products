<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');

function out($arr){ echo json_encode($arr, JSON_UNESCAPED_UNICODE); exit; }

if (empty($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
  out(['ok'=>false,'error'=>'CSRF invalid']);
}

$key  = $_POST['key']  ?? '';
$html = $_POST['html'] ?? '';

$allow = ['aud1','aud2','aud3','aud4'];
if (!in_array($key, $allow, true)) {
  out(['ok'=>false,'error'=>'Invalid key']);
}

$stmt = $conn->prepare("INSERT INTO site_audiences (`key`, html) VALUES (?, ?)
                        ON DUPLICATE KEY UPDATE html=VALUES(html)");
$stmt->bind_param('ss', $key, $html);
$ok = $stmt->execute();

if ($ok) {
  out(['ok'=>true,'key'=>$key,'html'=>$html]);
} else {
  out(['ok'=>false,'error'=>'DB error']);
}
