<?php
// save_footer_contact.php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

/* --- CSRF --- */
$csrf = $_POST['csrf'] ?? '';
if (empty($csrf) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  echo json_encode(['ok'=>false,'error'=>'CSRF invalid']); exit;
}

/* --- รับค่า --- */
$title   = trim($_POST['title']   ?? '');
$number1 = trim($_POST['number1'] ?? '');
$number2 = trim($_POST['number2'] ?? '');
$email   = trim($_POST['email']   ?? '');

if ($title === '' || $number1 === '' || $email === '') {
  echo json_encode(['ok'=>false,'error'=>'กรอกหัวข้อ / เบอร์ 1 / อีเมล']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['ok'=>false,'error'=>'อีเมลไม่ถูกต้อง']); exit;
}

/* --- บันทึก (UPSERT) --- */
try {
  $sql = "INSERT INTO site_footer_contact (id,title,number1,number2,email)
          VALUES (1,?,?,?,?)
          ON DUPLICATE KEY UPDATE
            title=VALUES(title),
            number1=VALUES(number1),
            number2=VALUES(number2),
            email=VALUES(email)";
  $stmt = $conn->prepare($sql);
  if (!$stmt) throw new Exception($conn->error);
  $stmt->bind_param('ssss', $title,$number1,$number2,$email);
  $stmt->execute();

  echo json_encode(['ok'=>true,
    'title'=>$title,'number1'=>$number1,'number2'=>$number2,'email'=>$email
  ]);
} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'error'=>'DB error: '.$e->getMessage()]);
}
