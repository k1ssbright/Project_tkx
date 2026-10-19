<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductViewTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_product_records_view_and_is_hidden_from_public(): void
    {
        $this->seed();

        $product = Product::query()->first();
        $this->assertNotNull($product);

        $initialViews = ProductView::query()->where('product_id', $product->id)->count();

        // Customer views the product
        $this->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee($product->name)
            ->assertDontSee('ยอดกดเข้าดู')
            ->assertDontSee('views-badge');

        $this->assertSame($initialViews + 1, ProductView::query()->where('product_id', $product->id)->count());
    }

    public function test_admin_can_view_product_views_and_filter_by_periods(): void
    {
        $this->seed();

        $admin = User::query()->where('is_admin', true)->first();
        $product = Product::query()->first();

        // Create views across different historical dates
        // 1 view 10 days ago (within 1m)
        ProductView::query()->create([
            'product_id' => $product->id,
            'ip_address' => '127.0.0.1',
            'viewed_at' => now()->subDays(10),
        ]);

        // 1 view 45 days ago (within 3m, but NOT 1m)
        ProductView::query()->create([
            'product_id' => $product->id,
            'ip_address' => '127.0.0.1',
            'viewed_at' => now()->subDays(45),
        ]);

        // 1 view 120 days ago (within 6m, but NOT 3m)
        ProductView::query()->create([
            'product_id' => $product->id,
            'ip_address' => '127.0.0.1',
            'viewed_at' => now()->subDays(120),
        ]);

        // 1 view 250 days ago (within 1y, but NOT 6m)
        ProductView::query()->create([
            'product_id' => $product->id,
            'ip_address' => '127.0.0.1',
            'viewed_at' => now()->subDays(250),
        ]);

        // 1. Check 1 Month (1m) filter in admin products
        $this->actingAs($admin)
            ->get('/admin/products?period=1m')
            ->assertOk()
            ->assertSee('ยอดกดเข้าดู')
            ->assertSee('1 เดือน');

        // 2. Check 3 Months (3m) filter in admin products
        $this->actingAs($admin)
            ->get('/admin/products?period=3m')
            ->assertOk()
            ->assertSee('3 เดือน');

        // 3. Check 6 Months (6m) filter in admin products
        $this->actingAs($admin)
            ->get('/admin/products?period=6m')
            ->assertOk()
            ->assertSee('6 เดือน');

        // 4. Check 1 Year (1y) filter in admin products
        $this->actingAs($admin)
            ->get('/admin/products?period=1y')
            ->assertOk()
            ->assertSee('1 ปี');

        // 5. Check All-time (all) filter in admin products
        $this->actingAs($admin)
            ->get('/admin/products?period=all')
            ->assertOk()
            ->assertSee('ทั้งหมด');

        // 6. Check Admin Dashboard with period filter
        $this->actingAs($admin)
            ->get('/admin?period=3m')
            ->assertOk()
            ->assertSee('ภาพรวมระบบและสถิติ')
            ->assertSee('ยอดเข้าชมสินค้า')
            ->assertSee('สินค้าที่มีคนกดเข้าดูสูงสุด');
    }
}
