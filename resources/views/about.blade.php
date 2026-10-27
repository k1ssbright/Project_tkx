@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา')

@section('content')
    <section class="about-hero">
        <div>
            <p class="eyebrow">About NANO CIRCUIT</p>
            <h1>ร้านเสื้อผ้า<br>ที่เลือกเฉพาะโทนขาว</h1>
            <p class="lede">NANO CIRCUIT รวมเสื้อและกางเกงสีขาว ครีม และเบจไว้ในที่เดียว เน้นผ้าที่ใส่สบายและทรงที่เรียบพอจะใส่ซ้ำได้ทั้งสัปดาห์</p>
            <div class="billboard-actions about-actions">
                <a class="buy-now" href="{{ route('shop.index') }}">ดูสินค้าทั้งหมด</a>
                <a class="billboard-link" href="{{ route('news.index') }}">อ่านข่าวสาร</a>
            </div>
        </div>
        <div class="about-hero-media">
            <img src="{{ asset('images/hero-wardrobe.jpg') }}?v={{ filemtime(public_path('images/hero-wardrobe.jpg')) }}" alt="ราวแขวนเสื้อโทนขาวและครีมของร้าน NANO CIRCUIT">
        </div>
    </section>

    <section class="about-points" aria-label="ข้อมูลร้าน">
        <div class="about-point">
            <span>01</span>
            <h2>สิ่งที่ร้านมี</h2>
            <p>เสื้อเชิ้ต เสื้อยืด เสื้อโปโล กางเกงขายาว กางเกงขากว้าง และกางเกงชิโน เว็บนี้เอาไว้เปิดดูคอลเลกชัน ไม่มีตะกร้าสินค้าและไม่รับสั่งซื้อ</p>
        </div>
        <div class="about-point">
            <span>02</span>
            <h2>ไซส์ในคอลเลกชัน</h2>
            <p>ไซส์เสื้อใช้ S ถึง XL ตามตารางด้านล่าง ไซส์กางเกงขายาวใช้เอว 28 ถึง 36 และกางเกงขากว้างใช้ S ถึง XL</p>
        </div>
        <div class="about-point">
            <span>03</span>
            <h2>ผ้าที่เลือกใช้</h2>
            <p>ลินินและคอตตอนเป็นหลัก ระบายอากาศดี เหมาะกับอากาศร้อน และอยู่ในโทนสีที่จับคู่กันได้ทุกชิ้น</p>
        </div>
    </section>

    <section class="section size-chart-block">
        @include('partials.shirt-size-chart')
    </section>
@endsection
