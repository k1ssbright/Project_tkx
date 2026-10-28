@extends('layouts.app')

@section('title', 'ติดต่อเรา')

@section('content')
    <section class="venue-hero">
        <img src="{{ $siteImages['contact_cover_image'] }}" alt="ป้าย Nano Circuit บนอาคารสนาม">
        <div class="venue-hero-copy">
            <p>contact us</p>
            <h1>ติดต่อเรา</h1>
        </div>
    </section>

    <section class="contact-page">
        <div class="contact-copy">
            <h2>Nano Circuit</h2>
            <p>Nano Circuit Go-Kart Track บางนา–ตราด กม.15 — สนามโกคาร์ทแห่งใหม่ล่าสุดทางฝั่งตะวันออกของกรุงเทพฯ ที่พร้อมก้าวสู่การเป็นศูนย์รวมของผู้หลงใหลมอเตอร์สปอร์ตรุ่นใหม่.</p>

            <h3>Office</h3>
            <p>Bangna-trad, Bang Chalong, Bang Phli, Samut Prakan 10540</p>
            <p><a href="tel:+66612324516">+ (66) 612324516</a></p>
            <p><a href="https://www.facebook.com/nanobiketh" target="_blank" rel="noopener">NanoCircuit- Bangna-trad KM.15 สนามโกคาร์ทนาโนเซอร์กิต</a></p>
            <p>วันจันทร์ - วันอาทิตย์ : 13.00 น. - 22.00 น.</p>
        </div>
    </section>

    <section class="contact-map">
        <h2>OUR LOCATIONS</h2>
        <iframe
            title="Nano Circuit"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4599.606483659861!2d100.7394295!3d13.6209451!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d5f9e2a8f7fd5%3A0x68599e4cf8eba0fe!2zTmFub0NpcmN1aXQt4Liq4LiZ4Liy4Lih4LmC4LiB4LiE4Liy4Lij4LmM4LiXIChHby1LYXJ0aW5nIEJhbmdrb2sp!5e1!3m2!1sth!2sth!4v1759716534912!5m2!1sth!2sth"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    </section>
@endsection
