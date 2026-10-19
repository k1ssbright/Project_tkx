<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_non_admins_cannot_access_admin_articles(): void
    {
        $this->get('/admin/articles')
            ->assertRedirect(route('login'));

        $user = User::query()->create([
            'name' => 'Regular Member',
            'email' => 'member@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/articles')
            ->assertForbidden();
    }

    public function test_admin_can_manage_articles(): void
    {
        $this->seed();

        $admin = User::query()->where('is_admin', true)->first();
        $this->actingAs($admin);

        // List page
        $this->get('/admin/articles')
            ->assertOk()
            ->assertSee('จัดการหน้าข่าวสาร');

        // Create page
        $this->get('/admin/articles/create')
            ->assertOk()
            ->assertSee('เพิ่มข่าวสารใหม่')
            ->assertSee('หัวข้อข่าวสาร');

        // Store
        $response = $this->post('/admin/articles', [
            'title' => 'ข่าวทดสอบพิเศษ',
            'excerpt' => 'สรุปข่าวทดสอบพิเศษสำหรับระบบ',
            'body' => "เนื้อหาย่อหน้าที่หนึ่ง\n\nเนื้อหาย่อหน้าที่สอง",
            'image' => UploadedFile::fake()->image('news.jpg', 800, 600),
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->followRedirects($response)->assertSee('เพิ่มข่าวสารเรียบร้อยแล้ว');

        $article = Article::query()->where('title', 'ข่าวทดสอบพิเศษ')->first();
        $this->assertNotNull($article);
        $this->assertFileExists(public_path($article->image));

        // Public show page
        $this->get('/articles/'.$article->slug)
            ->assertOk()
            ->assertSee('ข่าวทดสอบพิเศษ')
            ->assertSee('เนื้อหาย่อหน้าที่หนึ่ง')
            ->assertSee('เนื้อหาย่อหน้าที่สอง');

        // Public index page
        $this->get('/articles')
            ->assertOk()
            ->assertSee('ข่าวสารและกิจกรรม')
            ->assertSee('ข่าวทดสอบพิเศษ');

        // Edit
        $this->get('/admin/articles/'.$article->slug.'/edit')
            ->assertOk()
            ->assertSee('แก้ไขข่าวสาร');

        // Update
        $this->put('/admin/articles/'.$article->slug, [
            'title' => 'ข่าวทดสอบพิเศษ (แก้ไขแล้ว)',
            'excerpt' => 'สรุปใหม่',
            'body' => 'เนื้อหาใหม่',
        ])->assertRedirect(route('admin.articles.index'));

        $article->refresh();
        $this->assertSame('ข่าวทดสอบพิเศษ (แก้ไขแล้ว)', $article->title);

        // Delete
        $imagePath = $article->image;
        $this->delete('/admin/articles/'.$article->slug)
            ->assertRedirect(route('admin.articles.index'));

        $this->assertModelMissing($article);
        $this->assertFileDoesNotExist(public_path($imagePath));
    }

    public function test_homepage_does_not_show_articles_section(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertDontSee('id="articles"', false);
    }
}
