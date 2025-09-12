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

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

if ($_SERVER['REQUEST_METHOD']==='POST' && (isset($_POST['action']) && $_POST['action']==='save')) {
    header('Content-Type: application/json; charset=utf-8');

    // ตรวจ CSRF
    if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        http_response_code(400);
        echo json_encode(['ok'=>false, 'msg'=>'CSRF ไม่ถูกต้อง']);
        exit;
    }

    $id   = isset($_POST['id']) ? (int)$_POST['id'] : 1;
    $html = isset($_POST['html']) ? (string)$_POST['html'] : '';

    $stmt = $conn->prepare("UPDATE article SET html=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param('si', $html, $id);
    $ok = $stmt->execute();
    $stmt->close();

    if($ok){
        echo json_encode(['ok'=>true, 'msg'=>'บันทึกสำเร็จ']);
    } else {
        http_response_code(500);
        echo json_encode(['ok'=>false, 'msg'=>'บันทึกไม่สำเร็จ']);
    }
    exit;
}

$article = ['id'=>$articleId, 'title'=>'', 'html'=>'', 'updated_at'=>null];
$stmt = $conn->prepare("SELECT id, title, html, updated_at FROM article WHERE id=? LIMIT 1");
$stmt->bind_param('i', $articleId);
$stmt->execute();
$resArt = $stmt->get_result();
if($row = $resArt->fetch_assoc()){
    $article = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>บทความ</title>
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

    @media (max-width:575px) {
        .navbar {
            padding: 8px 20px;
        }
    }

    #editBar .btn {
        min-width: 100px
    }

    .rounded {
        border-radius: 30px !important;
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
                    <li class="nav-item"><a class="nav-link active" href="admin_article.php">บทความ</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div id="articleView" class="rounded p-3 bg-white">
            <?php echo $article['html'];?>
        </div>
        <textarea id="editorBody" class="mt-3 d-none"></textarea>
        <div id="editBar" class="d-flex gap-2 mt-3 justify-content-end">
            <button id="btnEdit" class="btn btn-warning">แก้ไข</button>
            <button id="btnSave" class="btn btn-success d-none">บันทึก</button>
            <button id="btnCancel" class="btn btn-outline-secondary d-none">ยกเลิก</button>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(function() {
        const ARTICLE_ID = <?php echo (int)$article['id']; ?>;
        const CSRF_TOKEN = <?php echo json_encode($csrf, JSON_UNESCAPED_UNICODE); ?>;
        let snapshotHtml = <?php echo json_encode($article['html'] ?? '', JSON_UNESCAPED_UNICODE); ?>;

        function initEditor(html) {
            $('#editorBody')
                .removeClass('d-none')
                .summernote({
                    height: 700,
                    focus: true,
                    placeholder: 'พิมพ์ข้อความที่นี่…',
                    dialogsInBody: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['picture']],
                    ],
                    fontNames: ['Prompt', 'TH Sarabun New', 'Arial', 'Tahoma', 'Times New Roman',
                        'Courier New', 'Helvetica'
                    ],
                    fontNamesIgnoreCheck: ['Prompt', 'TH Sarabun New'],
                    fontSizes: ['10', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48']
                })
                .summernote('code', html || '');
        }

        function destroyEditor() {
            if ($('#editorBody').next('.note-editor').length) {
                $('#editorBody').summernote('destroy');
            }
            $('#editorBody').addClass('d-none');
        }

        $('#btnEdit').on('click', function() {
            $('#btnEdit').addClass('d-none');
            $('#btnSave, #btnCancel').removeClass('d-none');
            $('#articleView').addClass('d-none');
            initEditor(snapshotHtml);
        });

        $('#btnCancel').on('click', function() {
            destroyEditor();
            $('#btnSave, #btnCancel').addClass('d-none');
            $('#btnEdit').removeClass('d-none');
            $('#articleView').html(snapshotHtml).removeClass('d-none');
        });

        $('#btnSave').on('click', function() {
            const html = $('#editorBody').summernote('code');
            $('#btnSave').prop('disabled', true).text('กำลังบันทึก...');

            $.post('admin_article.php?id=' + ARTICLE_ID, {
                    action: 'save',
                    id: ARTICLE_ID,
                    html: html,
                    csrf: CSRF_TOKEN
                }, function(resp) {
                    if (resp && resp.ok) {
                        snapshotHtml = html;
                        $('#articleView').html(snapshotHtml);
                        destroyEditor();
                        $('#btnSave, #btnCancel').addClass('d-none');
                        $('#btnEdit').removeClass('d-none');
                        $('#articleView').removeClass('d-none');
                    } else {
                        alert(resp && resp.msg ? resp.msg : 'บันทึกไม่สำเร็จ');
                    }
                }, 'json')
                .fail(function() {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                })
                .always(function() {
                    $('#btnSave').prop('disabled', false).text('บันทึก');
                });
        });
        $('.note-editor').addClass('d-none');
    });
    </script>
</body>

</html>