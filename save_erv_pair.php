<?php
// save_erv_pair.php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function fail($m, $code=400){
  http_response_code($code);
  echo json_encode(['ok'=>false,'error'=>$m], JSON_UNESCAPED_UNICODE);
  exit;
}

if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  fail('CSRF ไม่ถูกต้อง', 403);
}

$which = $_POST['which'] ?? '';
if (!in_array($which, ['pair1','pair2'], true)) {
  fail('พารามิเตอร์ which ไม่ถูกต้อง');
}

// mapping
$picCol  = $which === 'pair1' ? 'pic1' : 'pic2';
$htmlCol = $which === 'pair1' ? 'benefits_html' : 'tech_html';

$dir = __DIR__ . '/uploads/erv';
$baseUrl = 'uploads/erv/';
if (!is_dir($dir)) {
  @mkdir($dir, 0775, true);
  if (!file_exists($dir.'/.htaccess')) file_put_contents($dir.'/.htaccess', "Options -Indexes\n");
}

$maxBytes = 5 * 1024 * 1024;
$allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];

function saveImageIfAny(string $field, string $dir, string $baseUrl, int $maxBytes, array $allowed) : ?string {
  if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
  $f = $_FILES[$field];
  if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('อัปโหลดไฟล์ไม่สำเร็จ');
  if ($f['size'] > $maxBytes) throw new RuntimeException('ไฟล์ใหญ่เกิน 5MB');

  $fi = new finfo(FILEINFO_MIME_TYPE);
  $mime = $fi->file($f['tmp_name']);
  if (!isset($allowed[$mime])) throw new RuntimeException('ชนิดไฟล์ไม่รองรับ (JPEG/PNG/WEBP)');

  $name = sprintf('erv_%s_%s%s', $field, bin2hex(random_bytes(6)), $allowed[$mime]);
  $dest = rtrim($dir,'/').'/'.$name;
  if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('ย้ายไฟล์ล้มเหลว');

  return $baseUrl.$name; // relative path
}

try {
  $newPic = saveImageIfAny('pic', $dir, $baseUrl, $maxBytes, $allowed);
  $newHtml = isset($_POST['html']) ? (string)$_POST['html'] : null;

  if ($newPic === null && $newHtml === null) {
    echo json_encode(['ok'=>true]); exit;
  }

  $sets = [];
  $types = '';
  $vals = [];

  if ($newPic !== null) { $sets[] = "$picCol = ?"; $types .= 's'; $vals[] = $newPic; }
  if ($newHtml !== null) { $sets[] = "$htmlCol = ?"; $types .= 's'; $vals[] = $newHtml; }

  $sql = 'UPDATE site_erv SET '.implode(', ', $sets).' WHERE id=1';
  $stmt = $conn->prepare($sql);
  if (!$stmt) fail('เตรียมคำสั่งล้มเหลว', 500);
  $stmt->bind_param($types, ...$vals);
  if (!$stmt->execute()) fail('อัปเดตฐานข้อมูลล้มเหลว', 500);
  $stmt->close();

  $resp = ['ok'=>true];
  if ($newPic !== null)  $resp['pic']  = $newPic;
  if ($newHtml !== null) $resp['html'] = $newHtml;

  echo json_encode($resp, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  fail($e->getMessage(), 500);
}
