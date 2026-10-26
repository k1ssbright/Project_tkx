@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา')

@section('content')
    <section class="page-intro about">
        <p class="eyebrow">About NANO CIRCUIT</p>
        <h1>ร้านเสื้อผ้าที่เลือกเฉพาะโทนขาว</h1>
        <p class="lede">NANO CIRCUIT รวมเสื้อและกางเกงสีขาว ครีม และเบจไว้ในที่เดียว เน้นผ้าที่ใส่สบายและทรงที่เรียบพอจะใส่ซ้ำได้ทั้งสัปดาห์</p>
    </section>

    <section class="about-grid">
        <div>
            <h2>สิ่งที่ร้านมี</h2>
            <p>เสื้อเชิ้ต เสื้อยืด เสื้อโปโล กางเกงขายาว กางเกงขากว้าง และกางเกงชิโน เว็บนี้เอาไว้เปิดดูคอลเลกชัน ไม่มีตะกร้าสินค้าและไม่รับสั่งซื้อ</p>
        </div>
        <div>
            <h2>ไซส์ในคอลเลกชัน</h2>
            <p>ไซส์เสื้อใช้ S ถึง XL ตามตารางด้านล่าง ไซส์กางเกงขายาวใช้เอว 28 ถึง 36 และกางเกงขากว้างใช้ S ถึง XL ตัวเลขนี้เป็นข้อมูลประกอบการดูสินค้า</p>
        </div>
    </section>

    <section class="section size-chart-block">
        @include('partials.shirt-size-chart')
    </section>
@endsection
