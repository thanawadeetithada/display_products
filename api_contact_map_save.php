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

$lat  = isset($_POST['lat'])  ? (float)$_POST['lat']  : null;
$lng  = isset($_POST['lng'])  ? (float)$_POST['lng']  : null;
$zoom = 18;

$addr = (string)($_POST['address_html'] ?? '');
$addr = strip_tags($addr, '<br><br/>');

if ($lat === null || $lng === null || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
  http_response_code(422);
  echo json_encode(['ok'=>false,'msg'=>'พิกัดไม่ถูกต้อง']); exit;
}

$stmt = $conn->prepare("
  INSERT INTO contact_page_map (id, address_html, lat, lng, zoom)
  VALUES (1, ?, ?, ?, ?)
  ON DUPLICATE KEY UPDATE
    address_html = VALUES(address_html),
    lat = VALUES(lat),
    lng = VALUES(lng),
    zoom = VALUES(zoom),
    updated_at = NOW()
");
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'msg'=>'เตรียมคำสั่งล้มเหลว']); exit;
}

$stmt->bind_param('sddi', $addr, $lat, $lng, $zoom);
$ok = $stmt->execute();
$err = $ok ? '' : $stmt->error;
$stmt->close();

if ($ok) {
  echo json_encode(['ok'=>true, 'lat'=>$lat, 'lng'=>$lng, 'zoom'=>$zoom, 'address_html'=>$addr]);
} else {
  http_response_code(500);
  echo json_encode(['ok'=>false,'msg'=>'บันทึกไม่สำเร็จ','error'=>$err]);
}
