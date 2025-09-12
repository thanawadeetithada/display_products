<?php
    require_once __DIR__ . '/db.php';
    function e($s)
    {return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');}

    // header
    $H   = [];
    $res = $conn->query("SELECT name FROM site_header WHERE id=1");
    if ($row = $res->fetch_assoc()) {$H = $row;}

    // editorTitle
    $TITLE_HTML = '';
    $resT       = $conn->query("SELECT html FROM site_title_home WHERE id=1");
    if ($rowT = $resT->fetch_assoc()) {$TITLE_HTML = $rowT['html'];}
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
    $P    = ['img1' => 'img/pic1.jpg', 'img2' => 'img/pic2.jpg', 'img3' => 'img/pic3.jpg'];
    $resP = $conn->query("SELECT img1,img2,img3 FROM site_pic_slides WHERE id=1");
    if ($rowP = $resP->fetch_assoc()) {
        $P = array_merge($P, array_filter($rowP, fn($v) => $v !== null && $v !== ''));
    }

    // featTitle
    $FEAT_HTML = '';
    $resF      = $conn->query("SELECT html FROM site_feat_title WHERE id=1");
    if ($rowF = $resF->fetch_assoc()) {$FEAT_HTML = $rowF['html'];}
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
    $resPT     = $conn->query("SELECT html FROM site_product_title WHERE id=1");
    if ($rowPT = $resPT->fetch_assoc()) {$PROD_HTML = $rowPT['html'];}
    if ($PROD_HTML === '') {
        $PROD_HTML = '<h2>ผลิตภัณฑ์คุณภาพระดับโลก</h2>
<p class="products-sub">เทคโนโลยีขั้นสูงจากแบรนด์ชั้นนำ ผ่านการรับรองคุณภาพมาตรฐานสากล</p>';
    }

    // ERV section (4 ส่วน)
    $ERV = [
        'pic1'          => 'img/pic1.jpg',
        'benefits_html' => '',
        'tech_html'     => '',
        'pic2'          => 'img/pic2.jpg',
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
    $resAud   = $conn->query("SELECT html FROM site_text_audiences WHERE id=1");
    if ($rowAud = $resAud->fetch_assoc()) {$AUD_HTML = $rowAud['html'];}
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

    // contact block
    $CONTACT_HTML = '';
    $resC         = $conn->query("SELECT html FROM site_contact WHERE id=1");
    if ($rowC = $resC->fetch_assoc()) {$CONTACT_HTML = $rowC['html'];}
    if ($CONTACT_HTML === '') {
        $CONTACT_HTML = '<h2>สนใจติดตั้งระบบแลกเปลี่ยนอากาศ <br><span>LUMA AIR ERV </span>ในบ้านของคุณไหม?</h2>
<p>ติดต่อทีมงานเพื่อรับคำปรึกษาและใบเสนอราคาฟรี</p>';
    }

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
    'number1' => '02-123-4567',
    'number2' => '089-123-4567',
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
    'address' => "123 ถนนสุขุมวิท แขวงคลองเคย\nเขตคลองเคย กรุงเทพมหานคร 10110",
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
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LUMA AIR - ERV System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">หน้าหลัก</a></li>
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
                    <?php echo $TITLE_HTML ?>
                </div>
                <div class="hero-btns">
                    <a class="btn-bluenext">ดูรายละเอียดผลิตภัณฑ์</a>
                    <a class="btn-bluecurb">ปรึกษาฟรี</a>
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
                            <img id="slideImg1" src="<?php echo e($P['img1']) ?>" class="d-block w-100" alt="ภาพที่ 1"
                                loading="lazy">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img id="slideImg2" src="<?php echo e($P['img2']) ?>" class="d-block w-100" alt="ภาพที่ 2"
                                loading="lazy">
                        </div>
                        <div class="carousel-item" data-bs-interval="3000">
                            <img id="slideImg3" src="<?php echo e($P['img3']) ?>" class="d-block w-100" alt="ภาพที่ 3"
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
            </div>

        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <div id="featContent">
                <?php echo $FEAT_HTML ?>
            </div>
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
                            <span id="f1TitleText"><?php echo e(strip_tags($FEAT_PARTS['feat1_title'])) ?></span>
                        </span>
                    </summary>

                    <div class="content">
                        <span id="f1ContentText"><?php echo $FEAT_PARTS['feat1_content'] ?></span>
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
                            <span id="f2TitleText"><?php echo e(strip_tags($FEAT_PARTS['feat2_title'])) ?></span>
                        </span>
                    </summary>

                    <div class="content">
                        <span id="f2ContentText"><?php echo $FEAT_PARTS['feat2_content'] ?></span>
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
                            <span id="f3TitleText"><?php echo e(strip_tags($FEAT_PARTS['feat3_title'])) ?></span>
                        </span>
                    </summary>

                    <div class="content">
                        <span id="f3ContentText"><?php echo $FEAT_PARTS['feat3_content'] ?></span>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="products" id="products">
        <div class="container">
            <div id="productTitleContent">
                <?php echo $PROD_HTML ?>
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
        </div>
    </section>

    <!-- ERV info section -->
    <section class="erv-info" id="erv-info">
        <div class="container">
            <div class="erv-row">
                <div class="erv-col">
                    <div class="img-frame lg">
                        <img id="ervPic1" src="<?php echo e($ERV['pic1']) ?>" alt="Family living room">
                    </div>
                </div>
                <div class="erv-col">
                    <div id="ervBenefits"><?php echo $ERV['benefits_html'] ?></div>
                </div>
            </div>

            <div class="erv-row second mt-4">
                <div class="erv-col">
                    <div id="ervTech"><?php echo $ERV['tech_html'] ?></div>
                </div>
                <div class="erv-col">
                    <div class="img-stack">
                        <div class="img-frame sm">
                            <img id="ervPic2" src="<?php echo e($ERV['pic2']) ?>" alt="ERV phone UI">
                        </div>
                    </div>
                </div>
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
                    <div id="benefit1Content"><?php echo $BENEFITS['benefit1'] ?></div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon green">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3l7 3v6c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V6l7-3z" />
                        </svg>
                    </div>
                    <div id="benefit2Content"><?php echo $BENEFITS['benefit2'] ?></div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon purple">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12l2.5 2.5L16 9" />
                        </svg>
                    </div>
                    <div id="benefit3Content"><?php echo $BENEFITS['benefit3'] ?></div>
                </article>

                <article class="benefit-card">
                    <div class="benefit-icon amber">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13 2L3 14h7l-1 8 11-12h-7l1-8z" />
                        </svg>
                    </div>
                    <div id="benefit4Content"><?php echo $BENEFITS['benefit4'] ?></div>
                </article>
            </div>
        </div>
    </section>

    <!-- Suitable-->
    <section class="audiences" id="audiences">
        <div class="container">
            <div id="audiencesContent"><?php echo $AUD_HTML ?></div>
            <div class="aud-grid">
                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud1Content"><?php echo $AUD_ITEMS['aud1'] ?></div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud2Content"><?php echo $AUD_ITEMS['aud2'] ?></div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud3Content"><?php echo $AUD_ITEMS['aud3'] ?></div>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <div id="aud4Content"><?php echo $AUD_ITEMS['aud4'] ?></div>
                </article>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <div id="contactContent"><?php echo $CONTACT_HTML ?></div>
            <div class="contact-btns">
                <a class="btn-overwhite" href="#products">ดูรายละเอียดผลิตภัณฑ์</a>
                <a class="btn-white" href="#">ติดต่อเราเลย</a>
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
                            <span id="footerPhones">
                                <?php echo e($F['number1']) ?><br><?php echo e($F['number2']) ?>
                            </span>
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>