<?php /* index.php */ ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LUMA AIR - ERV System</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container">
            <div class="logo">LUMA AIR</div>
            <nav>
                <a href="#home">หน้าหลัก</a>
                <a href="#contact">ติดต่อเรา</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero" id="home">
        <div class="container hero-flex">
            <div class="hero-text">
                <span class="tag">⭐ อากาศบริสุทธิ์ คุณภาพพรีเมียม</span>
                <h2>หายใจ <span class="blue">อากาศบริสุทธิ์</span><br />ทุกลมหายใจ</h2>
                <p>
                    เครื่องแลกเปลี่ยนอากาศ <b>LUMA AIR ERV</b> ด้วยเทคโนโลยีขั้นสูง
                    ดูดอากาศเก่า กรองและแทนที่ด้วยอากาศบริสุทธิ์
                    <span class="blue">ไม่ต้องเปิดหน้าต่าง</span>
                </p>
                <div class="hero-btns">
                    <a class="btn" href="#products">ดูรายละเอียดผลิตภัณฑ์</a>
                    <a class="btn-outline" href="#contact">ปรึกษาฟรี</a>
                </div>
            </div>
            <div class="hero-img">
                <img src="img/pic2.jpg" alt="Luma Air App" />
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <h2><span class="blue">LUMA AIR ERV</span><br />Energy Recovery Ventilation</h2>
            <p>ระบบระบายอากาศที่ช่วยแลกเปลี่ยนความร้อนและความชื้นระหว่างอากาศภายในและภายนอกอาคาร
                พร้อมกรองอากาศให้บริสุทธิ์</p>
            <ul class="feature-list">
                <li>
                    <h4>⚠ วิงเวียนศีรษะ และง่วงนอนขณะทำงาน</h4>
                    <p>อาจเกิดจากระดับ CO2 ที่สูงและคุณภาพอากาศภายในอาคารที่ไม่ดี</p>
                </li>
                <li>
                    <h4>⚠ กลิ่นอับ เชื้อรา และแบคทีเรีย</h4>
                    <p>ในบ้านระบบปิด การระบายอากาศที่จำกัดทำให้ความชื้นสะสมได้ง่าย</p>
                </li>
                <li>
                    <h4>⚠ สารฟอร์มาลดีไฮด์ และ VOCs</h4>
                    <p>เฟอร์นิเจอร์บิวท์อินอาจปล่อยสารระเหยอันตรายที่มองไม่เห็น</p>
                </li>
            </ul>
        </div>
    </section>

    <!-- Products -->
    <section class="products" id="products">
        <div class="container">
            <h2>ผลิตภัณฑ์คุณภาพระดับโลก</h2>
            <p class="products-sub">เทคโนโลยีขั้นสูงจากแบรนด์ชั้นนำ ผ่านการรับรองคุณภาพมาตรฐานสากล</p>

            <div class="product-grid">
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

            <!-- แถวที่ 1 : ซ้ายรูป / ขวาข้อความ + list -->
            <div class="erv-row">
                <div class="erv-col">
                    <div class="img-frame lg">
                        <img src="img/pic1.jpg" alt="Family living room">
                    </div>
                </div>
                <div class="erv-col">
                    <h3 class="sun-title">🌞 อากาศสดชื่น ลดอาการวิงเวียนศีรษะ นอนหลับสนิท</h3>
                    <p>
                        ประหยัดไฟ 40–60% ใช้งานคุ้มค่า ช่วยลด PM 2.5 กรองฝุ่น
                        ช่วยลด CO<sub>2</sub> เพิ่ม O<sub>2</sub> ออกซิเจนใหม่เข้าห้อง
                        เติมอากาศสดชื่นบริสุทธิ์จากภายนอก
                    </p>
                    <ul class="checklist">
                        <li>ประหยัดไฟ 40–60% ใช้งานคุ้มค่า</li>
                        <li>ช่วยลด PM 2.5 ปกป้องจากฝุ่น</li>
                        <li>ช่วยลด CO<sub>2</sub> เพิ่ม O<sub>2</sub> ภายในห้อง</li>
                        <li>เติมอากาศสดชื่นบริสุทธิ์จากภายนอก โดยผ่านการกรอง</li>
                        <li>ลดปัญหาภูมิแพ้ กับเด็กเล็ก และลูกค้า</li>
                        <li>ลดไวรัสและแบคทีเรีย ที่มาจากความชื้นที่ไม่สมดุล</li>
                    </ul>
                </div>
            </div>

            <!-- แถวที่ 2 : ซ้ายหัวข้อเทคนิค / ขวารูป 2 ใบ -->
            <div class="erv-row second">
                <div class="erv-col">
                    <h3 class="tech-title">ข้อมูลทางเทคนิคและการติดตั้ง</h3>
                    <p>
                        ระบบ ERV มีมาตรและสเปคที่เหมาะสำหรับบ้านและอาคารต่างๆ
                        พร้อมข้อมูลการติดตั้งที่ชัดเจนและการรับประกันคุณภาพ
                    </p>
                    <ul class="bullet-amber">
                        <li>รับรองมาตรฐานจาก CE และ CSA</li>
                        <li>ประสิทธิภาพการกู้คืนพลังงานสูงสุด 97%</li>
                        <li>ติดตั้งได้ทั้งภายในและภายนอกอาคาร</li>
                    </ul>
                </div>
                <div class="erv-col">
                    <div class="img-stack">
                        <div class="img-frame sm"><img src="img/pic2.jpg" alt="Duct spec"></div>
                        <!-- <div class="img-frame sm"><img src="img/pic1.jpg" alt="Happy family"></div> -->
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Benefit / Highlights (ไม่ใช่ products) -->
    <section class="benefits" id="benefits">
        <div class="container">
            <div class="benefit-grid">

                <!-- เพิ่ม O2 ลด CO2 -->
                <article class="benefit-card">
                    <div class="benefit-icon">
                        <!-- ไอคอนลม -->
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M3 8h10c2 0 2-3 0-3-1.2 0-1.8.6-2 1M3 12h14c2 0 2-3 0-3M3 16h8c2 0 2 3 0 3-1.2 0-1.8-.6-2-1" />
                        </svg>
                    </div>
                    <h3>เพิ่ม O2 ลด CO2</h3>
                    <p>แลกเปลี่ยนอากาศเก่าให้เป็นอากาศใหม่ที่มีออกซิเจนสูง</p>
                </article>

                <!-- กรอง PM2.5 -->
                <article class="benefit-card">
                    <div class="benefit-icon green">
                        <!-- ไอคอนโล่ -->
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3l7 3v6c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V6l7-3z" />
                        </svg>
                    </div>
                    <h3>กรอง PM2.5</h3>
                    <p>กรองฝุ่นละออง PM2.5 และอนุภาคไม่พึงประสงค์ก่อนเข้าห้อง</p>
                </article>

                <!-- กำจัดสารฟอร์มาลดีไฮด์ -->
                <article class="benefit-card">
                    <div class="benefit-icon purple">
                        <!-- ไอคอนวงกลมเช็ค -->
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12l2.5 2.5L16 9" />
                        </svg>
                    </div>
                    <h3>กำจัดสารฟอร์มาลดีไฮด์</h3>
                    <p>กรองสารเคมีจากเฟอร์นิเจอร์และวัสดุก่อสร้างต่างๆ</p>
                </article>

                <!-- แลกเปลี่ยนความร้อน -->
                <article class="benefit-card">
                    <div class="benefit-icon amber">
                        <!-- ไอคอนสายฟ้า -->
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13 2L3 14h7l-1 8 11-12h-7l1-8z" />
                        </svg>
                    </div>
                    <h3>แลกเปลี่ยนความร้อน</h3>
                    <p>รักษาอุณหภูมิห้องให้เหมาะสม ประหยัดพลังงาน</p>
                </article>

            </div>
        </div>
    </section>

    <!-- Suitable for all building types -->
    <section class="audiences" id="audiences">
        <div class="container">
            <h2>🏡 เหมาะสำหรับทุกประเภทอาคาร</h2>
            <p class="aud-sub">
                LUMA AIR ERV สามารถติดตั้งได้กับอาคารและที่พักอาศัยทุกประเภท
            </p>

            <div class="aud-grid">
                <article class="aud-card">
                    <div class="aud-icon">
                        <!-- heart icon -->
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <h3>บ้านเดี่ยว</h3>
                    <p>เหมาะสำหรับการติดตั้งในห้องต่างๆ</p>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <h3>ทาวน์เฮาส์</h3>
                    <p>แก้ปัญหาอากาศไม่ถ่ายเทในพื้นที่จำกัด</p>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <h3>คอนโดมิเนียม</h3>
                    <p>เพิ่มคุณภาพอากาศในพื้นที่ขนาดกะทัดรัด</p>
                </article>

                <article class="aud-card">
                    <div class="aud-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 20s-6.5-4.4-9-7.9C1 9.6 2.1 6.7 4.9 6 6.7 5.6 8.5 6.4 9.6 7.8 10 8.3 10.5 9 12 10.4 13.5 9 14 8.3 14.4 7.8c1.1-1.4 2.9-2.2 4.7-1.8 2.8.7 3.9 3.6 1.9 6.1C18.5 15.6 12 20 12 20z" />
                        </svg>
                    </div>
                    <h3>อาคารพาณิชย์</h3>
                    <p>สร้างสภาพแวดล้อมทำงานที่ดี</p>
                </article>
            </div>
        </div>
    </section>



    <!-- Contact -->
    <section class="contact" id="contact">
        <div class="container">
            <h2>สนใจติดตั้งระบบแลกเปลี่ยนอากาศ <br><span style="color: #a2f4fd;">LUMA AIR ERV </span>ในบ้านของคุณไหม?
            </h2>
            <p>ติดต่อทีมงานเพื่อรับคำปรึกษาและใบเสนอราคาฟรี</p>
            <div class="contact-btns">
                <a class="btn" href="#products">ดูรายละเอียดผลิตภัณฑ์</a>
                <a class="btn-outline" href="#">ติดต่อเราเลย</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer" id="contact">
  <div class="container">
    <div class="footer-top">
      <!-- 1) Brand -->
      <div class="fcol">
        <h4>LUMA AIR</h4>
        <p>ผู้เชี่ยวชาญระบบแลกเปลี่ยนอากาศ เพื่อคุณภาพอากาศที่ดีในบ้าน</p>
      </div>

      <!-- 2) Contact -->
      <div class="fcol">
        <h4>ติดต่อเรา</h4>
        <ul class="f-list">
          <li>
            <span class="ficon">
              <!-- phone -->
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.2 19.2 0 0 1-8.4-3.2 18.8 18.8 0 0 1-6-6A19.2 19.2 0 0 1 2.1 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.7.6 2.5a2 2 0 0 1-.5 2L8.5 9a16.5 16.5 0 0 0 6 6l.9-1.6a2 2 0 0 1 2-1c.8.2 1.6.4 2.5.6A2 2 0 0 1 22 16.9z"/></svg>
            </span>
            02-123-4567<br>089-123-4567
          </li>
          <li>
            <span class="ficon">
              <!-- mail -->
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm0 0l8 6 8-6"/></svg>
            </span>
            info@lumaair.com
          </li>
        </ul>
      </div>

      <!-- 3) Address -->
      <div class="fcol">
        <h4>ที่อยู่</h4>
        <ul class="f-list">
          <li>
            <span class="ficon">
              <!-- location -->
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-7.1 7-12a7 7 0 1 0-14 0c0 4.9 7 12 7 12z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            123 ถนนสุขุมวิท แขวงคลองเคย<br>เขตคลองเคย กรุงเทพมหานคร 10110
          </li>
        </ul>
      </div>

      <!-- 4) Hours -->
      <div class="fcol">
        <h4>เวลาทำการ</h4>
        <ul class="f-list">
          <li>
            <span class="ficon">
              <!-- clock -->
              <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </span>
            จันทร์–ศุกร์: 9:00–18:00<br>เสาร์–อาทิตย์: 10:00–16:00
          </li>
        </ul>
      </div>
    </div>

    <hr class="f-divider">

    <div class="footer-bottom">
      <div class="copy">© 2025 LUMA AIR. สงวนลิขสิทธิ์ทุกประการ</div>
      <ul class="f-links">
        <li><a href="#">ติดตั้งทั่วประเทศ</a></li>
        <li><a href="#">รับประกัน 2 ปี</a></li>
        <li><a href="#">บริการหลังการขาย</a></li>
      </ul>
    </div>
  </div>
</footer>


</body>

</html>