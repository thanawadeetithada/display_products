<?php
// save_pic_slides.php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

// CSRF
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  echo json_encode(['ok'=>false, 'error'=>'CSRF invalid']); exit;
}

// โหลดของเดิม (กรณีไม่อัปบางรูปให้คงค่า)
$img1 = 'img/pic1.jpg';
$img2 = 'img/pic2.jpg';
$img3 = 'img/pic3.jpg';

$res = $conn->query("SELECT img1,img2,img3 FROM site_pic_slides WHERE id=1");
if ($row = $res->fetch_assoc()) {
  $img1 = $row['img1'];
  $img2 = $row['img2'];
  $img3 = $row['img3'];
}

// โฟลเดอร์เก็บไฟล์
$uploadDir = __DIR__ . '/uploads/slides';
$uploadUrl = 'uploads/slides';
if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }

function handleUpload($field, $oldPath, $uploadDir, $uploadUrl) {
  if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
    return $oldPath;
  }
  $file = $_FILES[$field];
  if ($file['error'] !== UPLOAD_ERR_OK) {
    return $oldPath;
  }
  $finfo = @getimagesize($file['tmp_name']);
  if ($finfo === false) {
    return $oldPath;
  }
  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg');
  $newName = uniqid('slide_', true) . '.' . $ext;
  $destPath = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $newName;

  if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    return $oldPath;
  }
  return rtrim($uploadUrl, '/\\') . '/' . $newName;
}

$img1 = handleUpload('img1', $img1, $uploadDir, $uploadUrl);
$img2 = handleUpload('img2', $img2, $uploadDir, $uploadUrl);
$img3 = handleUpload('img3', $img3, $uploadDir, $uploadUrl);

// upsert ตารางใหม่
$stmt = $conn->prepare("
  INSERT INTO site_pic_slides (id,img1,img2,img3)
  VALUES (1,?,?,?)
  ON DUPLICATE KEY UPDATE img1=VALUES(img1), img2=VALUES(img2), img3=VALUES(img3)
");
$stmt->bind_param('sss', $img1, $img2, $img3);
$ok = $stmt->execute();

if ($ok) {
  echo json_encode(['ok'=>true, 'img1'=>$img1, 'img2'=>$img2, 'img3'=>$img3]);
} else {
  echo json_encode(['ok'=>false, 'error'=>'DB error: '.$conn->error]);
}
