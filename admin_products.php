<?php
// maintenance.php
http_response_code(503); // ส่งสถานะ HTTP 503 Service Unavailable
header('Retry-After: 3600'); // แจ้ง browser/search engine ว่าลองใหม่ใน 1 ชั่วโมง

?><!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ระบบกำลังอัปเดต</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Tahoma, sans-serif;
      background-color: #f4f6f9;
      color: #333;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .box {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 500px;
    }
    h1 { color: #d9534f; margin-bottom: 1rem; }
    p { font-size: 1.1rem; margin: 0.5rem 0; }
    .loader {
      margin: 1.5rem auto;
      border: 6px solid #f3f3f3;
      border-top: 6px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="box">
    <h1>หน้านี้ยังไม่พร้อมใช้งานในขณะนี้ กำลังอัปเดตระบบ</h1>
    <div class="loader"></div>
    <p>หน้านี้ยังไม่พร้อมใช้งานในขณะนี้</p>
    <p>กรุณากลับมาใหม่อีกครั้งภายหลัง</p>
  </div>
</body>
</html>
