<?php
    require_once __DIR__ . '/db.php';
    function e($s)
    {return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');}

    // header
    $H   = [];
    $res = $conn->query("SELECT name FROM site_header WHERE id=1");
    if ($row = $res->fetch_assoc()) {$H = $row;}

    // footer title
    $FOOTER_TITLE_HTML = '';
    $resFT = $conn->query("SELECT html FROM site_footer_title WHERE id=1");
    if ($rowFT = $resFT->fetch_assoc()) { $FOOTER_TITLE_HTML = $rowFT['html']; }
    if ($FOOTER_TITLE_HTML === '') {
    $FOOTER_TITLE_HTML = '<h4 class="mb-3">LUMA AIR</h4>
    <p class="mb-0">ผู้เชี่ยวชาญระบบแลกเปลี่ยนอากาศ เพื่อคุณภาพอากาศที่ดีในบ้าน</p>';
    }

    // footer contact+email
    $F = [
    'title'   => 'ติดต่อเรา',
    'number1' => '',
    'number2' => '',
    'email'   => 'info@lumaair.com',
    ];
    $resF = $conn->query("SELECT title, number1, number2, email FROM site_footer_contact WHERE id=1");
    if ($row = $resF->fetch_assoc()) {
      foreach (['title','number1','number2','email'] as $k) {
      if (isset($row[$k]) && $row[$k] !== '') $F[$k] = $row[$k];
      }
    }

    // footer address
    $ADDR = [
    'title'   => 'ที่อยู่',
    'address' => "กรุงเทพมหานคร",
    ];
    $resAddr = $conn->query("SELECT title, address FROM site_footer_address WHERE id=1");
    if ($row = $resAddr->fetch_assoc()) {
        foreach (['title','address'] as $k) {
        if (isset($row[$k]) && $row[$k] !== '') $ADDR[$k] = $row[$k];
        }
    }

    // footer hours
    $HOURS = [
        'title' => 'เวลาทำการ',
        'hours' => "จันทร์–ศุกร์: 9:00–18:00\nเสาร์–อาทิตย์: 10:00–16:00",
    ];
    $resHours = $conn->query("SELECT title, hours FROM site_footer_hours WHERE id=1");
    if ($row = $resHours->fetch_assoc()) {
        foreach (['title','hours'] as $k) {
            if (isset($row[$k]) && $row[$k] !== '') $HOURS[$k] = $row[$k];
        }
    }

    // footer bottom
    $FBOT = [
        'copy_text' => '© '.date('Y').' '.($F['name'] ?? 'LUMA AIR').'. สงวนลิขสิทธิ์ทุกประการ',
        'link1'     => 'ติดตั้งทั่วประเทศ',
        'link2'     => 'รับประกัน 2 ปี',
        'link3'     => 'บริการหลังการขาย',
    ];
    $resFbot = $conn->query("SELECT copy_text, link1, link2, link3 FROM site_footer_bottom WHERE id=1");
    if ($row = $resFbot->fetch_assoc()) {
        foreach (['copy_text','link1','link2','link3'] as $k) {
            if (!empty($row[$k])) $FBOT[$k] = $row[$k];
        }
    }

    // CSRF
    if (session_status() === PHP_SESSION_NONE) {session_start();}
    if (empty($_SESSION['csrf'])) {$_SESSION['csrf'] = bin2hex(random_bytes(16));}
    $csrf = $_SESSION['csrf'];

    $TITLE_HTML = '<h1 class="fw-bold mb-2">ติดต่อเรา</h1>
<p class="text-muted m-0">
ติดต่อเราได้ทุกช่องทางเพื่อสอบถามข้อมูล LUMA AIR ERV
หรือขอคำปรึกษาเกี่ยวกับระบบแลกเปลี่ยนอากาศในบ้านของคุณ
</p>';

$resTitle = $conn->query("SELECT html FROM contact_page_title WHERE id=1");
if ($rowT = $resTitle->fetch_assoc()) {
  if (!empty($rowT['html'])) $TITLE_HTML = $rowT['html'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'save_title') {

  header('Content-Type: application/json; charset=utf-8');

  if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    http_response_code(400);
    echo json_encode(['ok'=>false, 'msg'=>'CSRF ไม่ถูกต้อง']); exit;
  }

  $html = isset($_POST['html']) ? (string)$_POST['html'] : '';

  $stmt = $conn->prepare(
    "INSERT INTO contact_page_title (id, html) VALUES (1, ?)
     ON DUPLICATE KEY UPDATE html=VALUES(html), updated_at=NOW()"
  );
  $stmt->bind_param('s', $html);
  $ok = $stmt->execute();
  $stmt->close();

  if ($ok) { echo json_encode(['ok'=>true, 'msg'=>'บันทึกสำเร็จ']); }
  else { http_response_code(500); echo json_encode(['ok'=>false, 'msg'=>'บันทึกไม่สำเร็จ']); }
  exit;
}

/* ===== Contact card ===== */
$CONTACT = [
  'address_html' => 'กรุงเทพมหานคร',
  'phone_html'   => '02-123-4567<br>089-123-4567',
  'email_html'   => 'info@lumaair.com<br>support@lumaair.com',
  'time_html'    => 'จันทร์–ศุกร์: 9:00–18:00<br>เสาร์–อาทิตย์: 10:00–16:00',
];

$resC = $conn->query("SELECT address_html, phone_html, email_html, time_html FROM contact_page_contact WHERE id=1");
if ($rowC = $resC->fetch_assoc()) {
  foreach (['address_html','phone_html','email_html','time_html'] as $k) {
    if (!empty($rowC[$k])) $CONTACT[$k] = $rowC[$k];
  }
}

/* ===== Other channels ===== */
$CHANNELS = [
  'line_html'     => 'สำหรับการสอบถามและปรึกษา',
  'whatsapp_html' => 'ตอบกลับเร็วในเวลาทำการ',
  'facebook_html' => 'ติดตามข่าวสารและโปรโมชั่น',
];
$resCh = $conn->query("SELECT line_html, whatsapp_html, facebook_html FROM contact_page_channels WHERE id=1");
if ($rowCh = $resCh->fetch_assoc()) {
  foreach (['line_html','whatsapp_html','facebook_html'] as $k) {
    if (!empty($rowCh[$k])) $CHANNELS[$k] = $rowCh[$k];
  }
}

/* ===== Services ===== */
$SERVICES = [
  'item1_html' => 'ติดตั้งและตรวจสอบระบบฟรี',
  'item2_html' => 'รับประกันระบบ 2 ปี พร้อมบริการหลังการขาย',
  'item3_html' => 'บริการซ่อมบำรุงและทำความสะอาด',
  'item4_html' => 'ปรึกษาระบบแลกเปลี่ยนอากาศฟรี',
];
$resS = $conn->query("SELECT item1_html, item2_html, item3_html, item4_html FROM contact_page_services WHERE id=1");
if ($rowS = $resS->fetch_assoc()) {
  foreach (['item1_html','item2_html','item3_html','item4_html'] as $k) {
    if (!empty($rowS[$k])) $SERVICES[$k] = $rowS[$k];
  }
}

/* ===== FAQ ===== */
$FAQ = [
  'q1_html'=>'LUMA AIR ERV มีประสิทธิภาพจริงหรือไม่?',
  'a1_html'=>'ใช้แผงแลกเปลี่ยนประสิทธิภาพการกู้คืนความร้อน 75–85% พร้อมระบบกรองอากาศ HEPA + Carbon Filter',
  'q2_html'=>'ค่าไฟฟ้าจะเพิ่มขึ้นมากหรือไม่?',
  'a2_html'=>'ไม่มาก ระบบใช้ไฟเพียง 45–120 วัตต์ และมีระบบ Heat Recovery ช่วยประหยัดพลังงาน',
  'q3_html'=>'สามารถติดตั้งในบ้านเก่าได้หรือไม่?',
  'a3_html'=>'ได้ครับ ติดตั้งได้ทั้งบ้าน/คอนโด/อาคาร สายงานซ่อนเรียบร้อยโดยทีมช่างมืออาชีพ',
  'q4_html'=>'เสียงดังหรือไม่?',
  'a4_html'=>'เงียบ ระดับเสียงต่ำกว่า 25 dB(A) ไม่รบกวนการนอนหลับ',
];
$resFAQ = $conn->query("SELECT q1_html,a1_html,q2_html,a2_html,q3_html,a3_html,q4_html,a4_html FROM contact_page_faq WHERE id=1");
if ($rowF = $resFAQ->fetch_assoc()) {
  foreach (['q1_html','a1_html','q2_html','a2_html','q3_html','a3_html','q4_html','a4_html'] as $k) {
    if (!empty($rowF[$k])) $FAQ[$k] = $rowF[$k];
  }
}

/* ===== MAP ===== */
$MAP = [
  'address_html' => 'แผนที่แสดงตำแหน่งร้าน<br>123 ถนนสุขุมวิท คลองเอย กรุงเทพฯ',
  'lat'  => 13.7563310,
  'lng'  => 100.5017620,
  'zoom' => 18
];
$resMap = $conn->query("SELECT address_html,lat,lng,zoom FROM contact_page_map WHERE id=1");
if ($rowM = $resMap->fetch_assoc()) {
  foreach (['address_html','lat','lng','zoom'] as $k) {
    if ($rowM[$k] !== null && $rowM[$k] !== '') $MAP[$k] = $rowM[$k];
  }
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>บทความ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css">
    <?php $cssv = file_exists('admin_style.css') ? md5_file('admin_style.css') : time(); ?>
    <link rel="stylesheet" href="admin_style.css?v=<?php echo $cssv ?>">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
    .navbar-brand {
        font-weight: 700;
        letter-spacing: .5px;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        color: #000;
        padding: 8px 14px !important;
        border-radius: 1rem;
        margin-left: 20px;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active,
    .navbar-expand-lg .navbar-nav .nav-link.active {
        background: #eaf6fb;
        color: var(--primary);
    }

    @media (max-width:575px) {
        .navbar {
            padding: 8px 20px;
        }
    }

    :root {
        --luma-primary: var(--bs-primary, #0d6efd);
    }

    .contact-hero {
        background: linear-gradient(180deg, #e9fbff 0%, #e6f7f7 100%);
    }

    .soft-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .05);
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 10px 22px rgba(13, 110, 253, .08), 0 2px 6px rgba(0, 0, 0, .04);
    }

    .icon-blob {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .1);
        color: var(--luma-primary);
        border-radius: 10px;
        flex: 0 0 36px;
    }

    .icon-blob svg {
        width: 20px;
        height: 20px;
        fill: currentColor;
    }

    .map-box {
        background: #e9ecef;
        border: 1px dashed rgba(0, 0, 0, .15);
        height: 260px;
    }

    .pill-item {
        background: #f8f9fa;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 12px;
    }

    .bg-line {
        background: #eef7ff;
    }

    .bg-wa {
        background: #eefcf2;
    }

    .bg-fb {
        background: #eef3ff;
    }

    .service-chip {
        background: #f7fbff;
        border: 1px solid rgba(13, 110, 253, .15);
        padding: 10px 12px;
        border-radius: 12px;
    }

    .service-chip .dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--luma-primary);
        margin-right: 8px;
        vertical-align: middle;
    }

    .faq-item {
        margin-bottom: 16px;
    }

    h1 {
        letter-spacing: .5px;
    }

    h5 {
        letter-spacing: .2px;
    }

    @media (max-width: 576px) {
        .soft-card {
            padding: 16px;
        }

        .map-box {
            height: 220px;
        }
    }

    .text-FAQ {
        margin-bottom: 5px;
        font-weight: bold;
        color: black;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg border-bottom sticky-top">
        <div class="container">
            <div><a class="navbar-brand" id="brandLink" href="index2.php"><?php echo $H['name'] ?? 'LUMA AIR' ?></a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="สลับเมนู"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="admin_home.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_article.php">บทความ</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin_contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="contact-hero py-5">
        <div class="container">
            <div class="text-center mb-4 mb-lg-5">
                <div id="titleView">
                    <?php echo $TITLE_HTML; ?>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-warning" id="btnEditTitle">แก้ไข</button>
                </div>
                <textarea id="editorTitle" class="d-none"></textarea>
                <div id="titleActions" class="text-end d-none mt-2">
                    <button type="button" class="btn btn-success" id="btnSaveTitle">บันทึก</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelTitle">ยกเลิก</button>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="soft-card h-100">
                        <h5 class="fw-bold mb-3">ข้อมูลการติดต่อ</h5>

                        <ul class="list-unstyled vstack gap-3 m-0">
                            <li class="d-flex gap-3">
                                <div class="icon-blob">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 22s7-7.1 7-12a7 7 0 1 0-14 0c0 4.9 7 12 7 12z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted small" id="contactAddressView">
                                        <?php echo $CONTACT['address_html']; ?></div>
                                    <textarea id="editorContactAddress" class="d-none"></textarea>
                                </div>
                            </li>

                            <li class="d-flex gap-3">
                                <div class="icon-blob">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M22 16.9v3a2 2 0 0 1-2.2 2 19.2 19.2 0 0 1-8.4-3.2 18.8 18.8 0 0 1-6-6A19.2 19.2 0 0 1 2.1 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.8.6 2.6a2 2 0 0 1-.5 2L8.5 9a16.5 16.5 0 0 0 6 6l.9-1.6a2 2 0 0 1 2-1c.8.2 1.6.4 2.5.6A2 2 0 0 1 22 16.9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted small" id="contactPhoneView">
                                        <?php echo $CONTACT['phone_html']; ?></div>
                                    <textarea id="editorContactPhone" class="d-none"></textarea>
                                </div>
                            </li>

                            <li class="d-flex gap-3">
                                <div class="icon-blob">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 0l8 6 8-6" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted small" id="contactEmailView">
                                        <?php echo $CONTACT['email_html']; ?></div>
                                    <textarea id="editorContactEmail" class="d-none"></textarea>
                                </div>
                            </li>

                            <li class="d-flex gap-3">
                                <div class="icon-blob">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-muted small" id="contactTimeView">
                                        <?php echo $CONTACT['time_html']; ?></div>
                                    <textarea id="editorContactTime" class="d-none"></textarea>
                                </div>
                            </li>
                        </ul>
                        <div id="contactActions" class="text-end d-none mt-3">
                            <button type="button" class="btn btn-success" id="btnSaveContact">บันทึก</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelContact">ยกเลิก</button>
                        </div>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-warning" id="btnEditDataContact">แก้ไข</button>
                        </div>
                    </div>

                </div>

                <!-- Map card -->
                <div class="col-12 col-lg-6">
                    <div class="soft-card h-100">
                        <h5 class="fw-bold mb-3">แผนที่</h5>
                        <div class="map-box rounded p-0" style="height:260px;" id="mapView">
                            <iframe id="mapFrame"
                                src="https://www.google.com/maps?q=<?php echo $MAP['lat'] . ',' . $MAP['lng']; ?>&z=<?php echo (int)$MAP['zoom']; ?>&output=embed"
                                width="100%" height="100%" style="border:0" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
                            </iframe>
                        </div>
                        <div class="text-muted small mt-2" id="mapAddrView"><?php echo $MAP['address_html']; ?></div>
                        <div id="mapEditors" class="d-none mt-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnPickOnMap">
                                        เลือกบนแผนที่
                                    </button><br>
                                    <small class="text-muted ms-2">คลิกบนแผนที่เพื่อวางหมุด ระบบจะกรอก
                                        Latitude/Longitude ให้อัตโนมัติ</small>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Latitude</label>
                                    <input type="text" class="form-control form-control-sm" id="mapLatInput"
                                        value="<?php echo $MAP['lat']; ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Longitude</label>
                                    <input type="text" class="form-control form-control-sm" id="mapLngInput"
                                        value="<?php echo $MAP['lng']; ?>">
                                </div>
                                <input type="hidden" id="mapZoomInput" value="18">
                                <div class="col-12">
                                    <label class="form-label small mb-1">ที่อยู่/คำอธิบายใต้แผนที่</label>
                                    <textarea id="editorMapAddr" class="d-none"></textarea>

                                </div>
                            </div>
                        </div>

                        <div class="text-end d-none mt-3" id="mapActions">
                            <button type="button" class="btn btn-success" id="btnSaveMap">บันทึก</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelMap">ยกเลิก</button>
                        </div>
                        <div class="text-end mt-4" id="mapEditWrap">
                            <button type="button" class="btn btn-warning" id="btnEditMap">แก้ไข</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="soft-card h-100">
                        <h5 class="fw-bold mb-3">ช่องทางการติดต่ออื่นๆ</h5>

                        <div id="channelsView">
                            <div class="pill-item bg-line">
                                <div class="text-muted small" id="lineView"><?php echo $CHANNELS['line_html']; ?></div>
                            </div>
                            <div class="pill-item bg-wa">
                                <div class="text-muted small" id="whatsappView">
                                    <?php echo $CHANNELS['whatsapp_html']; ?></div>
                            </div>

                            <div class="pill-item bg-fb mb-0">
                                <div class="text-muted small" id="facebookView">
                                    <?php echo $CHANNELS['facebook_html']; ?></div>
                            </div>
                        </div>

                        <textarea id="editorLine" class="d-none"></textarea><br>
                        <textarea id="editorWhatsapp" class="d-none"></textarea><br>
                        <textarea id="editorFacebook" class="d-none"></textarea>

                        <div id="channelsActions" class="text-end d-none mt-3">
                            <button type="button" class="btn btn-success" id="btnSaveChannels">บันทึก</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelChannels">ยกเลิก</button>
                        </div>

                        <div class="text-end mt-4" id="channelsEditWrap">
                            <button type="button" class="btn btn-warning" id="btnEditContactEach">แก้ไข</button>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="col-12 col-lg-6">
                    <div class="soft-card h-100">
                        <h5 class="fw-bold mb-3">บริการของเรา</h5>

                        <ul class="list-unstyled m-0 vstack gap-2" id="servicesView">
                            <li class="service-chip">
                                <span class="dot"></span>
                                <span id="service1View"><?php echo $SERVICES['item1_html']; ?></span>
                            </li>
                            <li class="service-chip">
                                <span class="dot"></span>
                                <span id="service2View"><?php echo $SERVICES['item2_html']; ?></span>
                            </li>
                            <li class="service-chip">
                                <span class="dot"></span>
                                <span id="service3View"><?php echo $SERVICES['item3_html']; ?></span>
                            </li>
                            <li class="service-chip mb-0">
                                <span class="dot"></span>
                                <span id="service4View"><?php echo $SERVICES['item4_html']; ?></span>
                            </li>
                        </ul>
                        <textarea id="editorService1" class="d-none"></textarea><br>
                        <textarea id="editorService2" class="d-none"></textarea><br>
                        <textarea id="editorService3" class="d-none"></textarea><br>
                        <textarea id="editorService4" class="d-none"></textarea>
                        <div id="servicesActions" class="text-end d-none mt-3">
                            <button type="button" class="btn btn-success" id="btnSaveServices">บันทึก</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelServices">ยกเลิก</button>
                        </div>
                        <div class="text-end mt-4" id="servicesEditWrap">
                            <button type="button" class="btn btn-warning" id="btnEditService">แก้ไข</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="soft-card">
                <h5 class="fw-bold mb-3 text-center">คำถามที่พบบ่อย</h5>
                <div class="row g-4" id="faqView">
                    <div class="col-12 col-lg-6">
                        <div class="faq-item">
                            <div class="fw-semibold mb-1" id="faq1QView"><?php echo $FAQ['q1_html']; ?></div>
                            <div class="text-muted small" id="faq1AView"><?php echo $FAQ['a1_html']; ?></div>
                        </div>
                        <div class="faq-item mb-0">
                            <div class="fw-semibold mb-1" id="faq2QView"><?php echo $FAQ['q2_html']; ?></div>
                            <div class="text-muted small" id="faq2AView"><?php echo $FAQ['a2_html']; ?></div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="faq-item">
                            <div class="fw-semibold mb-1" id="faq3QView"><?php echo $FAQ['q3_html']; ?></div>
                            <div class="text-muted small" id="faq3AView"><?php echo $FAQ['a3_html']; ?></div>
                        </div>
                        <div class="faq-item mb-0">
                            <div class="fw-semibold mb-1" id="faq4QView"><?php echo $FAQ['q4_html']; ?></div>
                            <div class="text-muted small" id="faq4AView"><?php echo $FAQ['a4_html']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-4" id="faqView">
                    <div class="col-12 col-lg-6">
                        <p id="editTextFaqQ1" class="d-none text-FAQ">คำถามชุดที่ 1</p>
                        <textarea id="editorFaq1Q" class="d-none"></textarea><br>
                        <p id="editTextFaqA1" class="d-none text-FAQ">คำตอบชุดที่ 1</p>
                        <textarea id="editorFaq1A" class="d-none"></textarea>
                    </div>
                    <div class="col-12 col-lg-6">
                        <p id="editTextFaqQ3" class="d-none text-FAQ">คำถามชุดที่ 2</p>
                        <textarea id="editorFaq3Q" class="d-none"></textarea><br>
                        <p id="editTextFaqA3" class="d-none text-FAQ">คำตอบชุดที่ 2</p>
                        <textarea id="editorFaq3A" class="d-none"></textarea>
                    </div>
                </div>
                <br>
                <div class="row g-4" id="faqView">
                    <div class="col-12 col-lg-6">
                        <p id="editTextFaqQ2" class="d-none text-FAQ">คำถามชุดที่ 3</p>
                        <textarea id="editorFaq2Q" class="d-none"></textarea><br>
                        <p id="editTextFaqA2" class="d-none text-FAQ">คำตอบชุดที่ 3</p>
                        <textarea id="editorFaq2A" class="d-none"></textarea>
                    </div>
                    <div class="col-12 col-lg-6">
                        <p id="editTextFaqQ4" class="d-none text-FAQ">คำถามชุดที่ 4</p>
                        <textarea id="editorFaq4Q" class="d-none"></textarea><br>
                        <p id="editTextFaqA4" class="d-none text-FAQ">คำตอบชุดที่ 4</p>
                        <textarea id="editorFaq4A" class="d-none"></textarea>
                    </div>
                </div>

                <div class="text-end d-none mt-3" id="faqActions">
                    <button type="button" class="btn btn-success" id="btnSaveFAQ">บันทึก</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelFAQ">ยกเลิก</button>
                </div>
                <div class="text-end mt-4" id="faqEditWrap">
                    <button type="button" class="btn btn-warning" id="btnEditFAQ">แก้ไข</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer" id="contact">
        <div class="container">
            <div class="row gy-4 align-items-start footer-top">
                <div class="col-12 col-lg-4 fcol">
                    <div id="footerTitleContent"><?php echo $FOOTER_TITLE_HTML ?></div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2 fcol">
                    <h4 class="mb-3" id="footerContactTitle"><?php echo e($F['title']) ?></h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true" id="iconContact">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M22 16.9v3a2 2 0 0 1-2.2 2 19.2 19.2 0 0 1-8.4-3.2 18.8 18.8 0 0 1-6-6A19.2 19.2 0 0 1 2.1 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.8.6 2.6a2 2 0 0 1-.5 2L8.5 9a16.5 16.5 0 0 0 6 6l.9-1.6a2 2 0 0 1 2-1c.8.2 1.6.4 2.5.6A2 2 0 0 1 22 16.9z" />
                                </svg>
                            </span>
                            <span
                                id="footerPhones"><?php echo e($F['number1']) ?><br><?php echo e($F['number2']) ?></span>
                        </li>
                        <li>
                            <span class="ficon" aria-hidden="true" id="iconEmail">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 0l8 6 8-6" />
                                </svg>
                            </span>
                            <span id="footerEmail"><?php echo e($F['email']) ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 fcol">
                    <h4 class="mb-3" id="footerAddrTitle"><?php echo e($ADDR['title']) ?></h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true" id="iconAddr">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 22s7-7.1 7-12a7 7 0 1 0-14 0c0 4.9 7 12 7 12z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </span>
                            <span id="footerAddrText"><?php echo nl2br(e($ADDR['address'])) ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-lg-3 fcol">
                    <h4 class="mb-3" id="footerHoursTitle"><?php echo e($HOURS['title']) ?></h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true" id="iconHours">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <span id="footerHoursText"><?php echo nl2br(e($HOURS['hours'])) ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="f-divider">

            <div class="footer-bottom d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
                <div class="copy" id="footerCopy"><?php echo e($FBOT['copy_text']) ?></div>
                <ul class="f-links list-inline m-0" id="footerLinks">
                    <li class="list-inline-item"><a href="#"><?php echo e($FBOT['link1']) ?></a></li>
                    <li class="list-inline-item"><a href="#"><?php echo e($FBOT['link2']) ?></a></li>
                    <li class="list-inline-item"><a href="#"><?php echo e($FBOT['link3']) ?></a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Modal: Map Picker -->
    <div class="modal fade" id="mapPickerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">เลือกตำแหน่งบนแผนที่</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div id="mapPicker" style="height: 420px; border-radius: 12px; overflow: hidden;"></div>
                    <div class="d-flex align-items-center gap-3 mt-3 small">
                        <div>Latitude: <span id="pickedLat">-</span></div>
                        <div>Longitude: <span id="pickedLng">-</span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnUsePicked" type="button" class="btn btn-primary">ใช้พิกัดนี้</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
    // Title
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_TITLE = 'api_contact_title_save.php';

        let snapshotTitle = <?php echo json_encode($TITLE_HTML, JSON_UNESCAPED_UNICODE); ?>;

        function initTitleEditor(html) {
            $('#editorTitle')
                .removeClass('d-none')
                .summernote({
                    height: 220,
                    focus: true,
                    dialogsInBody: true,
                    placeholder: 'แก้ไขส่วนหัวและคำอธิบาย...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                        'Courier New', 'Helvetica'
                    ],
                    fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                    fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
                })
                .summernote('code', html || '');
        }

        function destroyTitleEditor() {
            if ($('#editorTitle').next('.note-editor').length) {
                $('#editorTitle').summernote('destroy');
            }
            $('#editorTitle').addClass('d-none');
        }

        $('#btnEditTitle').on('click', function() {
            $('#btnEditTitle').closest('div').addClass('d-none');
            $('#titleView').addClass('d-none');
            initTitleEditor(snapshotTitle);
            $('#titleActions').removeClass('d-none');
        });

        $('#btnCancelTitle').on('click', function() {
            $('#editorTitle').summernote('reset');
            destroyTitleEditor();
            $('#titleActions').addClass('d-none');
            $('#titleView').html(snapshotTitle).removeClass('d-none');
            $('#btnEditTitle').closest('div').removeClass('d-none');
        });

        $('#btnSaveTitle').on('click', function() {
            const html = $('#editorTitle').summernote('code');

            $('#btnSaveTitle').prop('disabled', true).text('กำลังบันทึก...');

            $.post(SAVE_URL_TITLE, {
                    action: 'save_title',
                    html: html,
                    csrf: CSRF
                }, function(resp) {
                    if (resp && resp.ok) {
                        snapshotTitle = html;
                        $('#titleView').html(snapshotTitle);
                        destroyTitleEditor();
                        $('#titleActions').addClass('d-none');
                        $('#titleView').removeClass('d-none');
                        $('#btnEditTitle').closest('div').removeClass('d-none');
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(function() {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                })
                .always(function() {
                    $('#btnSaveTitle').prop('disabled', false).text('บันทึก');
                });
        });
    });

    // Card1
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_CONTACT = 'api_contact_card_save.php';

        let snapshotContact = {
            address_html: <?php echo json_encode($CONTACT['address_html'], JSON_UNESCAPED_UNICODE); ?>,
            phone_html: <?php echo json_encode($CONTACT['phone_html'],   JSON_UNESCAPED_UNICODE); ?>,
            email_html: <?php echo json_encode($CONTACT['email_html'],   JSON_UNESCAPED_UNICODE); ?>,
            time_html: <?php echo json_encode($CONTACT['time_html'],    JSON_UNESCAPED_UNICODE); ?>
        };

        function initContactEditors(data) {
            $('#contactAddressView,#contactPhoneView,#contactEmailView,#contactTimeView').addClass('d-none');
            $('#contactActions').removeClass('d-none');
            $('#btnEditDataContact').closest('div').addClass('d-none');

            $('#editorContactAddress').removeClass('d-none').summernote({
                height: 120,
                dialogsInBody: true,
                placeholder: 'แก้ไขที่อยู่...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            }).summernote('code', data.address_html || '');

            $('#editorContactPhone').removeClass('d-none').summernote({
                height: 100,
                dialogsInBody: true,
                placeholder: 'แก้ไขโทรศัพท์...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            }).summernote('code', data.phone_html || '');

            $('#editorContactEmail').removeClass('d-none').summernote({
                height: 100,
                dialogsInBody: true,
                placeholder: 'แก้ไขอีเมล...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            }).summernote('code', data.email_html || '');

            $('#editorContactTime').removeClass('d-none').summernote({
                height: 100,
                dialogsInBody: true,
                placeholder: 'แก้ไขเวลาทำการ...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            }).summernote('code', data.time_html || '');
        }

        function destroyContactEditors() {
            ['#editorContactAddress', '#editorContactPhone', '#editorContactEmail', '#editorContactTime']
            .forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('destroy');
                $(sel).addClass('d-none');
            });
            $('#contactActions').addClass('d-none');
            $('#btnEditDataContact').closest('div').removeClass('d-none');
            $('#contactAddressView,#contactPhoneView,#contactEmailView,#contactTimeView').removeClass('d-none');
        }

        $('#btnEditDataContact').on('click', function() {
            initContactEditors(snapshotContact);
        });
        $('#btnCancelContact').on('click', function() {
            ['#editorContactAddress', '#editorContactPhone', '#editorContactEmail',
                '#editorContactTime'
            ]
            .forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('reset');
            });
            destroyContactEditors();
            $('#contactAddressView').html(snapshotContact.address_html);
            $('#contactPhoneView').html(snapshotContact.phone_html);
            $('#contactEmailView').html(snapshotContact.email_html);
            $('#contactTimeView').html(snapshotContact.time_html);
        });
        $('#btnSaveContact').on('click', function() {
            const payload = {
                csrf: CSRF,
                address_html: $('#editorContactAddress').summernote('code'),
                phone_html: $('#editorContactPhone').summernote('code'),
                email_html: $('#editorContactEmail').summernote('code'),
                time_html: $('#editorContactTime').summernote('code')
            };
            $('#btnSaveContact').prop('disabled', true).text('กำลังบันทึก...');
            $.post(SAVE_URL_CONTACT, payload, function(resp) {
                    if (resp && resp.ok) {
                        snapshotContact = {
                            address_html: payload.address_html,
                            phone_html: payload.phone_html,
                            email_html: payload.email_html,
                            time_html: payload.time_html
                        };
                        $('#contactAddressView').html(snapshotContact.address_html);
                        $('#contactPhoneView').html(snapshotContact.phone_html);
                        $('#contactEmailView').html(snapshotContact.email_html);
                        $('#contactTimeView').html(snapshotContact.time_html);

                        destroyContactEditors();
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'))
                .always(() => $('#btnSaveContact').prop('disabled', false).text('บันทึก'));
        });
    });

    // Other channels
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_CHANNELS = 'api_contact_channels_save.php';

        let snapshotChannels = {
            line_html: <?php echo json_encode($CHANNELS['line_html'], JSON_UNESCAPED_UNICODE); ?>,
            whatsapp_html: <?php echo json_encode($CHANNELS['whatsapp_html'], JSON_UNESCAPED_UNICODE); ?>,
            facebook_html: <?php echo json_encode($CHANNELS['facebook_html'], JSON_UNESCAPED_UNICODE); ?>
        };

        function showEditors(data) {
            $('#channelsView').addClass('d-none');
            $('#channelsEditWrap').addClass('d-none');
            $('#channelsActions').removeClass('d-none');

            const opts = {
                height: 110,
                dialogsInBody: true,
                placeholder: 'พิมพ์ข้อความ...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['insert', ['link']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            };

            $('#editorLine').removeClass('d-none').summernote(opts).summernote('code', data.line_html || '');
            $('#editorWhatsapp').removeClass('d-none').summernote(opts).summernote('code', data.whatsapp_html ||
                '');
            $('#editorFacebook').removeClass('d-none').summernote(opts).summernote('code', data.facebook_html ||
                '');
        }

        function destroyEditors() {
            ['#editorLine', '#editorWhatsapp', '#editorFacebook'].forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('destroy');
                $(sel).addClass('d-none');
            });
            $('#channelsView').removeClass('d-none');
            $('#channelsEditWrap').removeClass('d-none');
            $('#channelsActions').addClass('d-none');
        }

        $('#btnEditContactEach').on('click', function() {
            showEditors(snapshotChannels);
        });

        $('#btnCancelChannels').on('click', function() {
            ['#editorLine', '#editorWhatsapp', '#editorFacebook'].forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('reset');
            });
            destroyEditors();
            $('#lineView').html(snapshotChannels.line_html);
            $('#whatsappView').html(snapshotChannels.whatsapp_html);
            $('#facebookView').html(snapshotChannels.facebook_html);
        });

        $('#btnSaveChannels').on('click', function() {
            const payload = {
                csrf: CSRF,
                line_html: $('#editorLine').summernote('code'),
                whatsapp_html: $('#editorWhatsapp').summernote('code'),
                facebook_html: $('#editorFacebook').summernote('code')
            };

            $('#btnSaveChannels').prop('disabled', true).text('กำลังบันทึก...');

            $.post(SAVE_URL_CHANNELS, payload, function(resp) {
                    if (resp && resp.ok) {
                        snapshotChannels = {
                            ...payload
                        };
                        $('#lineView').html(snapshotChannels.line_html);
                        $('#whatsappView').html(snapshotChannels.whatsapp_html);
                        $('#facebookView').html(snapshotChannels.facebook_html);
                        destroyEditors();
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'))
                .always(() => $('#btnSaveChannels').prop('disabled', false).text('บันทึก'));
        });
    });

    // Services
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_SERVICES = 'api_contact_services_save.php';

        let snapshotServices = {
            item1_html: <?php echo json_encode($SERVICES['item1_html'], JSON_UNESCAPED_UNICODE); ?>,
            item2_html: <?php echo json_encode($SERVICES['item2_html'], JSON_UNESCAPED_UNICODE); ?>,
            item3_html: <?php echo json_encode($SERVICES['item3_html'], JSON_UNESCAPED_UNICODE); ?>,
            item4_html: <?php echo json_encode($SERVICES['item4_html'], JSON_UNESCAPED_UNICODE); ?>
        };

        function initServiceEditors(data) {
            $('#servicesView').addClass('d-none');
            $('#servicesEditWrap').addClass('d-none');
            $('#servicesActions').removeClass('d-none');

            const opts = {
                height: 90,
                dialogsInBody: true,
                placeholder: 'พิมพ์ข้อความ...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['insert', ['link']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            };

            $('#editorService1').removeClass('d-none').summernote(opts).summernote('code', data.item1_html ||
                '');
            $('#editorService2').removeClass('d-none').summernote(opts).summernote('code', data.item2_html ||
                '');
            $('#editorService3').removeClass('d-none').summernote(opts).summernote('code', data.item3_html ||
                '');
            $('#editorService4').removeClass('d-none').summernote(opts).summernote('code', data.item4_html ||
                '');
        }

        function destroyServiceEditors() {
            ['#editorService1', '#editorService2', '#editorService3', '#editorService4'].forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('destroy');
                $(sel).addClass('d-none');
            });
            $('#servicesView').removeClass('d-none');
            $('#servicesEditWrap').removeClass('d-none');
            $('#servicesActions').addClass('d-none');
        }

        $('#btnEditService').on('click', function() {
            initServiceEditors(snapshotServices);
        });

        $('#btnCancelServices').on('click', function() {
            ['#editorService1', '#editorService2', '#editorService3', '#editorService4'].forEach(
                sel => {
                    if ($(sel).next('.note-editor').length) $(sel).summernote('reset');
                });
            destroyServiceEditors();
            $('#service1View').html(snapshotServices.item1_html);
            $('#service2View').html(snapshotServices.item2_html);
            $('#service3View').html(snapshotServices.item3_html);
            $('#service4View').html(snapshotServices.item4_html);
        });

        $('#btnSaveServices').on('click', function() {
            const payload = {
                csrf: CSRF,
                item1_html: $('#editorService1').summernote('code'),
                item2_html: $('#editorService2').summernote('code'),
                item3_html: $('#editorService3').summernote('code'),
                item4_html: $('#editorService4').summernote('code')
            };

            $('#btnSaveServices').prop('disabled', true).text('กำลังบันทึก...');

            $.post(SAVE_URL_SERVICES, payload, function(resp) {
                    if (resp && resp.ok) {
                        snapshotServices = {
                            ...payload
                        };
                        $('#service1View').html(snapshotServices.item1_html);
                        $('#service2View').html(snapshotServices.item2_html);
                        $('#service3View').html(snapshotServices.item3_html);
                        $('#service4View').html(snapshotServices.item4_html);
                        destroyServiceEditors();
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'))
                .always(() => $('#btnSaveServices').prop('disabled', false).text('บันทึก'));
        });
    });

    // FAQ
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_FAQ = 'api_contact_faq_save.php';

        let snapshotFAQ = {
            q1_html: <?php echo json_encode($FAQ['q1_html'], JSON_UNESCAPED_UNICODE); ?>,
            a1_html: <?php echo json_encode($FAQ['a1_html'], JSON_UNESCAPED_UNICODE); ?>,
            q2_html: <?php echo json_encode($FAQ['q2_html'], JSON_UNESCAPED_UNICODE); ?>,
            a2_html: <?php echo json_encode($FAQ['a2_html'], JSON_UNESCAPED_UNICODE); ?>,
            q3_html: <?php echo json_encode($FAQ['q3_html'], JSON_UNESCAPED_UNICODE); ?>,
            a3_html: <?php echo json_encode($FAQ['a3_html'], JSON_UNESCAPED_UNICODE); ?>,
            q4_html: <?php echo json_encode($FAQ['q4_html'], JSON_UNESCAPED_UNICODE); ?>,
            a4_html: <?php echo json_encode($FAQ['a4_html'], JSON_UNESCAPED_UNICODE); ?>
        };

        function initFaqEditors(data) {
            $('#faqView').addClass('d-none');
            $('#faqEditWrap').addClass('d-none');
            $('#faqActions').removeClass('d-none');
            $('#editTextFaqQ1').removeClass('d-none');
            $('#editTextFaqQ2').removeClass('d-none');
            $('#editTextFaqQ3').removeClass('d-none');
            $('#editTextFaqQ4').removeClass('d-none');
            $('#editTextFaqA1').removeClass('d-none');
            $('#editTextFaqA2').removeClass('d-none');
            $('#editTextFaqA3').removeClass('d-none');
            $('#editTextFaqA4').removeClass('d-none');

            const qOpts = {
                height: 70,
                dialogsInBody: true,
                placeholder: 'พิมพ์คำถาม...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                    'Courier New', 'Helvetica'
                ],
                fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
            };
            const aOpts = {
                ...qOpts,
                placeholder: 'พิมพ์คำตอบ...',
                height: 100
            };

            $('#editorFaq1Q').removeClass('d-none').summernote(qOpts).summernote('code', data.q1_html || '');
            $('#editorFaq1A').removeClass('d-none').summernote(aOpts).summernote('code', data.a1_html || '');
            $('#editorFaq2Q').removeClass('d-none').summernote(qOpts).summernote('code', data.q2_html || '');
            $('#editorFaq2A').removeClass('d-none').summernote(aOpts).summernote('code', data.a2_html || '');
            $('#editorFaq3Q').removeClass('d-none').summernote(qOpts).summernote('code', data.q3_html || '');
            $('#editorFaq3A').removeClass('d-none').summernote(aOpts).summernote('code', data.a3_html || '');
            $('#editorFaq4Q').removeClass('d-none').summernote(qOpts).summernote('code', data.q4_html || '');
            $('#editorFaq4A').removeClass('d-none').summernote(aOpts).summernote('code', data.a4_html || '');

        }

        function destroyFaqEditors() {
            ['#editorFaq1Q', '#editorFaq1A', '#editorFaq2Q', '#editorFaq2A', '#editorFaq3Q', '#editorFaq3A',
                '#editorFaq4Q', '#editorFaq4A'
            ]
            .forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('destroy');
                $(sel).addClass('d-none');
            });
            $('#faqView').removeClass('d-none');
            $('#faqEditWrap').removeClass('d-none');
            $('#faqActions').addClass('d-none');
            $('#editTextFaqQ1').addClass('d-none');
            $('#editTextFaqQ2').addClass('d-none');
            $('#editTextFaqQ3').addClass('d-none');
            $('#editTextFaqQ4').addClass('d-none');
            $('#editTextFaqA1').addClass('d-none');
            $('#editTextFaqA2').addClass('d-none');
            $('#editTextFaqA3').addClass('d-none');
            $('#editTextFaqA4').addClass('d-none');
        }

        $('#btnEditFAQ').on('click', function() {
            initFaqEditors(snapshotFAQ);
        });

        $('#btnCancelFAQ').on('click', function() {
            ['#editorFaq1Q', '#editorFaq1A', '#editorFaq2Q', '#editorFaq2A', '#editorFaq3Q',
                '#editorFaq3A', '#editorFaq4Q', '#editorFaq4A'
            ]
            .forEach(sel => {
                if ($(sel).next('.note-editor').length) $(sel).summernote('reset');
            });
            destroyFaqEditors();
            $('#faq1QView').html(snapshotFAQ.q1_html);
            $('#faq1AView').html(snapshotFAQ.a1_html);
            $('#faq2QView').html(snapshotFAQ.q2_html);
            $('#faq2AView').html(snapshotFAQ.a2_html);
            $('#faq3QView').html(snapshotFAQ.q3_html);
            $('#faq3AView').html(snapshotFAQ.a3_html);
            $('#faq4QView').html(snapshotFAQ.q4_html);
            $('#faq4AView').html(snapshotFAQ.a4_html);
        });

        $('#btnSaveFAQ').on('click', function() {
            const payload = {
                csrf: CSRF,
                q1_html: $('#editorFaq1Q').summernote('code'),
                a1_html: $('#editorFaq1A').summernote('code'),
                q2_html: $('#editorFaq2Q').summernote('code'),
                a2_html: $('#editorFaq2A').summernote('code'),
                q3_html: $('#editorFaq3Q').summernote('code'),
                a3_html: $('#editorFaq3A').summernote('code'),
                q4_html: $('#editorFaq4Q').summernote('code'),
                a4_html: $('#editorFaq4A').summernote('code')
            };

            $('#btnSaveFAQ').prop('disabled', true).text('กำลังบันทึก...');

            $.post(SAVE_URL_FAQ, payload, function(resp) {
                    if (resp && resp.ok) {
                        snapshotFAQ = {
                            ...payload
                        };
                        $('#faq1QView').html(snapshotFAQ.q1_html);
                        $('#faq1AView').html(snapshotFAQ.a1_html);
                        $('#faq2QView').html(snapshotFAQ.q2_html);
                        $('#faq2AView').html(snapshotFAQ.a2_html);
                        $('#faq3QView').html(snapshotFAQ.q3_html);
                        $('#faq3AView').html(snapshotFAQ.a3_html);
                        $('#faq4QView').html(snapshotFAQ.q4_html);
                        $('#faq4AView').html(snapshotFAQ.a4_html);
                        destroyFaqEditors();
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'))
                .always(() => $('#btnSaveFAQ').prop('disabled', false).text('บันทึก'));
        });
    });

    // Map
    $(function() {
        const CSRF = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        const SAVE_URL_MAP = 'api_contact_map_save.php';
        const addrOpts = {
            height: 140,
            dialogsInBody: true,
            placeholder: 'แก้ไขที่อยู่/คำอธิบายใต้แผนที่...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ],
            fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman', 'Courier New',
                'Helvetica'
            ],
            fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
            fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32', '36']
        };

        let snapshotMap = {
            lat: <?php echo json_encode($MAP['lat']); ?>,
            lng: <?php echo json_encode($MAP['lng']); ?>,
            zoom: 18,
            address_html: <?php echo json_encode($MAP['address_html'], JSON_UNESCAPED_UNICODE); ?>
        };

        function refreshIframe() {
            const src = `https://www.google.com/maps?q=${snapshotMap.lat},${snapshotMap.lng}&z=18&output=embed`;
            $('#mapFrame').attr('src', src);
            $('#mapAddrView').html(snapshotMap.address_html);
        }

        function enterEdit() {
            $('#mapView,#mapAddrView,#mapEditWrap').addClass('d-none');
            $('#mapEditors,#mapActions').removeClass('d-none');

            $('#mapLatInput').val(snapshotMap.lat);
            $('#mapLngInput').val(snapshotMap.lng);
            $('#mapZoomInput').val(18);

            $('#editorMapAddr')
                .removeClass('d-none')
                .summernote(addrOpts)
                .summernote('code', snapshotMap.address_html || 'แผนที่แสดงตำแหน่งร้าน');
        }

        function exitEdit() {
            if ($('#editorMapAddr').next('.note-editor').length) {
                $('#editorMapAddr').summernote('destroy');
            }
            $('#editorMapAddr').addClass('d-none');

            $('#mapEditors,#mapActions').addClass('d-none');
            $('#mapView,#mapAddrView,#mapEditWrap').removeClass('d-none');
        }

        $('#btnEditMap').on('click', enterEdit);
        $('#btnCancelMap').on('click', function() {
            if ($('#editorMapAddr').next('.note-editor').length) {
                $('#editorMapAddr').summernote('reset');
            }
            refreshIframe();
            exitEdit();
        });

        let pickerMap = null;
        let pickerMarker = null;
        let pickedLatLng = null;
        let geocoderControl = null;

        function setPicked(latlng) {
            pickedLatLng = latlng;
            if (pickerMarker) {
                pickerMarker.setLatLng(latlng);
            } else {
                pickerMarker = L.marker(latlng, {
                    draggable: true
                }).addTo(pickerMap);
                pickerMarker.on('dragend', () => setPicked(pickerMarker.getLatLng()));
            }
            $('#pickedLat').text(latlng.lat.toFixed(6));
            $('#pickedLng').text(latlng.lng.toFixed(6));
        }

        $('#btnPickOnMap').on('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('mapPickerModal'));
            modal.show();
            $('#mapPickerModal').one('shown.bs.modal', function() {
                const startLat = parseFloat($('#mapLatInput').val()) || parseFloat(snapshotMap
                    .lat);
                const startLng = parseFloat($('#mapLngInput').val()) || parseFloat(snapshotMap
                    .lng);

                if (!pickerMap) {
                    pickerMap = L.map('mapPicker').setView([startLat, startLng], 18);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 20,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(pickerMap);

                    geocoderControl = L.Control.geocoder({
                            defaultMarkGeocode: false,
                            placeholder: 'ค้นหาสถานที่...',
                            errorMessage: 'ไม่พบสถานที่',
                            showUniqueResult: true,
                            suggestTimeout: 250
                        })
                        .on('markgeocode', function(e) {
                            const center = e.geocode.center;
                            setPicked(center);
                            pickerMap.setView(center, 18);
                        })
                        .addTo(pickerMap);
                    pickerMap.on('click', function(e) {
                        setPicked(e.latlng);
                    });
                } else {
                    pickerMap.invalidateSize();
                    pickerMap.setView([startLat, startLng], 18);
                }
                setPicked(L.latLng(startLat, startLng));
            });
        });

        $('#btnUsePicked').on('click', function() {
            if (pickedLatLng) {
                $('#mapLatInput').val(pickedLatLng.lat.toFixed(7));
                $('#mapLngInput').val(pickedLatLng.lng.toFixed(7));
            }
            const modalEl = document.getElementById('mapPickerModal');
            const modalObj = bootstrap.Modal.getInstance(modalEl);
            modalObj?.hide();
        });

        $('#btnSaveMap').on('click', function() {
            let lat = $('#mapLatInput').val().trim();
            let lng = $('#mapLngInput').val().trim();

            if (lat === '' || lng === '') {
                if (pickedLatLng) {
                    lat = pickedLatLng.lat;
                    lng = pickedLatLng.lng;
                    $('#mapLatInput').val(lat);
                    $('#mapLngInput').val(lng);
                }
            }

            const fLat = parseFloat(lat);
            const fLng = parseFloat(lng);
            if (isNaN(fLat) || isNaN(fLng) || fLat < -90 || fLat > 90 || fLng < -180 || fLng > 180) {
                alert('กรุณาเลือก/กรอกพิกัดให้ถูกต้อง');
                return;
            }

            const addr = $('#editorMapAddr').summernote('code');
            $('#btnSaveMap').prop('disabled', true).text('กำลังบันทึก...');
            $.post(
                    SAVE_URL_MAP, {
                        csrf: CSRF,
                        lat: fLat,
                        lng: fLng,
                        zoom: 18,
                        address_html: addr
                    },
                    function(resp) {
                        if (resp && resp.ok) {
                            snapshotMap = {
                                lat: fLat,
                                lng: fLng,
                                zoom: 18,
                                address_html: addr
                            };
                            refreshIframe();
                            exitEdit();
                        } else {
                            alert((resp && resp.msg) ? resp.msg : 'บันทึกไม่สำเร็จ');
                        }
                    },
                    'json'
                )
                .fail(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'))
                .always(() => $('#btnSaveMap').prop('disabled', false).text('บันทึก'));
        });
        refreshIframe();
    });
    </script>

</body>

</html>