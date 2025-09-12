<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LUMA AIR - ERV System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css" />

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
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg bg-light border-bottom sticky-top">
        <div class="container">

            <div class="mb-3">
                <input type="text" class="form-control" id="exampleFormControlInput1">
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="สลับเมนู">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="admin_home.php">หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_article.php">บทความ</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Footer -->
    <footer class="site-footer" id="contact">
        <div class="container">
            <div class="row gy-4 align-items-start footer-top">
                <div class="col-12 col-lg-4 fcol">
                    <h4 class="mb-3">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="exampleFormControlInput1">
                        </div>
                    </h4>
                    <p class="mb-0">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    </p>
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
                            <span>
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div>
                            </span>
                        </li>
                        <li>
                            <span class="ficon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 0l8 6 8-6" />
                                </svg>
                            </span>
                            <span>
                                <div class="mb-3">
                                    <input type="email" class="form-control" id="exampleFormControlInput1">
                                </div>
                            </span>
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
                            <span>
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div>
                            </span>
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
                            <span>
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="f-divider">

            <div class="footer-bottom d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
                <div class="copy">© 2025 LUMA AIR. สงวนลิขสิทธิ์ทุกประการ</div>
                <ul class="f-links list-inline m-0">
                    <li class="list-inline-item"><a href="#">ติดตั้งทั่วประเทศ</a></li>
                    <li class="list-inline-item"><a href="#">รับประกัน 2 ปี</a></li>
                    <li class="list-inline-item"><a href="#">บริการหลังการขาย</a></li>
                </ul>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>