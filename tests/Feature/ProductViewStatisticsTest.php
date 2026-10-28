<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductViewStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_visits_are_counted_except_for_admins(): void
    {
        $this->seed();
        $product = Product::query()->where('slug', 'white-cotton-tee')->firstOrFail();

        $this->get('/products/white-cotton-tee')->assertOk();
        $this->get('/products/white-cotton-tee')->assertOk();
        $this->actingAs(User::factory()->create())->get('/products/white-cotton-tee')->assertOk();

        $this->assertSame(3, $product->views()->count());

        $this->actingAs(User::factory()->admin()->create())->get('/products/white-cotton-tee')->assertOk();

        $this->assertSame(3, $product->views()->count());
    }

    public function test_view_counts_are_shown_only_in_admin_and_filtered_by_period(): void
    {
        $this->seed();
        $tee = Product::query()->where('slug', 'white-cotton-tee')->firstOrFail();
        $shirt = Product::query()->where('slug', 'ivory-linen-shirt')->firstOrFail();

        $this->travelTo(now());

        $tee->views()->createMany([
            ['viewed_at' => now()->subDays(3)],
            ['viewed_at' => now()->subDays(10)],
            ['viewed_at' => now()->subMonths(2)],
            ['viewed_at' => now()->subMonths(5)],
            ['viewed_at' => now()->subMonths(11)],
            ['viewed_at' => now()->subMonths(13)],
        ]);
        $shirt->views()->createMany([
            ['viewed_at' => now()->subMonths(4)],
            ['viewed_at' => now()->subMonths(4)],
            ['viewed_at' => now()->subMonths(4)],
        ]);

        $this->get('/products/white-cotton-tee')->assertDontSee('จำนวนเข้าชม');

        $this->actingAs(User::factory()->admin()->create());

        $expectedTotals = ['1m' => 3, '3m' => 4, '6m' => 8, '12m' => 9];

        foreach ($expectedTotals as $period => $total) {
            $this->get('/admin/views?period='.$period)
                ->assertOk()
                ->assertViewHas('totalViews', $total);
        }

        $sixMonths = $this->get('/admin/views?period=6m')->viewData('products');
        $this->assertSame(5, $sixMonths->firstWhere('id', $tee->id)->views_count);
        $this->assertSame(3, $sixMonths->firstWhere('id', $shirt->id)->views_count);
        $this->assertSame($tee->id, $sixMonths->first()->id);

        $threeMonths = $this->get('/admin/views?period=3m')->viewData('products');
        $this->assertSame(0, $threeMonths->firstWhere('id', $shirt->id)->views_count);

        $this->get('/admin/views')->assertOk()->assertViewHas('period', '1m')->assertSee('สถิติการเข้าชมสินค้า');
        $this->get('/admin/views?period=forever')->assertSessionHasErrors('period');
    }
}
