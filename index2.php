<?php
require_once __DIR__ . '/db.php';
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// header
$H = [];
$res = $conn->query("SELECT name FROM site_header WHERE id=1");
if ($row = $res->fetch_assoc()) { $H = $row; }

// footer
$F = [];
$res2 = $conn->query("SELECT * FROM site_footer WHERE id=1");
if ($row2 = $res2->fetch_assoc()) { $F = $row2; }

// editorTitle
$TITLE_HTML = '';
$resT = $conn->query("SELECT html FROM site_title_home WHERE id=1");
if ($rowT = $resT->fetch_assoc()) { $TITLE_HTML = $rowT['html']; }
if ($TITLE_HTML === '') {
  $TITLE_HTML = '<span class="tag">⭐ อากาศบริสุทธิ์ คุณภาพพรีเมียม</span>
<h2>หายใจ <span class="blue">อากาศบริสุทธิ์</span><br />ทุกลมหายใจ</h2>
<p>
  เครื่องแลกเปลี่ยนอากาศ <b>LUMA AIR ERV</b> ด้วยเทคโนโลยีขั้นสูง
  ดูดอากาศเก่า กรองและแทนที่ด้วยอากาศบริสุทธิ์
  <span class="blue">ไม่ต้องเปิดหน้าต่าง</span>
</p>';
}

// Pic slides
$P = ['img1'=>'img/pic1.jpg','img2'=>'img/pic2.jpg','img3'=>'img/pic3.jpg'];
$resP = $conn->query("SELECT img1,img2,img3 FROM site_pic_slides WHERE id=1");
if ($rowP = $resP->fetch_assoc()) {
  $P = array_merge($P, array_filter($rowP, fn($v) => $v !== null && $v !== ''));
}

// featTitle
$FEAT_HTML = '';
$resF = $conn->query("SELECT html FROM site_feat_title WHERE id=1");
if ($rowF = $resF->fetch_assoc()) { $FEAT_HTML = $rowF['html']; }
if ($FEAT_HTML === '') {
  $FEAT_HTML = '<h2 class="feat-title"><span class="blue">LUMA AIR ERV</span></h2>
<p class="feat-sub">Energy Recovery Ventilation</p>
<p class="feat-desc">
  ระบบระบายอากาศที่ช่วยแลกเปลี่ยนความร้อนและความชื้นระหว่างอากาศภายในและภายนอกอาคาร
  พร้อมกรองอากาศให้บริสุทธิ์
</p>';
}

$FEAT_PARTS = [
  'feat1_title'   => 'วิงเวียนศีรษะ และง่วงนอนขณะทำงาน',
  'feat1_content' => 'อาจเกิดจากระดับ CO2 ที่สูงและคุณภาพอากาศภายในอาคารที่ไม่ดี',
  'feat2_title'   => 'กลิ่นอับ เชื้อรา และแบคทีเรีย',
  'feat2_content' => 'ในบ้านระบบปิด การระบายอากาศที่จำกัดทำให้ความชื้นสะสมได้ง่าย',
  'feat3_title'   => 'สารฟอร์มาลดีไฮด์ และ VOCs',
  'feat3_content' => 'เฟอร์นิเจอร์บิวท์อินอาจปล่อยสารระเหยอันตรายที่มองไม่เห็น',
];

$resP = $conn->query("SELECT `key`, html FROM site_feat_parts WHERE `key` IN (
  'feat1_title','feat1_content','feat2_title','feat2_content','feat3_title','feat3_content'
)");
while ($row = $resP->fetch_assoc()) {
  $FEAT_PARTS[$row['key']] = $row['html'];
}

// productTitle
$PROD_HTML = '';
$resPT = $conn->query("SELECT html FROM site_product_title WHERE id=1");
if ($rowPT = $resPT->fetch_assoc()) { $PROD_HTML = $rowPT['html']; }
if ($PROD_HTML === '') {
  $PROD_HTML = '<h2>ผลิตภัณฑ์คุณภาพระดับโลก</h2>
<p class="products-sub">เทคโนโลยีขั้นสูงจากแบรนด์ชั้นนำ ผ่านการรับรองคุณภาพมาตรฐานสากล</p>';
}

// ERV section (4 ส่วน)
$ERV = [
  'pic1' => 'img/pic1.jpg',
  'benefits_html' => '',
  'tech_html' => '',
  'pic2' => 'img/pic2.jpg',
];
$resERV = $conn->query("SELECT pic1, benefits_html, tech_html, pic2 FROM site_erv WHERE id=1");
if ($row = $resERV->fetch_assoc()) {
  $ERV = array_merge($ERV, $row);
}

// Benefits
$BENEFITS = [
  'benefit1' => '<h3>เพิ่ม O2 ลด CO2</h3><p>แลกเปลี่ยนอากาศเก่าให้เป็นอากาศใหม่ที่มีออกซิเจนสูง</p>',
  'benefit2' => '<h3>กรอง PM2.5</h3><p>กรองฝุ่นละออง PM2.5 และอนุภาคไม่พึงประสงค์ก่อนเข้าห้อง</p>',
  'benefit3' => '<h3>กำจัดสารฟอร์มาลดีไฮด์</h3><p>กรองสารเคมีจากเฟอร์นิเจอร์และวัสดุก่อสร้างต่างๆ</p>',
  'benefit4' => '<h3>แลกเปลี่ยนความร้อน</h3><p>รักษาอุณหภูมิห้องให้เหมาะสม ประหยัดพลังงาน</p>',
];
$resB = $conn->query("SELECT `key`, html FROM site_benefits WHERE `key` IN ('benefit1','benefit2','benefit3','benefit4')");
while ($row = $resB->fetch_assoc()) {
  $BENEFITS[$row['key']] = $row['html'];
}

// Audiences text
$AUD_HTML = '';
$resAud = $conn->query("SELECT html FROM site_text_audiences WHERE id=1");
if ($rowAud = $resAud->fetch_assoc()) { $AUD_HTML = $rowAud['html']; }
if ($AUD_HTML === '') {
  $AUD_HTML = '<h2>🏡 เหมาะสำหรับทุกประเภทอาคาร</h2>
  <p class="aud-sub">LUMA AIR ERV สามารถติดตั้งได้กับอาคารและที่พักอาศัยทุกประเภท</p>';
}

// Audiences 4 ชุด
$AUD_ITEMS = [
  'aud1' => '<h3>บ้านเดี่ยว</h3><p>เหมาะสำหรับการติดตั้งในห้องต่างๆ</p>',
  'aud2' => '<h3>ทาวน์เฮาส์</h3><p>แก้ปัญหาอากาศไม่ถ่ายเทในพื้นที่จำกัด</p>',
  'aud3' => '<h3>คอนโดมิเนียม</h3><p>เพิ่มคุณภาพอากาศในพื้นที่ขนาดกะทัดรัด</p>',
  'aud4' => '<h3>อาคารพาณิชย์</h3><p>สร้างสภาพแวดล้อมทำงานที่ดี</p>',
];

