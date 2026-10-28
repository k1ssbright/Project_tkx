<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NewsManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_news_with_images(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin/news')->assertOk()->assertSee('จัดการข่าวสาร');
        $this->get('/admin/news/create')->assertOk()->assertSee('เพิ่มข่าวสาร');

        $this->post('/admin/news', [
            'title' => 'คอลเลกชันใหม่มาแล้ว',
            'slug' => 'new-collection',
            'excerpt' => 'สรุปข่าวคอลเลกชันใหม่',
            'body' => "ย่อหน้าแรกของข่าว\n\nย่อหน้าที่สอง <b>ตัวหนา</b>",
            'image' => UploadedFile::fake()->image('cover.jpg', 1200, 800),
            'gallery' => [
                UploadedFile::fake()->image('one.jpg', 800, 600),
                UploadedFile::fake()->image('two.png', 800, 600),
            ],
        ])->assertRedirect(route('admin.news.index'));

        $article = Article::query()->where('slug', 'new-collection')->firstOrFail();
        $gallery = $article->images()->get();

        $this->assertCount(2, $gallery);
        $this->assertFileExists(public_path($article->image));
        $gallery->each(fn ($image) => $this->assertFileExists(public_path($image->image)));

        $this->get('/news')->assertOk()->assertSee('คอลเลกชันใหม่มาแล้ว')->assertSee('สรุปข่าวคอลเลกชันใหม่');
        $this->get('/news/new-collection')
            ->assertOk()
            ->assertSee('ย่อหน้าแรกของข่าว')
            ->assertSee('&lt;b&gt;ตัวหนา&lt;/b&gt;', false)
            ->assertSee($gallery[0]->image)
            ->assertSee($gallery[1]->image);

        $oldCover = $article->image;
        $removedImage = $gallery[0];

        $this->put('/admin/news/new-collection', [
            'title' => 'คอลเลกชันใหม่ (อัปเดต)',
            'slug' => 'new-collection',
            'excerpt' => 'สรุปใหม่',
            'body' => 'เนื้อหาใหม่',
            'image' => UploadedFile::fake()->image('cover2.jpg', 1200, 800),
            'remove_images' => [$removedImage->id],
            'gallery' => [UploadedFile::fake()->image('three.webp', 800, 600)],
        ])->assertRedirect(route('admin.news.index'));

        $article->refresh();

        $this->assertSame('คอลเลกชันใหม่ (อัปเดต)', $article->title);
        $this->assertNotSame($oldCover, $article->image);
        $this->assertFileDoesNotExist(public_path($oldCover));
        $this->assertModelMissing($removedImage);
        $this->assertFileDoesNotExist(public_path($removedImage->image));
        $this->assertCount(2, $article->images()->get());

        $remainingImages = $article->images()->pluck('image')->push($article->image);

        $this->delete('/admin/news/new-collection')->assertRedirect(route('admin.news.index'));

        $this->assertModelMissing($article);
        $remainingImages->each(fn (string $path) => $this->assertFileDoesNotExist(public_path($path)));
    }

    public function test_news_requires_cover_image_and_content(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->post('/admin/news', [
            'title' => '',
            'excerpt' => '',
            'body' => '',
        ])->assertSessionHasErrors(['title', 'excerpt', 'body', 'image']);

        $this->assertSame(0, Article::query()->count());
    }

    public function test_seeded_cover_images_are_kept_when_news_is_deleted(): void
    {
        $this->seed();
        $this->actingAs(User::factory()->admin()->create());

        $article = Article::query()->where('slug', 'linen-or-cotton')->firstOrFail();

        $this->delete('/admin/news/linen-or-cotton')->assertRedirect(route('admin.news.index'));

        $this->assertModelMissing($article);
        $this->assertFileExists(public_path($article->image));
    }
}
