<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_pages_show_white_wardrobe_products(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('เสื้อผ้าและกางเกง')
            ->assertSee('เสื้อเชิ้ตลินินสีงาช้าง')
            ->assertSee('เข้าสู่ระบบ')
            ->assertSee('สมัครสมาชิก')
            ->assertDontSee('ใส่โทนขาวทั้งชุดโดยไม่ดูจืด')
            ->assertDontSee('อ่านต่อ')
            ->assertDontSee('จัดการสินค้า');

        $this->get('/news')
            ->assertOk()
            ->assertSee('ข่าวสาร')
            ->assertSee('ใส่โทนขาวทั้งชุดโดยไม่ดูจืด')
            ->assertSee('อ่านต่อ');

        $this->get('/news/linen-or-cotton')
            ->assertOk()
            ->assertSee('ลินินกับคอตตอน เลือกยังไง')
            ->assertSee('เสื้อเชิ้ตลินินเหมาะกับวันที่อยู่กลางแจ้ง');

        $this->get('/articles/linen-or-cotton')
            ->assertRedirect('/news/linen-or-cotton')
            ->assertStatus(301);

        $this->get('/shop?category=pants')
            ->assertOk()
            ->assertSee('กางเกงขากว้างสีขาว')
            ->assertDontSee('เสื้อยืดคอตตอนขาว');

        $this->get('/products/white-cotton-tee')
            ->assertOk()
            ->assertSee('690 บาท')
            ->assertSee('คอตตอนคอมแพค')
            ->assertSee('ตารางไซส์เสื้อ')
            ->assertSee('รอบอก');

        $this->get('/products/white-wide-pants')
            ->assertOk()
            ->assertDontSee('ตารางไซส์เสื้อ');

        $this->get('/about')
            ->assertOk()
            ->assertSee('เกี่ยวกับเรา')
            ->assertSee('900 เมตร')
            ->assertSee('Our Facility')
            ->assertSee('0612324516');

        $this->get('/shop?category=missing')->assertNotFound();
    }
}
