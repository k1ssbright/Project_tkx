<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_created_updated_and_deleted(): void
    {
        $this->seed();

        $admin = User::query()->where('is_admin', true)->first();
        $this->actingAs($admin);

        $this->get('/admin/products')
            ->assertOk()
            ->assertSee('จัดการสินค้า')
            ->assertSee('เสื้อเชิ้ตลินินสีงาช้าง')
            ->assertSee('sweetalert2@11', false)
            ->assertSee('data-confirm="ลบสินค้านี้ใช่ไหม"', false)
            ->assertDontSee('return confirm(', false);

        $categoryId = Category::query()->where('slug', 'shirts')->value('id');

        $response = $this->post('/admin/products', [
            'name' => 'เสื้อทดสอบ',
            'category_id' => $categoryId,
            'price' => 990,
            'fabric' => 'คอตตอน',
            'description' => 'คำอธิบายทดสอบ',
            'colors' => 'ขาว, ครีม',
            'sizes' => 'S, M',
            'image' => UploadedFile::fake()->image('tee.jpg', 400, 500),
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->followRedirects($response)->assertSee('เพิ่มสินค้าแล้ว', false);

        $product = Product::query()->where('name', 'เสื้อทดสอบ')->first();

        $this->assertNotNull($product);
        $this->assertTrue($product->is_featured);
        $this->assertSame(['ขาว', 'ครีม'], $product->colors);
        $this->assertSame(['S', 'M'], $product->sizes);
        $this->assertFileExists(public_path($product->image));

        $this->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee('เสื้อทดสอบ')
            ->assertSee('990 บาท')
            ->assertSee('กดที่รูปเพื่อดูขนาดเต็มตามไฟล์ต้นฉบับ');

        $this->get('/admin/products/'.$product->slug.'/edit')
            ->assertOk()
            ->assertSee('select2.min.js', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('name="colors"', false)
            ->assertSee('name="sizes"', false)
            ->assertDontSee('name="colors[]"', false)
            ->assertDontSee('name="sizes[]"', false);

        $this->put('/admin/products/'.$product->slug, [
            'name' => 'เสื้อทดสอบแก้',
            'slug' => $product->slug,
            'category_id' => $categoryId,
            'price' => 1000,
            'fabric' => 'ลินิน',
            'description' => 'คำอธิบายใหม่',
            'colors' => 'ขาว',
            'sizes' => 'M',
        ])->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $image = $product->image;

        $this->assertSame('เสื้อทดสอบแก้', $product->name);
        $this->assertSame(1000, $product->price);
        $this->assertFalse($product->is_featured);

        $this->delete('/admin/products/'.$product->slug)
            ->assertRedirect(route('admin.products.index'));

        $this->assertModelMissing($product);
        $this->assertFileDoesNotExist(public_path($image));
    }
}
