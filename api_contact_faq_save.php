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

$fields = ['q1_html','a1_html','q2_html','a2_html','q3_html','a3_html','q4_html','a4_html'];
$data = [];
foreach ($fields as $f) { $data[$f] = (string)($_POST[$f] ?? ''); }

$stmt = $conn->prepare("
  INSERT INTO contact_page_faq
    (id,q1_html,a1_html,q2_html,a2_html,q3_html,a3_html,q4_html,a4_html)
  VALUES
    (1,?,?,?,?,?,?,?,?)
  ON DUPLICATE KEY UPDATE
    q1_html=VALUES(q1_html), a1_html=VALUES(a1_html),
    q2_html=VALUES(q2_html), a2_html=VALUES(a2_html),
    q3_html=VALUES(q3_html), a3_html=VALUES(a3_html),
    q4_html=VALUES(q4_html), a4_html=VALUES(a4_html),
    updated_at=NOW()
");
$stmt->bind_param(
  'ssssssss',
  $data['q1_html'],$data['a1_html'],
  $data['q2_html'],$data['a2_html'],
  $data['q3_html'],$data['a3_html'],
  $data['q4_html'],$data['a4_html']
);
$ok = $stmt->execute();
$stmt->close();

echo $ok ? json_encode(['ok'=>true,'msg'=>'บันทึกสำเร็จ'])
         : (http_response_code(500) || true) && json_encode(['ok'=>false,'msg'=>'บันทึกไม่สำเร็จ']);
