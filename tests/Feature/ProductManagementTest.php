<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_created_updated_and_deleted(): void
    {
        $this->seed();

        $this->get('/manage/products')
            ->assertOk()
            ->assertSee('จัดการสินค้า')
            ->assertSee('เสื้อเชิ้ตลินินสีงาช้าง');

        $categoryId = Category::query()->where('slug', 'shirts')->value('id');

        $this->post('/manage/products', [
            'name' => 'เสื้อทดสอบ',
            'category_id' => $categoryId,
            'price' => 990,
            'fabric' => 'คอตตอน',
            'description' => 'คำอธิบายทดสอบ',
            'colors' => 'ขาว, ครีม',
            'sizes' => 'S, M',
            'image' => UploadedFile::fake()->image('tee.jpg', 400, 500),
            'is_featured' => '1',
        ])->assertRedirect(route('products.manage.index'));

        $product = Product::query()->where('name', 'เสื้อทดสอบ')->first();

        $this->assertNotNull($product);
        $this->assertTrue($product->is_featured);
        $this->assertSame(['ขาว', 'ครีม'], $product->colors);
        $this->assertSame(['S', 'M'], $product->sizes);
        $this->assertFileExists(public_path($product->image));

        $this->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee('เสื้อทดสอบ')
            ->assertSee('990 บาท');

        $this->put('/manage/products/'.$product->slug, [
            'name' => 'เสื้อทดสอบแก้',
            'slug' => $product->slug,
            'category_id' => $categoryId,
            'price' => 1000,
            'fabric' => 'ลินิน',
            'description' => 'คำอธิบายใหม่',
            'colors' => 'ขาว',
            'sizes' => 'M',
        ])->assertRedirect(route('products.manage.index'));

        $product->refresh();
        $image = $product->image;

        $this->assertSame('เสื้อทดสอบแก้', $product->name);
        $this->assertSame(1000, $product->price);
        $this->assertFalse($product->is_featured);

        $this->delete('/manage/products/'.$product->slug)
            ->assertRedirect(route('products.manage.index'));

        $this->assertModelMissing($product);
        $this->assertFileDoesNotExist(public_path($image));
    }
}
