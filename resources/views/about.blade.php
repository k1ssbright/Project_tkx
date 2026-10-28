@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา')

@section('content')
    <section class="venue-hero">
        <img src="{{ $siteImages['about_cover_image'] }}" alt="ป้าย Nano Circuit บนอาคารสนาม">
        <div class="venue-hero-copy">
            <p>Nano Circuit</p>
            <h1>เกี่ยวกับเรา</h1>
        </div>
    </section>

    <section class="venue-intro">
        <img src="{{ $siteImages['about_owners_image'] }}" alt="ทีม Nano Circuit ที่สนามโกคาร์ท">
        <p class="eyebrow">Go-Kart Track Thailand</p>
        <h2>Nano Circuit</h2>
        <p>Nano Circuit Go-Kart Track ตั้งอยู่ที่บางนา–ตราด กม.15 คือจุดหมายปลายทางโกคาร์ทแห่งใหม่ล่าสุดทางฝั่งตะวันออกของกรุงเทพฯ ที่กำลังจะกลายเป็นแหล่งรวมตัวของผู้หลงใหลมอเตอร์สปอร์ตอย่างแท้จริง สนามมีระยะทางต่อรอบ 900 เมตร และสามารถปรับเปลี่ยนรูปแบบได้มากถึง 8 เลย์เอาต์ มอบประสบการณ์การขับขี่ที่หลากหลาย</p>
        <p>ที่นี่ใช้รถโกคาร์ทรุ่น TS7 ทำความเร็วได้สูงสุดถึง 70 กม./ชม. สำหรับผู้ใหญ่ (อายุ 12 ปีขึ้นไป) พร้อมทั้งมีรถโกคาร์ทที่ออกแบบพิเศษสำหรับนักขับรุ่นเยาว์โดยเฉพาะอีกด้วย</p>
    </section>

    <section class="venue-experience">
        <p class="eyebrow">New experience</p>
        <h2>Dynamic Racing Experience</h2>
        <div class="venue-gallery">
            <img src="{{ $siteImages['about_gallery_1_image'] }}" alt="นักขับโกคาร์ทที่ Nano Circuit">
            <img src="{{ $siteImages['about_gallery_2_image'] }}" alt="บรรยากาศในสนาม Nano Circuit">
            <img src="{{ $siteImages['about_gallery_3_image'] }}" alt="โกคาร์ทบนสนาม Nano Circuit">
            <img src="{{ $siteImages['about_gallery_4_image'] }}" alt="การแข่งขันที่ Nano Circuit">
        </div>
    </section>

    <section class="venue-story">
        <p class="eyebrow">Adrenalin</p>
        <h2>The Professional<br>Kart Thailand</h2>
        <p>ที่ Nano Circuit เรามีทุกอย่างที่คนรักมอเตอร์สปอร์ตต้องการ ตั้งแต่สนามโกคาร์ทระยะทาง 900 เมตรที่ปรับเปลี่ยนได้หลายรูปแบบ รถโกคาร์ทประสิทธิภาพสูงรุ่น TS7 และรถสำหรับนักขับรุ่นเยาว์ ไปจนถึงสิ่งอำนวยความสะดวกระดับมืออาชีพที่รองรับทั้งผู้ขับขี่ทั่วไปและนักแข่งจริงจัง Nano Circuit ถูกออกแบบมาเพื่อมอบความเร้าใจของการแข่งรถอย่างแท้จริงในทุก ๆ รอบที่คุณขับ</p>
        <img src="{{ $siteImages['about_track_image'] }}" alt="สนามโกคาร์ท Nano Circuit">
    </section>

    <section class="venue-facility">
        <p class="eyebrow">Nanocircuit</p>
        <h2>Our Facility</h2>
        <div class="facility-grid">
            <article>
                <svg viewBox="0 0 64 64" aria-hidden="true">
                    <rect x="14" y="8" width="28" height="40" rx="2" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M22 20h12M22 28h12M22 36h8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="44" cy="44" r="12" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M44 38v6l4 2" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <h3>Driver Registration Area</h3>
                <p>ระบบเช็กอินที่รวดเร็วและสะดวกสบายสำหรับผู้ขับขี่ทุกคน</p>
            </article>
            <article>
                <svg viewBox="0 0 64 64" aria-hidden="true">
                    <path d="M18 30c0-10 6-18 14-18s14 8 14 18v6H18v-6z" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M16 36h32v6c0 6-7 10-16 10s-16-4-16-10v-6z" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M24 28h16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <h3>High-Quality Helmets &amp; Racing Gear</h3>
                <p>หมวกกันน็อก แผงคอ ถุงมือ และอุปกรณ์สำหรับเด็ก ที่ได้รับการรับรองความปลอดภัยครบถ้วน</p>
            </article>
            <article>
                <svg viewBox="0 0 64 64" aria-hidden="true">
                    <path d="M14 40l8-16h20l8 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                    <circle cx="22" cy="46" r="5" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <circle cx="42" cy="46" r="5" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M27 46h10M32 24v10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <h3>Pit Lane &amp; Kart Maintenance Zone</h3>
                <p>พื้นที่พิตระดับมืออาชีพ พร้อมทีมช่างบำรุงรักษาเต็มรูปแบบ เพื่อให้มั่นใจถึงสมรรถนะสูงสุดของรถทุกคัน</p>
            </article>
            <article>
                <svg viewBox="0 0 64 64" aria-hidden="true">
                    <path d="M20 26h24v8c0 8-5 14-12 14s-12-6-12-14v-8z" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M24 26c0-6 3-10 8-10s8 4 8 10" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M22 50h20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <h3>Food &amp; Beverage Corner</h3>
                <p>คาเฟ่หรือสแน็กบาร์ สำหรับบริการเครื่องดื่ม กาแฟ และอาหารว่าง</p>
            </article>
        </div>
    </section>

    <section class="venue-portfolio">
        <p class="eyebrow">Portfolio</p>
        <h2>Nano Circuit</h2>
        <div class="portfolio-grid">
            <article>
                <img src="{{ $siteImages['about_grand_opening_image'] }}" alt="Nano Circuit Grand Opening">
                <time datetime="2025-11-26">พฤศจิกายน 26, 2025</time>
                <h3>Nanocircuit Grand Openning</h3>
            </article>
            <article>
                <img src="{{ $siteImages['about_sprint_race_image'] }}" alt="Nano Circuit Sprint Race 1">
                <time datetime="2025-12-01">ธันวาคม 1, 2025</time>
                <h3>Nanocircuit Sprint Race#1</h3>
            </article>
        </div>
    </section>
@endsection
