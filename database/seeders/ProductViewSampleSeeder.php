<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Database\Seeder;

/**
 * Sample product clicks for previewing the admin dashboard. Rows are flagged with is_sample,
 * so `php artisan views:clear-samples` removes them without touching real clicks.
 */
class ProductViewSampleSeeder extends Seeder
{
    private const int MONTHS = 24;

    public function run(): void
    {
        $products = Product::query()->get(['id', 'is_featured']);

        if ($products->isEmpty()) {
            return;
        }

        mt_srand(2026);

        ProductView::query()->where('is_sample', true)->delete();

        $hourWeights = [0 => 2, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 2, 7 => 4, 8 => 5, 9 => 5, 10 => 6, 11 => 7, 12 => 10, 13 => 8, 14 => 6, 15 => 6, 16 => 7, 17 => 8, 18 => 10, 19 => 13, 20 => 14, 21 => 13, 22 => 9, 23 => 5];
        $productWeights = $products->mapWithKeys(fn (Product $product) => [$product->id => $product->is_featured ? 5 : mt_rand(1, 4)])->all();

        $start = now()->subMonths(self::MONTHS)->startOfDay();
        $rows = [];

        for ($day = $start->copy(); $day->lte(now()); $day->addDay()) {
            $growth = 1 + ($start->diffInDays($day) / 365) * 0.45;
            $weekend = $day->isWeekend() ? 1.5 : 1;
            $season = in_array($day->month, [11, 12, 4], true) ? 1.3 : 1;
            $clicks = (int) round(mt_rand(14, 34) * $growth * $weekend * $season);

            for ($i = 0; $i < $clicks; $i++) {
                $viewedAt = $day->copy()->setTime($this->weightedPick($hourWeights), mt_rand(0, 59), mt_rand(0, 59));

                if ($viewedAt->isFuture()) {
                    continue;
                }

                $rows[] = [
                    'product_id' => $this->weightedPick($productWeights),
                    'viewed_at' => $viewedAt,
                    'is_sample' => true,
                ];
            }

            if (count($rows) >= 2000) {
                ProductView::query()->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            ProductView::query()->insert($rows);
        }
    }

    /**
     * @param  array<int, int>  $weights
     */
    private function weightedPick(array $weights): int
    {
        $roll = mt_rand(1, array_sum($weights));

        foreach ($weights as $key => $weight) {
            $roll -= $weight;

            if ($roll <= 0) {
                return $key;
            }
        }

        return array_key_first($weights);
    }
}
