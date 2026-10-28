<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_summarizes_product_clicks_for_the_selected_period(): void
    {
        $this->seed();
        $this->travelTo(now()->setTime(12, 0));

        $tee = Product::query()->where('slug', 'white-cotton-tee')->firstOrFail();
        $pants = Product::query()->where('slug', 'cream-straight-pants')->firstOrFail();

        $tee->views()->createMany([
            ['viewed_at' => now()->subDays(1)],
            ['viewed_at' => now()->subDays(2)],
            ['viewed_at' => now()->subDays(3)],
            ['viewed_at' => now()->subDays(10)],
        ]);
        $pants->views()->createMany([
            ['viewed_at' => now()->subDays(4)],
            ['viewed_at' => now()->subDays(12)],
        ]);

        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get('/admin?period=7d')
            ->assertOk()
            ->assertSee('ภาพรวมการเข้าชมสินค้า')
            ->assertDontSee('คลิกตัวอย่าง');

        $kpis = $response->viewData('kpis');
        $this->assertSame(4, $kpis[0]['raw']);
        $this->assertSame(100.0, $kpis[0]['delta']);
        $this->assertSame(2, $kpis[1]['raw']);
        $this->assertSame('เสื้อยืดคอตตอนขาว', $kpis[3]['value']);

        $topProducts = $response->viewData('topProducts');
        $this->assertSame(['เสื้อยืดคอตตอนขาว', 'กางเกงขายาวสีครีม'], $topProducts->pluck('label')->all());
        $this->assertSame(75.0, $topProducts->first()['share']);

        $unviewed = $response->viewData('unviewedProducts');
        $this->assertFalse($unviewed->contains('id', $tee->id));
        $this->assertTrue($unviewed->contains('slug', 'ivory-linen-shirt'));

        $this->get('/admin?period=30d')->assertViewHas('kpis', fn (array $kpis) => $kpis[0]['raw'] === 6);
        $this->get('/admin?period=forever')->assertSessionHasErrors('period');
    }

    public function test_sample_clicks_are_flagged_and_can_be_cleared_without_touching_real_clicks(): void
    {
        $this->seed();
        $product = Product::query()->firstOrFail();
        $product->views()->create(['viewed_at' => now()->subDay()]);
        ProductView::query()->insert(['product_id' => $product->id, 'viewed_at' => now()->subDays(2), 'is_sample' => true]);

        $this->actingAs(User::factory()->admin()->create());
        $this->get('/admin')->assertOk()->assertSee('คลิกตัวอย่าง (mock) 1 รายการ', false);

        $this->artisan('views:clear-samples')->assertSuccessful();

        $this->assertSame(1, ProductView::query()->count());
        $this->assertFalse(ProductView::query()->first()->is_sample);
        $this->get('/admin')->assertDontSee('คลิกตัวอย่าง');
    }

    public function test_dashboard_shows_empty_state_and_is_admin_only(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->get('/admin')->assertOk()->assertSee('ยังไม่มีคนกดเข้าดูสินค้า');

        $this->actingAs(User::factory()->create());
        $this->get('/admin')->assertForbidden();
    }
}
