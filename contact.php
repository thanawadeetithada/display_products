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

    p {
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg border-bottom sticky-top">
        <div class="container">
            <div>
                <a class="navbar-brand" id="brandLink" href="index2.php"><?php echo $H['name'] ?? 'LUMA AIR' ?></a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="สลับเมนู">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="article.php">บทความ</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">ติดต่อเรา</a></li>
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
                    </div>
                </div>
            </div>

            <div class="soft-card">
                <h5 class="fw-bold mb-3 text-center">คำถามที่พบบ่อย</h5>
                <div class="row g-4" id="faqView">
                    <div class="col-12 col-lg-6">
                        <div class="faq-item mt-4 mb-2">
                            <div class="fw-semibold mb-1" id="faq1QView"><?php echo $FAQ['q1_html']; ?></div>
                            <div class="text-muted small" id="faq1AView"><?php echo $FAQ['a1_html']; ?></div>
                        </div>
                        <div class="faq-item mt-4 mb-2">
                            <div class="fw-semibold mb-1" id="faq2QView"><?php echo $FAQ['q2_html']; ?></div>
                            <div class="text-muted small" id="faq2AView"><?php echo $FAQ['a2_html']; ?></div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="faq-item mt-4 mb-2">
                            <div class="fw-semibold mb-1" id="faq3QView"><?php echo $FAQ['q3_html']; ?></div>
                            <div class="text-muted small" id="faq3AView"><?php echo $FAQ['a3_html']; ?></div>
                        </div>
                        <div class="faq-item mt-4 mb-2">
                            <div class="fw-semibold mb-1" id="faq4QView"><?php echo $FAQ['q4_html']; ?></div>
                            <div class="text-muted small" id="faq4AView"><?php echo $FAQ['a4_html']; ?></div>
                        </div>
                    </div>
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
</body>

</html>