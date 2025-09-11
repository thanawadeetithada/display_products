<?php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');

function out($a){ echo json_encode($a, JSON_UNESCAPED_UNICODE); exit; }

if (empty($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
  out(['ok'=>false, 'error'=>'CSRF invalid']);
}

$html = $_POST['html'] ?? '';

$stmt = $conn->prepare("INSERT INTO site_footer_title (id, html) VALUES (1, ?)
                        ON DUPLICATE KEY UPDATE html=VALUES(html)");
$stmt->bind_param('s', $html);
$ok = $stmt->execute();

if ($ok) out(['ok'=>true, 'html'=>$html]);
out(['ok'=>false, 'error'=>'DB error']);
