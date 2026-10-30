<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleViewStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_page_visits_are_counted_except_for_admins(): void
    {
        $this->seed();
        $article = Article::query()->where('slug', 'all-white-outfit')->firstOrFail();

        $this->get('/news/all-white-outfit')->assertOk();
        $this->get('/news/all-white-outfit')->assertOk();
        $this->actingAs(User::factory()->create())->get('/news/all-white-outfit')->assertOk();

        $this->assertSame(3, $article->views()->count());

        $this->actingAs(User::factory()->admin()->create())->get('/news/all-white-outfit')->assertOk();

        $this->assertSame(3, $article->views()->count());
    }

    public function test_admin_news_list_shows_total_and_recent_view_counts(): void
    {
        $this->seed();
        $article = Article::query()->where('slug', 'all-white-outfit')->firstOrFail();

        $this->travelTo(now());

        $article->views()->createMany([
            ['viewed_at' => now()->subDays(2)],
            ['viewed_at' => now()->subDays(20)],
            ['viewed_at' => now()->subDays(45)],
            ['viewed_at' => now()->subMonths(6)],
        ]);

        $this->get('/news/all-white-outfit')->assertDontSee('เข้าชมทั้งหมด');

        $articles = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/news')
            ->assertOk()
            ->assertSee('เข้าชมทั้งหมด')
            ->viewData('articles');

        $listed = $articles->firstWhere('id', $article->id);

        $this->assertSame(5, $listed->views_count);
        $this->assertSame(3, $listed->recent_views_count);
    }

    public function test_news_analytics_ranks_articles_and_compares_with_previous_period(): void
    {
        $this->seed();
        $outfit = Article::query()->where('slug', 'all-white-outfit')->firstOrFail();
        $fabric = Article::query()->where('slug', 'linen-or-cotton')->firstOrFail();

        $this->travelTo(now());

        $outfit->views()->createMany([
            ['viewed_at' => now()->subDays(1)],
            ['viewed_at' => now()->subDays(40)],
            ['viewed_at' => now()->subDays(50)],
        ]);
        $fabric->views()->createMany([
            ['viewed_at' => now()->subDays(2)],
            ['viewed_at' => now()->subDays(3)],
            ['viewed_at' => now()->subDays(4)],
            ['viewed_at' => now()->subMonths(3)],
        ]);

        $this->get('/admin/news-views')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/news-views')->assertForbidden();

        $response = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/news-views')
            ->assertOk()
            ->assertSee('ยอดเข้าชมข่าวสาร')
            ->assertViewHas('period', '30d')
            ->assertViewHas('views', 4)
            ->assertViewHas('previousViews', 2)
            ->assertViewHas('viewsDelta', 100.0)
            ->assertViewHas('readArticles', 2)
            ->assertViewHas('allTimeViews', 7);

        $articles = $response->viewData('articles');
        $this->assertSame($fabric->id, $articles->first()->id);
        $this->assertSame(3, $articles->first()->period_views_count);
        $this->assertSame(1, $articles->firstWhere('id', $outfit->id)->period_views_count);
        $this->assertSame($fabric->id, $response->viewData('leader')->id);
        $this->assertSame(4, array_sum(array_column($response->viewData('trend')['buckets'], 'views')));

        $this->get('/admin/news-views?period=12m')->assertOk()->assertViewHas('views', 7);
        $this->get('/admin/news-views?period=forever')->assertSessionHasErrors('period');
    }
}
