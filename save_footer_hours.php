<?php
// save_footer_hours.php
declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function jexit($arr) {
  echo json_encode($arr, JSON_UNESCAPED_UNICODE);
  exit;
}

// ตรวจ CSRF
$csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
  jexit(['ok' => false, 'error' => 'CSRF token ไม่ถูกต้อง']);
}

// รับค่า
$title = trim((string)($_POST['title'] ?? ''));
$hours = trim((string)($_POST['hours'] ?? ''));

// ตรวจสอบเบื้องต้น
if ($title === '' || $hours === '') {
  jexit(['ok' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
}
if (mb_strlen($title) > 200) {
  jexit(['ok' => false, 'error' => 'ความยาวหัวข้อเกินกำหนด']);
}

// บันทึก (update id=1)
$stmt = $conn->prepare("UPDATE site_footer_hours SET title=?, hours=? WHERE id=1");
if (!$stmt) {
  jexit(['ok' => false, 'error' => 'ไม่สามารถเตรียมคำสั่งได้']);
}
$stmt->bind_param('ss', $title, $hours);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
  jexit(['ok' => false, 'error' => 'บันทึกไม่สำเร็จ']);
}

// แปลง hours เป็น HTML (แสดงผล)
$hours_html = nl2br(htmlspecialchars($hours, ENT_QUOTES, 'UTF-8'));

jexit([
  'ok' => true,
  'title' => $title,
  'hours_html' => $hours_html,
]);
