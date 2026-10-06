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
            ->assertSee('เสื้อเชิ้ตลินินสีงาช้าง');

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
            ->assertSee('ตารางไซส์เสื้อ');

        $this->get('/shop?category=missing')->assertNotFound();
    }
}
