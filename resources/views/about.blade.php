@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา')

@section('content')
    <section class="page-intro about">
        <p class="eyebrow">About TKX</p>
        <h1>ร้านเสื้อผ้าที่เลือกเฉพาะโทนขาว</h1>
        <p class="lede">TKX รวมเสื้อและกางเกงสีขาว ครีม และเบจไว้ในที่เดียว เน้นผ้าที่ใส่สบายและทรงที่เรียบพอจะใส่ซ้ำได้ทั้งสัปดาห์</p>
    </section>

    <section class="about-grid">
        <div>
            <h2>สิ่งที่ร้านมี</h2>
            <p>เสื้อเชิ้ต เสื้อยืด เสื้อโปโล กางเกงขายาว กางเกงขากว้าง และกางเกงชิโน ไม่มีตะกร้าสินค้าในเว็บนี้ เพราะตั้งใจให้เป็นหน้าโชว์คอลเลกชัน</p>
        </div>
        <div>
            <h2>วิธีเลือกไซส์</h2>
            <p>ไซส์เสื้อใช้ S ถึง XL ตามตารางด้านล่าง ไซส์กางเกงขายาวใช้เอว 28 ถึง 36 และกางเกงขากว้างใช้ S ถึง XL ถ้าไม่แน่ใจ สอบถามที่ร้านก่อนเลือก</p>
        </div>
        <div>
            <h2>ติดต่อร้าน</h2>
            <p>โชว์รูมนัดหมายล่วงหน้า<br>อีเมล hello@tkx.local</p>
        </div>
    </section>

    <section class="section size-chart-block">
        @include('partials.shirt-size-chart')
    </section>
@endsection
