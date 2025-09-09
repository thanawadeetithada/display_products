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
if ($rowP = $resP->fetch_assoc()) { $P = $rowP; }

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
    <link rel="stylesheet" href="style2.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let BRAND_DB = <?= json_encode($H['name'] ?? 'LUMA AIR') ?>;
    const CSRF = <?= json_encode($csrf) ?>;

    // header
    $(function() {
        let inited = false;

        $('#btnEditLogo').on('click', function() {
            $('#brandLink').addClass('d-none');
            $('#btnEditLogo').addClass('d-none');
            $('#logoActions').removeClass('d-none');
            $('#logoEditorWrap').removeClass('d-none');

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
            $('#editorLogo').summernote('code', BRAND_DB);
            $('#editorLogo').summernote('focus');
        });

        $('#btnCancelLogo').on('click', function() {
            $('#logoActions').addClass('d-none');
            $('#logoEditorWrap').addClass('d-none');
            $('#btnEditLogo').removeClass('d-none');
            $('#brandLink').removeClass('d-none');
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
                        $('#logoActions').addClass('d-none');
                        $('#editorLogo').addClass('d-none');
                        $('#logoEditorWrap').addClass('d-none');
                        $('#btnEditLogo').removeClass('d-none');
                        $('#brandLink').removeClass('d-none');
                        BRAND_DB = resp.name;
                    } else {
                        alert(resp.error || 'บันทึกไม่สำเร็จ');
                    }
                },
                error: function(xhr) {
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

    //  Examp textEditor
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