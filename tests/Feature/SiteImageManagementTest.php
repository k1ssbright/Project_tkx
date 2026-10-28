<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SiteImageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_every_replaceable_image_grouped_by_page(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get('/admin/site-images')
            ->assertOk()
            ->assertSee('รูปหน้าเว็บ')
            ->assertSee('ทั่วไป')
            ->assertSee('หน้าเกี่ยวกับเรา')
            ->assertDontSee('กลับไปใช้รูปเริ่มต้น');

        foreach (SiteSetting::IMAGES as $key => $image) {
            $response->assertSee('name="images['.$key.']"', false);
            $response->assertSee($image['default'], false);
        }
    }

    public function test_admin_can_replace_several_images_at_once_and_reset_one(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->put('/admin/site-images', [
            'images' => [
                'hero_image' => UploadedFile::fake()->image('hero.jpg', 1600, 700),
                'about_gallery_2_image' => UploadedFile::fake()->image('gallery.webp', 900, 600),
                'contact_cover_image' => UploadedFile::fake()->image('contact.png', 1600, 700),
            ],
        ])->assertRedirect(route('admin.site-images.edit'));

        $heroImage = SiteSetting::get('hero_image');
        $galleryImage = SiteSetting::get('about_gallery_2_image');
        $contactImage = SiteSetting::get('contact_cover_image');

        $this->assertStringStartsWith('images/site/hero-image-', $heroImage);
        $this->assertFileExists(public_path($heroImage));
        $this->assertFileExists(public_path($galleryImage));
        $this->assertFileExists(public_path($contactImage));
        $this->assertNull(SiteSetting::get('about_cover_image'));

        $this->get('/')->assertSee($heroImage, false);
        $this->get('/about')
            ->assertSee($galleryImage, false)
            ->assertSee('images/about/cover.webp', false);
        $this->get('/contact')
            ->assertSee($contactImage, false)
            ->assertDontSee('images/about/cover.webp', false);

        $this->put('/admin/site-images', [
            'images' => ['hero_image' => UploadedFile::fake()->image('hero-2.jpg', 1600, 700)],
        ])->assertRedirect(route('admin.site-images.edit'));

        $replacedHeroImage = SiteSetting::get('hero_image');
        $this->assertFileDoesNotExist(public_path($heroImage));
        $this->assertFileExists(public_path($replacedHeroImage));

        $this->get('/admin/site-images')->assertSee('กลับไปใช้รูปเริ่มต้น');

        $this->delete('/admin/site-images/about_gallery_2_image')
            ->assertRedirect(route('admin.site-images.edit'));

        $this->assertNull(SiteSetting::get('about_gallery_2_image'));
        $this->assertFileDoesNotExist(public_path($galleryImage));
        $this->get('/about')->assertSee('images/about/gallery-2.webp', false);

        $this->delete('/admin/site-images/hero_image');
        $this->delete('/admin/site-images/contact_cover_image');
        $this->assertFileDoesNotExist(public_path($replacedHeroImage));
        $this->assertFileDoesNotExist(public_path($contactImage));
    }

    public function test_uploads_must_be_images_for_known_slots(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->from('/admin/site-images')
            ->put('/admin/site-images', [
                'images' => ['logo_image' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf')],
            ])
            ->assertRedirect('/admin/site-images')
            ->assertSessionHasErrors('images.logo_image');

        $this->from('/admin/site-images')
            ->put('/admin/site-images', [
                'images' => ['unknown_image' => UploadedFile::fake()->image('x.jpg')],
            ])
            ->assertSessionHasErrors('images');

        $this->from('/admin/site-images')
            ->put('/admin/site-images', [])
            ->assertSessionHasErrors('images');

        $this->delete('/admin/site-images/unknown_image')->assertNotFound();
        $this->assertSame(0, SiteSetting::query()->count());
    }

    public function test_members_cannot_change_site_images(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/site-images')->assertForbidden();
        $this->put('/admin/site-images', [
            'images' => ['hero_image' => UploadedFile::fake()->image('hero.jpg')],
        ])->assertForbidden();
        $this->delete('/admin/site-images/hero_image')->assertForbidden();
    }
}