$resAud4 = $conn->query("SELECT `key`, html FROM site_audiences WHERE `key` IN ('aud1','aud2','aud3','aud4')");
while ($row = $resAud4->fetch_assoc()) {
  $AUD_ITEMS[$row['key']] = $row['html'];
}

// CSRF
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
$csrf = $_SESSION['csrf'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LUMA AIR - ERV System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php $cssv = file_exists('style2.css') ? md5_file('style2.css') : time(); ?>
    <link rel="stylesheet" href="style2.css?v=<?= $cssv ?>">
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

    .navbar-nav .nav-link:hover {
        background: #eaf6fb;
        color: var(--primary);
    }

    .note-editable {
        background: #fff;
    }

    .btn-bluenext {
        border: 2px solid #1ea0e6;
        color: #fff;
        padding: 10px 16px;
        background: #1ea0e6;
        display: inline-block;
        text-decoration: none;
        font-weight: 600;
        border-radius: 14px;
        transition: .2s ease;
    }

    @media (max-width:575px) {
        .navbar {
            padding: 8px 20px;
        }
    }

    .hero-img .carousel,
    .hero-img .carousel-inner,
    .hero-img .carousel-item {
        border-radius: 18px;
        overflow: hidden;
    }

    .hero-img .carousel-item {
        aspect-ratio: 4/3;
    }

    .hero-img .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 20%;
    }

    @media (max-width: 600px) {
        .hero-img .carousel-item {
            aspect-ratio: 16 / 11;
        }
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light border-bottom sticky-top">
        <div class="container">
            <div>
                <a class="navbar-brand" id="brandLink" href="index2.php"><?= $H['name'] ?? 'LUMA AIR' ?></a>
                <button type="button" class="btn btn-warning ms-2" id="btnEditLogo">แก้ไข</button>
            </div>

            <div id="logoEditorWrap" class="ms-2 d-none">
                <textarea id="editorLogo"></textarea>

                <div id="logoActions" class="d-none mt-2 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-success" id="btnSaveLogo">บันทึก</button>
                    <button type="button" class="btn btn-outline-secondary" id="btnCancelLogo">ยกเลิก</button>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="สลับเมนู">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index2.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="article.php">บทความ</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="container hero-flex">
            <div class="hero-text">
                <div id="heroContent">
                    <?= $TITLE_HTML ?>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-warning" id="btnEditTitle">แก้ไข</button>
                </div>

                <textarea id="editorTitle" class="d-none"></textarea>
                <div id="titleActions" class="text-end d-none mt-2">
                    <button type="button" class="btn btn-success" id="btnSaveTitle">บันทึก</button>
                    <button type="button" class="btn btn-outline-secondary" id="btnCancelTitle">ยกเลิก</button>
                </div>

                <div class="hero-btns">
                    <a class="btn-bluenext" href="#products">ดูรายละเอียดผลิตภัณฑ์</a>
                    <a class="btn-outline" href="#contact">ปรึกษาฟรี</a>
                </div>
            </div>

            <div class="hero-img">
                <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel"
                    data-bs-interval="3000" data-bs-pause="false">

                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="สไลด์ 1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"
                            aria-label="สไลด์ 2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"
                            aria-label="สไลด์ 3"></button>
                    </div>

                    <div class="carousel-inner rounded-3 shadow-sm">
                        <div class="carousel-item active" data-bs-interval="3000">
                            <img id="slideImg1" src="<?= e($P['img1']) ?>" class="d-block w-100" alt="ภาพที่ 1"
                                loading="lazy">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img id="slideImg2" src="<?= e($P['img2']) ?>" class="d-block w-100" alt="ภาพที่ 2"
                                loading="lazy">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img id="slideImg3" src="<?= e($P['img3']) ?>" class="d-block w-100" alt="ภาพที่ 3"
                                loading="lazy">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="prev" aria-label="ก่อนหน้า">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="next" aria-label="ถัดไป">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
                <br>
                <div class="text-end">
                    <button type="button" class="btn btn-warning" id="btnPicSlide">แก้ไขรูปภาพ</button>
                </div>
            </div>

        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <div id="featContent">
                <?= $FEAT_HTML ?>
            </div>

            <div class="text-end mt-2">
                <button type="button" class="btn btn-warning" id="btnEditFeatTitle">แก้ไข</button>
            </div>

            <textarea id="editorFeatTitle" class="d-none"></textarea>
            <div id="featActions" class="text-end d-none mt-2">
                <button type="button" class="btn btn-success" id="btnSaveFeatTitle">บันทึก</button>
                <button type="button" class="btn btn-outline-secondary" id="btnCancelFeatTitle">ยกเลิก</button>
            </div>
            <br>
            <div class="feature-list">
                <details class="feature-card red" open>
                    <summary>
                        <span class="i">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3l10 18H2L12 3z"></path>
                                <rect x="11" y="9" width="2" height="6" rx="1"></rect>
                                <rect x="11" y="16.5" width="2" height="2" rx="1"></rect>
                            </svg>
                        </span>
                        <span class="t">
                            <span id="f1TitleText"><?= e(strip_tags($FEAT_PARTS['feat1_title'])) ?></span>
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF1Title">แก้ไขหัวข้อ</button>
                        </span>
                    </summary>

                    <div id="f1TitleEditorWrap" class="d-none mt-2">
                        <textarea id="editorF1Title"></textarea>
                        <div id="f1TitleActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success" id="btnSaveF1Title">บันทึกหัวข้อ</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF1Title">ยกเลิกหัวข้อ</button>
                        </div>
                    </div>

                    <div class="content">
                        <span id="f1ContentText"><?= $FEAT_PARTS['feat1_content'] ?></span>
                        <div class="mt-2">
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF1Content">แก้ไขรายละเอียด</button>
                        </div>
                    </div>

                    <div id="f1ContentEditorWrap" class="d-none mt-2">
                        <textarea id="editorF1Content"></textarea>
                        <div id="f1ContentActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success"
                                id="btnSaveF1Content">บันทึกรายละเอียด</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF1Content">ยกเลิกรายละเอียด</button>
                        </div>
                    </div>
                </details>

                <details class="feature-card orange" open>
                    <summary>
                        <span class="i">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3l10 18H2L12 3z"></path>
                                <rect x="11" y="9" width="2" height="6" rx="1"></rect>
                                <rect x="11" y="16.5" width="2" height="2" rx="1"></rect>
                            </svg>
                        </span>
                        <span class="t">
                            <span id="f2TitleText"><?= e(strip_tags($FEAT_PARTS['feat2_title'])) ?></span>
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF2Title">แก้ไขหัวข้อ</button>
                        </span>
                    </summary>

                    <div id="f2TitleEditorWrap" class="d-none mt-2">
                        <textarea id="editorF2Title"></textarea>
                        <div id="f2TitleActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success" id="btnSaveF2Title">บันทึกหัวข้อ</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF2Title">ยกเลิกหัวข้อ</button>
                        </div>
                    </div>

                    <div class="content">
                        <span id="f2ContentText"><?= $FEAT_PARTS['feat2_content'] ?></span>
                        <div class="mt-2">
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF2Content">แก้ไขรายละเอียด</button>
                        </div>
                    </div>

                    <div id="f2ContentEditorWrap" class="d-none mt-2">
                        <textarea id="editorF2Content"></textarea>
                        <div id="f2ContentActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success"
                                id="btnSaveF2Content">บันทึกรายละเอียด</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF2Content">ยกเลิกรายละเอียด</button>
                        </div>
                    </div>
                </details>

                <details class="feature-card purple" open>
                    <summary>
                        <span class="i">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 3l10 18H2L12 3z"></path>
                                <rect x="11" y="9" width="2" height="6" rx="1"></rect>
                                <rect x="11" y="16.5" width="2" height="2" rx="1"></rect>
                            </svg>
                        </span>
                        <span class="t">
                            <span id="f3TitleText"><?= e(strip_tags($FEAT_PARTS['feat3_title'])) ?></span>
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF3Title">แก้ไขหัวข้อ</button>
                        </span>
                    </summary>

                    <div id="f3TitleEditorWrap" class="d-none mt-2">
                        <textarea id="editorF3Title"></textarea>
                        <div id="f3TitleActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success" id="btnSaveF3Title">บันทึกหัวข้อ</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF3Title">ยกเลิกหัวข้อ</button>
                        </div>
                    </div>

                    <div class="content">
                        <span id="f3ContentText"><?= $FEAT_PARTS['feat3_content'] ?></span>
                        <div class="mt-2">
                            <button type="button" class="btn btn-warning btn-sm"
                                id="btnEditF3Content">แก้ไขรายละเอียด</button>
                        </div>
                    </div>

                    <div id="f3ContentEditorWrap" class="d-none mt-2">
                        <textarea id="editorF3Content"></textarea>
                        <div id="f3ContentActions" class="text-end d-none mt-2">
                            <button type="button" class="btn btn-success"
                                id="btnSaveF3Content">บันทึกรายละเอียด</button>
                            <button type="button" class="btn btn-outline-secondary"
                                id="btnCancelF3Content">ยกเลิกรายละเอียด</button>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="products" id="products">
        <div class="container">
            <div id="productTitleContent">
                <?= $PROD_HTML ?>
            </div>
            <div class="text-end mt-4">
                <button type="button" class="btn btn-warning" id="btnEditProductTitle">แก้ไข</button>
            </div>
            <textarea id="editorProductTitle" class="d-none"></textarea>
            <div id="productTitleActions" class="text-end d-none mt-2">
                <button type="button" class="btn btn-success" id="btnSaveProductTitle">บันทึก</button>
                <button type="button" class="btn btn-outline-secondary" id="btnCancelProductTitle">ยกเลิก</button>
            </div>

            <div class="product-grid mt-4">
                <!-- Card 1 -->
                <article class="product-card">
                    <div class="pc-img">
                        <img src="img/pic2.jpg" alt="LUMA AIR ERV">
                    </div>
                    <div class="pc-body">
                        <h3>LUMA AIR ERV</h3>
                        <p>ระบบแลกเปลี่ยนอากาศอัจฉริยะ ประหยัดไฟ 40–60%</p>
                        <a href="#" class="btn-wide">ดูรายละเอียด</a>
                    </div>
                </article>

                <!-- Card 2 -->
                <article class="product-card">
                    <div class="pc-img">
                        <img src="img/pic1.jpg" alt="AIRWOODS ERV System">
                    </div>
                    <div class="pc-body">
                        <h3>AIRWOODS ERV System</h3>
                        <p>ระบบแลกเปลี่ยนอากาศประสิทธิภาพสูง</p>
                        <a href="#" class="btn-wide">ดูรายละเอียด</a>
                    </div>
                </article>

                <!-- Card 3 -->
                <article class="product-card">
                    <div class="pc-img">
                        <img src="img/pic2.jpg" alt="Outer Hoods & Grilles">
                    </div>
                    <div class="pc-body">
                        <h3>Outer Hoods & Grilles</h3>
                        <p>หัวระบายอากาศสำหรับติดตั้งนอกอาคาร</p>
                        <a href="#" class="btn-wide">ดูรายละเอียด</a>
                    </div>
                </article>
            </div>
            <div class="text-end mt-4">
                <button type="button" class="btn btn-warning" id="btnEditProduct" disable>แก้ไข</button>
            </div>
        </div>
    </section>

    <!-- ERV info section -->
    <section class="erv-info" id="erv-info">
        <div class="container">
            <div class="erv-row">
                <div class="erv-col">
                    <div class="img-frame lg">
                        <img id="ervPic1" src="<?= e($ERV['pic1']) ?>" alt="Family living room">
                    </div>
                </div>
                <div class="erv-col">
                    <div id="ervBenefits"><?= $ERV['benefits_html'] ?></div>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="button" class="btn btn-warning" id="btnEditERVPair1">แก้ไขส่วนที่ 1 (รูป+ข้อความ)</button>
            </div>

            <div class="erv-row second mt-4">
                <div class="erv-col">
                    <div id="ervTech"><?= $ERV['tech_html'] ?></div>
                </div>
                <div class="erv-col">
                    <div class="img-stack">
                        <div class="img-frame sm">
                            <img id="ervPic2" src="<?= e($ERV['pic2']) ?>" alt="ERV phone UI">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="button" class="btn btn-warning" id="btnEditERVPair2">แก้ไขส่วนที่ 2 (รูป+ข้อความ)</button>
            </div>
        </div>
    </section>

    <!-- Benefit -->
    <section class="benefits" id="benefits">
        <div class="container">
            <div class="benefit-grid">

                <article class="benefit-card">
                    <div class="benefit-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M3 8h10c2 0 2-3 0-3-1.2 0-1.8.6-2 1M3 12h14c2 0 2-3 0-3M3 16h8c2 0 2 3 0 3-1.2 0-1.8-.6-2-1" />
                        </svg>
                    </div>
                    <div id="benefit1Content"><?= $BENEFITS['benefit1'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditbenefit1">แก้ไข</button>
                    </div>
                    <textarea id="editorbenefit1" class="d-none"></textarea>
                    <div id="benefit1Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSavebenefit1">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnCancelbenefit1">ยกเลิก</button>
                    </div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon green">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3l7 3v6c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V6l7-3z" />
                        </svg>
                    </div>
                    <div id="benefit2Content"><?= $BENEFITS['benefit2'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditbenefit2">แก้ไข</button>
                    </div>
                    <textarea id="editorbenefit2" class="d-none"></textarea>
                    <div id="benefit2Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSavebenefit2">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnCancelbenefit2">ยกเลิก</button>
                    </div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon purple">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12l2.5 2.5L16 9" />
                        </svg>
                    </div>
                    <div id="benefit3Content"><?= $BENEFITS['benefit3'] ?></div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditbenefit3">แก้ไข</button>
                    </div>

                    <textarea id="editorbenefit3" class="d-none"></textarea>

                    <div id="benefit3Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSavebenefit3">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnCancelbenefit3">ยกเลิก</button>
                    </div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon amber">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13 2L3 14h7l-1 8 11-12h-7l1-8z" />
                        </svg>
                    </div>
                    <div id="benefit4Content"><?= $BENEFITS['benefit4'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditbenefit4">แก้ไข</button>
                    </div>
                    <textarea id="editorbenefit4" class="d-none"></textarea>
                    <div id="benefit4Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSavebenefit4">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnCancelbenefit4">ยกเลิก</button>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Suitable-->
    <section class="audiences" id="audiences">
        <div class="container">
            <div id="audiencesContent"><?= $AUD_HTML ?></div>
            <div class="text-end mt-3 mb-3">
                <button type="button" class="btn btn-warning mb-4" id="btnEditTextAudiences">แก้ไข</button>
            </div>
            <textarea id="editorTextAudiences" class="d-none"></textarea>
            <div id="TextAudiencesActions" class="text-end d-none mt-2 mb-4">
                <button type="button" class="btn btn-success" id="btnSaveTextAudiences">บันทึก</button>
                <button type="button" class="btn btn-outline-secondary" id="btnCancelTextAudiences">ยกเลิก</button>
            </div>

            <div class="aud-grid">
                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud1Content"><?= $AUD_ITEMS['aud1'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditAudiences1">แก้ไข</button>
                    </div>
                    <textarea id="editorAudiences1" class="d-none"></textarea>
                    <div id="Audiences1Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSaveAudiences1">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCancelAudiences1">ยกเลิก</button>
                    </div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud2Content"><?= $AUD_ITEMS['aud2'] ?></div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditAudiences2">แก้ไข</button>
                    </div>
                    <textarea id="editorAudiences2" class="d-none"></textarea>
                    <div id="Audiences2Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSaveAudiences2">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCancelAudiences2">ยกเลิก</button>
                    </div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud3Content"><?= $AUD_ITEMS['aud3'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditAudiences3">แก้ไข</button>
                    </div>
                    <textarea id="editorAudiences3" class="d-none"></textarea>
                    <div id="Audiences3Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSaveAudiences3">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCancelAudiences3">ยกเลิก</button>
                    </div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud4Content"><?= $AUD_ITEMS['aud4'] ?></div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-warning" id="btnEditAudiences4">แก้ไข</button>
                    </div>
                    <textarea id="editorAudiences4" class="d-none"></textarea>
                    <div id="Audiences4Actions" class="text-end d-none mt-2">
                        <button type="button" class="btn btn-success" id="btnSaveAudiences4">บันทึก</button>
                        <button type="button" class="btn btn-outline-secondary"
                            id="btnCancelAudiences4">ยกเลิก</button>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <textarea id="editorBody" class="mt-3"></textarea>
    </div>

    <!-- Footer -->
    <footer class="site-footer" id="contact">
        <div class="container">
            <div class="row gy-4 align-items-start footer-top">
                <div class="col-12 col-lg-4 fcol">
                    <h4 class="mb-3"><?= e($F['name'] ?? 'LUMA AIR') ?></h4>
                    <p class="mb-0"><?= e($F['title'] ?? '') ?></p>
                </div>

                <div class="col-12 col-sm-6 col-lg-2 fcol">
                    <h4 class="mb-3">ติดต่อเรา</h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M22 16.9v3a2 2 0 0 1-2.2 2 19.2 19.2 0 0 1-8.4-3.2 18.8 18.8 0 0 1-6-6A19.2 19.2 0 0 1 2.1 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.8.6 2.6a2 2 0 0 1-.5 2L8.5 9a16.5 16.5 0 0 0 6 6l.9-1.6a2 2 0 0 1 2-1c.8.2 1.6.4 2.5.6A2 2 0 0 1 22 16.9z" />
                                </svg>
                            </span>
                            <span><?= e($F['number1'] ?? '') ?><br><?= e($F['number2'] ?? '') ?></span>
                        </li>
                        <li>
                            <span class="ficon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 0l8 6 8-6" />
                                </svg>
                            </span>
                            <span><?= e($F['email'] ?? '') ?></span>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-sm-6 col-lg-3 fcol">
                    <h4 class="mb-3">ที่อยู่</h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 22s7-7.1 7-12a7 7 0 1 0-14 0c0 4.9 7 12 7 12z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </span>
                            <span><?= nl2br(e($F['address'] ?? '')) ?></span>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-lg-3 fcol">
                    <h4 class="mb-3">เวลาทำการ</h4>
                    <ul class="f-list">
                        <li>
                            <span class="ficon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <span><?= e($F['work_time'] ?? '') ?><br><?= e($F['weekend_time'] ?? '') ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="f-divider">

            <div class="footer-bottom d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
                <div class="copy">© <?= date('Y') ?> <?= e($F['name'] ?? 'LUMA AIR') ?>. สงวนลิขสิทธิ์ทุกประการ</div>
                <ul class="f-links list-inline m-0">
                    <li class="list-inline-item"><a href="#">ติดตั้งทั่วประเทศ</a></li>
                    <li class="list-inline-item"><a href="#">รับประกัน 2 ปี</a></li>
                    <li class="list-inline-item"><a href="#">บริการหลังการขาย</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Modal แก้ไขรูปสไลด์ -->
    <div class="modal fade" id="slideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" id="slideForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขรูปภาพสไลด์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">รูปที่ 1</label>
                            <input class="form-control" type="file" id="img1" name="img1" accept="image/*">
                            <img id="prev1" class="img-fluid mt-2 rounded border" alt="preview 1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">รูปที่ 2</label>
                            <input class="form-control" type="file" id="img2" name="img2" accept="image/*">
                            <img id="prev2" class="img-fluid mt-2 rounded border" alt="preview 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">รูปที่ 3</label>
                            <input class="form-control" type="file" id="img3" name="img3" accept="image/*">
                            <img id="prev3" class="img-fluid mt-2 rounded border" alt="preview 3">
                        </div>
                    </div>

                    <!-- ส่ง CSRF ไปกับฟอร์ม -->
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 1 -->
    <div class="modal fade" id="ervPair1Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" id="ervPair1Form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขส่วนที่ 1 (รูป + ข้อความ)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">รูปที่ 1 (ซ้ายบน)</label>
                            <input class="form-control" type="file" id="pair1PicInput" name="pic" accept="image/*">
                            <img id="pair1PicPrev" class="img-fluid mt-2 rounded border" alt="preview pic1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ข้อความที่ 1 (Benefits)</label>
                            <textarea id="pair1Html" name="html"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="which" value="pair1">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2 -->
    <div class="modal fade" id="ervPair2Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" id="ervPair2Form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขส่วนที่ 2 (รูป + ข้อความ)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">รูปที่ 2 (ขวาล่าง)</label>
                            <input class="form-control" type="file" id="pair2PicInput" name="pic" accept="image/*">
                            <img id="pair2PicPrev" class="img-fluid mt-2 rounded border" alt="preview pic2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ข้อความที่ 2 (ข้อมูลทางเทคนิค/ติดตั้ง)</label>
                            <textarea id="pair2Html" name="html"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="which" value="pair2">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let BRAND_DB = <?= json_encode($H['name'] ?? 'LUMA AIR') ?>;
    const CSRF = <?= json_encode($csrf) ?>;

    // header
    $(document).on('click', '.feature-card summary button', function(e) {
        e.stopPropagation();
    });

    $(function() {
        let inited = false;

        $('#btnEditLogo').on('click', function() {
            $('#brandLink, #btnEditLogo').addClass('d-none');
            $('#logoActions, #logoEditorWrap').removeClass('d-none');

            if (!inited) {
                $('#editorLogo').summernote({
                    height: 60,
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']]
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma',
                        'Times New Roman', 'Courier New', 'Helvetica'
                    ],
                    fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32']
                });
                inited = true;
            }
            $('#editorLogo').summernote('code', BRAND_DB).summernote('focus');
        });

        $('#btnCancelLogo').on('click', function() {
            if ($('#editorLogo').next('.note-editor').length) {
                $('#editorLogo').summernote('code', BRAND_DB);
            }
            $('#logoActions, #logoEditorWrap').addClass('d-none');
            $('#btnEditLogo, #brandLink').removeClass('d-none');
        });

        $('#btnSaveLogo').on('click', function() {
            const html = $('#editorLogo').summernote('code');
            $.ajax({
                url: 'save_header.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: CSRF,
                    name: html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $('#brandLink').html(resp.name);
                        $('#logoActions, #logoEditorWrap').addClass('d-none');
                        $('#btnEditLogo, #brandLink').removeClass('d-none');
                        BRAND_DB = resp.name;
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    // EditorTitle
    $(function() {
        let titleInited = false;

        $('#editorTitle').addClass('d-none');
        $('#titleActions').addClass('d-none');

        function showTitleEditor() {
            $('#btnEditTitle').addClass('d-none');

            if (!titleInited) {
                $('#editorTitle').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']]
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                        'Courier New', 'Helvetica'
                    ],
                    fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                    fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48']
                });
                titleInited = true;
            }

            const currentHtml = $('#heroContent').html().trim();
            $('#editorTitle').summernote('code', currentHtml);
            $('#heroContent').addClass('d-none');
            $('#editorTitle').removeClass('d-none');
            $('#editorTitle').next('.note-editor').removeClass('d-none');
            $('#titleActions').removeClass('d-none');
            $('#editorTitle').summernote('focus');
        }

        function hideTitleEditor() {
            $('#editorTitle').addClass('d-none');
            $('#editorTitle').next('.note-editor').addClass('d-none');
            $('#titleActions').addClass('d-none');
            $('#heroContent').removeClass('d-none');
            $('#btnEditTitle').removeClass('d-none');
        }

        $('#btnEditTitle').on('click', showTitleEditor);
        $('#btnCancelTitle').on('click', hideTitleEditor);

        $('#btnSaveTitle').on('click', function() {
            const html = $('#editorTitle').summernote('code');

            $.ajax({
                url: 'save_title_home.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: <?= json_encode($csrf) ?>,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $('#heroContent').html(resp.html);
                        hideTitleEditor();
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    // Pic slide
    const SLIDES = <?= json_encode($P, JSON_UNESCAPED_SLASHES) ?>;

    $(function() {
        const slideModalEl = document.getElementById('slideModal');
        if (!slideModalEl) {
            console.warn('ไม่พบ #slideModal ใน DOM');
            return;
        }

        const slideModal = bootstrap.Modal.getOrCreateInstance(slideModalEl);

        $('#btnPicSlide').on('click', function() {
            $('#prev1').attr('src', SLIDES.img1 || 'img/pic1.jpg');
            $('#prev2').attr('src', SLIDES.img2 || 'img/pic2.jpg');
            $('#prev3').attr('src', SLIDES.img3 || 'img/pic3.jpg');
            $('#img1,#img2,#img3').val('');
            slideModal.show();
        });

        function bindPreview(inputSel, imgSel) {
            $(inputSel).on('change', function() {
                const f = this.files && this.files[0];
                if (!f) return;
                const url = URL.createObjectURL(f);
                $(imgSel).attr('src', url);
            });
        }
        bindPreview('#img1', '#prev1');
        bindPreview('#img2', '#prev2');
        bindPreview('#img3', '#prev3');

        slideModalEl.addEventListener('hidden.bs.modal', () => {
            $('#slideForm')[0].reset();
            $('#prev1,#prev2,#prev3').attr('src', '');
        });

        $('#slideForm').on('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);

            $.ajax({
                url: 'save_pic_slides.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.ok) {
                        SLIDES.img1 = resp.img1;
                        SLIDES.img2 = resp.img2;
                        SLIDES.img3 = resp.img3;

                        $('#slideImg1').attr('src', SLIDES.img1);
                        $('#slideImg2').attr('src', SLIDES.img2);
                        $('#slideImg3').attr('src', SLIDES.img3);

                        slideModal.hide();
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการอัปโหลด');
                }
            });
        });
    });

    // feattitle
    $(function() {
        let featInited = false;

        $('#editorFeatTitle').addClass('d-none');
        $('#featActions').addClass('d-none');

        function showFeatEditor() {
            $('#btnEditFeatTitle').addClass('d-none');

            if (!featInited) {
                $('#editorFeatTitle').summernote({
                    height: 260,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen']]
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                        'Courier New', 'Helvetica'
                    ],
                    fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                    fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48']
                });
                featInited = true;
            }

            const current = $('#featContent').html().trim();
            $('#editorFeatTitle').summernote('code', current);

            $('#featContent').addClass('d-none');
            $('#editorFeatTitle').removeClass('d-none');
            $('#editorFeatTitle').next('.note-editor').removeClass('d-none');
            $('#featActions').removeClass('d-none');
            $('#editorFeatTitle').summernote('focus');
        }

        function hideFeatEditor(clear = false) {
            if (featInited) {
                if (clear) $('#editorFeatTitle').summernote('code', '');
                $('#editorFeatTitle').blur();
            }
            $('#editorFeatTitle').addClass('d-none');
            $('#editorFeatTitle').next('.note-editor').addClass('d-none');
            $('#featActions').addClass('d-none');
            $('#featContent').removeClass('d-none');
            $('#btnEditFeatTitle').removeClass('d-none');
        }

        $('#btnEditFeatTitle').on('click', showFeatEditor);

        $('#btnCancelFeatTitle').on('click', function() {
            hideFeatEditor(true);
        });

        $('#btnSaveFeatTitle').on('click', function() {
            const html = $('#editorFeatTitle').summernote('code');

            $.ajax({
                url: 'save_feat_title.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: <?= json_encode($csrf) ?>,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $('#featContent').html(resp.html);
                        hideFeatEditor(false);
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    const FEAT_PARTS = <?= json_encode($FEAT_PARTS, JSON_UNESCAPED_UNICODE) ?>;
    $(function() {
        function bindPartEditor(cfg) {
            const {
                key,
                $display,
                $btnEdit,
                $wrap,
                $ta,
                $actions,
                $btnSave,
                $btnCancel
            } = cfg;

            let inited = false;

            $wrap.addClass('d-none');
            $actions.addClass('d-none');

            function showEditor() {
                $display.closest('details').attr('open', true);
                $btnEdit.addClass('d-none');
                $display.addClass('d-none');

                if (!inited) {
                    $ta.summernote({
                        height: 120,
                        toolbar: [
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']]
                        ],
                        fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                            'Courier New', 'Helvetica'
                        ],
                        fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                        fontSizes: ['12', '14', '16', '18', '20', '24', '28', '32']
                    });
                    inited = true;
                }

                const current = FEAT_PARTS[key] || $display.html().trim();
                $ta.summernote('code', current);

                $wrap.removeClass('d-none');
                $actions.removeClass('d-none');
                $ta.summernote('focus');
            }

            function hideEditor(clear = false) {
                if (inited && clear) $ta.summernote('code', '');
                $wrap.addClass('d-none');
                $actions.addClass('d-none');
                $display.removeClass('d-none');
                $btnEdit.removeClass('d-none');
            }

            $btnEdit.on('click', showEditor);
            $btnCancel.on('click', () => hideEditor(true));

            $btnSave.on('click', function() {
                const html = $ta.summernote('code');
                $.ajax({
                    url: 'save_feat_part.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        csrf: CSRF,
                        key,
                        html
                    },
                    success: function(resp) {
                        if (resp && resp.ok) {
                            FEAT_PARTS[key] = resp.html;

                            if (/_title$/.test(key)) {
                                const plain = $('<div>').html(resp.html).text();
                                $display.text(plain);
                            } else {
                                $display.html(resp.html);
                            }

                            hideEditor(false);
                        } else {
                            alert(resp.error || 'บันทึกไม่สำเร็จ');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการบันทึก');
                    }
                });
            });
        }

        bindPartEditor({
            key: 'feat1_title',
            $display: $('#f1TitleText'),
            $btnEdit: $('#btnEditF1Title'),
            $wrap: $('#f1TitleEditorWrap'),
            $ta: $('#editorF1Title'),
            $actions: $('#f1TitleActions'),
            $btnSave: $('#btnSaveF1Title'),
            $btnCancel: $('#btnCancelF1Title'),
        });
        bindPartEditor({
            key: 'feat1_content',
            $display: $('#f1ContentText'),
            $btnEdit: $('#btnEditF1Content'),
            $wrap: $('#f1ContentEditorWrap'),
            $ta: $('#editorF1Content'),
            $actions: $('#f1ContentActions'),
            $btnSave: $('#btnSaveF1Content'),
            $btnCancel: $('#btnCancelF1Content'),
        });

        bindPartEditor({
            key: 'feat2_title',
            $display: $('#f2TitleText'),
            $btnEdit: $('#btnEditF2Title'),
            $wrap: $('#f2TitleEditorWrap'),
            $ta: $('#editorF2Title'),
            $actions: $('#f2TitleActions'),
            $btnSave: $('#btnSaveF2Title'),
            $btnCancel: $('#btnCancelF2Title'),
        });
        bindPartEditor({
            key: 'feat2_content',
            $display: $('#f2ContentText'),
            $btnEdit: $('#btnEditF2Content'),
            $wrap: $('#f2ContentEditorWrap'),
            $ta: $('#editorF2Content'),
            $actions: $('#f2ContentActions'),
            $btnSave: $('#btnSaveF2Content'),
            $btnCancel: $('#btnCancelF2Content'),
        });

        bindPartEditor({
            key: 'feat3_title',
            $display: $('#f3TitleText'),
            $btnEdit: $('#btnEditF3Title'),
            $wrap: $('#f3TitleEditorWrap'),
            $ta: $('#editorF3Title'),
            $actions: $('#f3TitleActions'),
            $btnSave: $('#btnSaveF3Title'),
            $btnCancel: $('#btnCancelF3Title'),
        });

        bindPartEditor({
            key: 'feat3_content',
            $display: $('#f3ContentText'),
            $btnEdit: $('#btnEditF3Content'),
            $wrap: $('#f3ContentEditorWrap'),
            $ta: $('#editorF3Content'),
            $actions: $('#f3ContentActions'),
            $btnSave: $('#btnSaveF3Content'),
            $btnCancel: $('#btnCancelF3Content'),
        });

    });

    // Product
    $(function() {
        let prodInited = false;
        $('#editorProductTitle').addClass('d-none');
        $('#productTitleActions').addClass('d-none');

        function showProdEditor() {
            $('#btnEditProductTitle').addClass('d-none');
            $('#productTitleContent').addClass('d-none');

            if (!prodInited) {
                $('#editorProductTitle').summernote({
                    height: 180,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen']]
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                        'Courier New', 'Helvetica'
                    ],
                    fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                    fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36']
                });
                prodInited = true;
            }

            const current = $('#productTitleContent').html().trim();
            $('#editorProductTitle').summernote('code', current);
            $('#editorProductTitle').removeClass('d-none');
            $('#editorProductTitle').next('.note-editor').removeClass('d-none');
            $('#productTitleActions').removeClass('d-none');
            $('#editorProductTitle').summernote('focus');
        }

        function hideProdEditor(clear = false) {
            if (prodInited && clear) {
                $('#editorProductTitle').summernote('code', '');
            }
            $('#editorProductTitle').addClass('d-none');
            $('#editorProductTitle').next('.note-editor').addClass('d-none');
            $('#productTitleActions').addClass('d-none');
            $('#productTitleContent').removeClass('d-none');
            $('#btnEditProductTitle').removeClass('d-none');
        }

        $('#btnEditProductTitle').on('click', showProdEditor);
        $('#btnCancelProductTitle').on('click', function() {
            hideProdEditor(true);
        });
        $('#btnSaveProductTitle').on('click', function() {
            const html = $('#editorProductTitle').summernote('code');
            $.ajax({
                url: 'save_product_title.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: <?= json_encode($csrf) ?>,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $('#productTitleContent').html(resp.html);
                        hideProdEditor(false);
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    // ===== Helpers =====
    function initSummernoteOnce($el, height = 260) {
        if ($el.data('sn-inited')) return;
        $el.summernote({
            height,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen']]
            ],
            fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman', 'Courier New',
                'Helvetica'
            ],
            fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
            fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48']
        });
        $el.data('sn-inited', true);
    }

    function bindPreview(inputSel, imgSel) {
        $(inputSel).on('change', function() {
            const f = this.files && this.files[0];
            if (!f) return;
            const url = URL.createObjectURL(f);
            $(imgSel).attr('src', url);
        });
    }

    $(function() {
        const el = document.getElementById('ervPair1Modal');
        const modal = bootstrap.Modal.getOrCreateInstance(el);

        $('#btnEditERVPair1').on('click', function() {
            $('#pair1PicPrev').attr('src', $('#ervPic1').attr('src'));
            $('#pair1PicInput').val('');
            initSummernoteOnce($('#pair1Html'));
            $('#pair1Html').summernote('code', $('#ervBenefits').html().trim());
            modal.show();
        });

        bindPreview('#pair1PicInput', '#pair1PicPrev');

        el.addEventListener('hidden.bs.modal', () => {
            $('#ervPair1Form')[0].reset();
            $('#pair1PicPrev').attr('src', $('#ervPic1').attr('src'));
        });

        $('#ervPair1Form').on('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: 'save_erv_pair.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.ok) {
                        if (resp.pic) $('#ervPic1').attr('src', resp.pic);
                        if (resp.html !== undefined) $('#ervBenefits').html(resp.html);
                        modal.hide();
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    $(function() {
        const el = document.getElementById('ervPair2Modal');
        const modal = bootstrap.Modal.getOrCreateInstance(el);

        $('#btnEditERVPair2').on('click', function() {
            $('#pair2PicPrev').attr('src', $('#ervPic2').attr('src'));
            $('#pair2PicInput').val('');
            initSummernoteOnce($('#pair2Html'));
            $('#pair2Html').summernote('code', $('#ervTech').html().trim());
            modal.show();
        });

        bindPreview('#pair2PicInput', '#pair2PicPrev');

        el.addEventListener('hidden.bs.modal', () => {
            $('#ervPair2Form')[0].reset();
            $('#pair2PicPrev').attr('src', $('#ervPic2').attr('src'));
        });

        $('#ervPair2Form').on('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: 'save_erv_pair.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.ok) {
                        if (resp.pic) $('#ervPic2').attr('src', resp.pic);
                        if (resp.html !== undefined) $('#ervTech').html(resp.html);
                        modal.hide();
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    function bindBenefitEditor(opts) {
        const {
            key,
            displaySel,
            btnEditSel,
            editorSel,
            actionsSel,
            btnSaveSel,
            btnCancelSel
        } = opts;

        const $display = $(displaySel);
        const $btnEdit = $(btnEditSel);
        const $editor = $(editorSel);
        const $actions = $(actionsSel);
        const $btnSave = $(btnSaveSel);
        const $btnCancel = $(btnCancelSel);

        $editor.addClass('d-none');
        $actions.addClass('d-none');

        $btnEdit.on('click', function() {
            $btnEdit.addClass('d-none');
            $display.addClass('d-none');

            initSummernoteOnce($editor, 180);
            $editor.summernote('code', $display.html().trim());

            $editor.removeClass('d-none');
            $editor.next('.note-editor').removeClass('d-none');
            $actions.removeClass('d-none');
            $editor.summernote('focus');
        });

        $btnCancel.on('click', function() {
            if ($editor.data('sn-inited')) $editor.summernote('code', '');
            $actions.addClass('d-none');
            $editor.addClass('d-none');
            $editor.next('.note-editor').addClass('d-none');
            $display.removeClass('d-none');
            $btnEdit.removeClass('d-none');
        });

        $btnSave.on('click', function() {
            const html = $editor.summernote('code');
            $.ajax({
                url: 'save_benefit.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: <?= json_encode($csrf) ?>,
                    key,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $display.html(resp.html);
                        $actions.addClass('d-none');
                        $editor.addClass('d-none');
                        $editor.next('.note-editor').addClass('d-none');
                        $display.removeClass('d-none');
                        $btnEdit.removeClass('d-none');
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    }

    $(function() {
        bindBenefitEditor({
            key: 'benefit1',
            displaySel: '#benefit1Content',
            btnEditSel: '#btnEditbenefit1',
            editorSel: '#editorbenefit1',
            actionsSel: '#benefit1Actions',
            btnSaveSel: '#btnSavebenefit1',
            btnCancelSel: '#btnCancelbenefit1'
        });
        bindBenefitEditor({
            key: 'benefit2',
            displaySel: '#benefit2Content',
            btnEditSel: '#btnEditbenefit2',
            editorSel: '#editorbenefit2',
            actionsSel: '#benefit2Actions',
            btnSaveSel: '#btnSavebenefit2',
            btnCancelSel: '#btnCancelbenefit2'
        });
        bindBenefitEditor({
            key: 'benefit3',
            displaySel: '#benefit3Content',
            btnEditSel: '#btnEditbenefit3',
            editorSel: '#editorbenefit3',
            actionsSel: '#benefit3Actions',
            btnSaveSel: '#btnSavebenefit3',
            btnCancelSel: '#btnCancelbenefit3'
        });
        bindBenefitEditor({
            key: 'benefit4',
            displaySel: '#benefit4Content',
            btnEditSel: '#btnEditbenefit4',
            editorSel: '#editorbenefit4',
            actionsSel: '#benefit4Actions',
            btnSaveSel: '#btnSavebenefit4',
            btnCancelSel: '#btnCancelbenefit4'
        });
    });

    // === TextAudiences Editor ===
    $(function() {
        const $btn = $('#btnEditTextAudiences');
        const $content = $('#audiencesContent');
        const $ta = $('#editorTextAudiences');
        const $actions = $('#TextAudiencesActions');
        const $btnSave = $('#btnSaveTextAudiences');
        const $btnCancel = $('#btnCancelTextAudiences');
        $ta.addClass('d-none');
        $actions.addClass('d-none');

        $btn.on('click', function() {
            $btn.addClass('d-none');
            initSummernoteOnce($ta, 220);
            $ta.summernote('code', $content.html().trim());
            $content.addClass('d-none');
            $ta.removeClass('d-none').next('.note-editor').removeClass('d-none');
            $actions.removeClass('d-none');
            $ta.summernote('focus');
        });

        $btnCancel.on('click', function() {
            if ($ta.data('sn-inited')) $ta.summernote('code', '');
            $actions.addClass('d-none');
            $ta.addClass('d-none').next('.note-editor').addClass('d-none');
            $content.removeClass('d-none');
            $btn.removeClass('d-none');
        });

        $btnSave.on('click', function() {
            const html = $ta.summernote('code');
            $.ajax({
                url: 'save_text_audiences.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: CSRF,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $content.html(resp.html);
                        $actions.addClass('d-none');
                        $ta.addClass('d-none').next('.note-editor').addClass('d-none');
                        $content.removeClass('d-none');
                        $btn.removeClass('d-none');
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    });

    // Audience
    function bindAudienceEditor(opts) {
        const {
            key,
            displaySel,
            btnEditSel,
            editorSel,
            actionsSel,
            btnSaveSel,
            btnCancelSel
        } = opts;

        const $display = $(displaySel);
        const $btnEdit = $(btnEditSel);
        const $editor = $(editorSel);
        const $actions = $(actionsSel);
        const $btnSave = $(btnSaveSel);
        const $btnCancel = $(btnCancelSel);

        $editor.addClass('d-none');
        $actions.addClass('d-none');

        $btnEdit.on('click', function() {
            $btnEdit.addClass('d-none');
            $display.addClass('d-none');

            initSummernoteOnce($editor, 200);
            $editor.summernote('code', $display.html().trim());

            $editor.removeClass('d-none');
            $editor.next('.note-editor').removeClass('d-none');
            $actions.removeClass('d-none');
            $editor.summernote('focus');
        });

        $btnCancel.on('click', function() {
            if ($editor.data('sn-inited')) $editor.summernote('code', '');
            $editor.addClass('d-none').next('.note-editor').addClass('d-none');
            $actions.addClass('d-none');
            $display.removeClass('d-none');
            $btnEdit.removeClass('d-none');
        });

        $btnSave.on('click', function() {
            const html = $editor.summernote('code');
            $.ajax({
                url: 'save_audience.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    csrf: <?= json_encode($csrf) ?>,
                    key,
                    html
                },
                success: function(resp) {
                    if (resp && resp.ok) {
                        $display.html(resp.html);
                        $editor.addClass('d-none').next('.note-editor').addClass('d-none');
                        $actions.addClass('d-none');
                        $display.removeClass('d-none');
                        $btnEdit.removeClass('d-none');
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการบันทึก');
                }
            });
        });
    }

    $(function() {
        bindAudienceEditor({
            key: 'aud1',
            displaySel: '#aud1Content',
            btnEditSel: '#btnEditAudiences1',
            editorSel: '#editorAudiences1',
            actionsSel: '#Audiences1Actions',
            btnSaveSel: '#btnSaveAudiences1',
            btnCancelSel: '#btnCancelAudiences1'
        });
        bindAudienceEditor({
            key: 'aud2',
            displaySel: '#aud2Content',
            btnEditSel: '#btnEditAudiences2',
            editorSel: '#editorAudiences2',
            actionsSel: '#Audiences2Actions',
            btnSaveSel: '#btnSaveAudiences2',
            btnCancelSel: '#btnCancelAudiences2'
        });
        bindAudienceEditor({
            key: 'aud3',
            displaySel: '#aud3Content',
            btnEditSel: '#btnEditAudiences3',
            editorSel: '#editorAudiences3',
            actionsSel: '#Audiences3Actions',
            btnSaveSel: '#btnSaveAudiences3',
            btnCancelSel: '#btnCancelAudiences3'
        });
        bindAudienceEditor({
            key: 'aud4',
            displaySel: '#aud4Content',
            btnEditSel: '#btnEditAudiences4',
            editorSel: '#editorAudiences4',
            actionsSel: '#Audiences4Actions',
            btnSaveSel: '#btnSaveAudiences4',
            btnCancelSel: '#btnCancelAudiences4'
        });
    });

    // Examp textEditor
    $(function() {

        $('#editorBody').summernote({
            height: 300, // ความสูงเริ่มต้น
            minHeight: null, // ความสูงต่ำสุด
            maxHeight: null, // ความสูงสูงสุด
            focus: false, // โฟกัสหลัง init
            shortcuts: true, // ปุ่มลัดคีย์บอร์ด
            disableDragAndDrop: false,
            placeholder: 'พิมพ์ข้อความที่นี่…',
            dialogsInBody: true, // modal อยู่ใน body (กัน z-index ซ้อน)
            toolbar: [
                ['style', ['style']], // p, h1–h6, blockquote, pre
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']], // รายการฟอนต์
                ['fontsize', ['fontsize']], // ขนาด
                ['color', ['color']], // สีตัวอักษร/พื้นหลัง
                ['para', ['ul', 'ol', 'paragraph']], // bullet/number + จัดย่อหน้า
                ['insert', ['picture']], // ลิงก์/รูป/วิดีโอ/เส้นคั่น
                ['view', ['fullscreen']], // เต็มจอ/ดูโค้ด/ช่วยเหลือ
            ],
            fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman', 'Courier New',
                'Helvetica'
            ],
            fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'], // บังคับโชว์แม้ระบบไม่มี
            fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48'],

            callbacks: {
                onChange: function(contents, $editable) {
                    /* เมื่อเนื้อหาเปลี่ยน */
                },
                onPaste: function(e) {
                    /* ดัก paste (กรองสไตล์) */
                },
                onFocus: function() {},
                onBlur: function() {},
                onImageUpload: function(files) { // อัปโหลดไฟล์รูป
                    // เรียก AJAX อัปโหลด -> ได้ URL -> แทรกรูป
                    // $(this).summernote('insertImage', imageUrl, 'alt text');
                },
                onMediaDelete: function(target) { // ลบรูป/สื่อ -> แจ้งลบไฟล์ที่เซิร์ฟเวอร์ถ้าต้องการ
                    // $.post('/delete', {src: target[0].src})
                }
            }
        });

    });
    </script>
</body>

</html>