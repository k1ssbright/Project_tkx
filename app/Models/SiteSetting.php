<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    /**
     * Image slots that admins can replace. Each key is also the setting key that stores the uploaded path.
     *
     * @var array<string, array{group: string, label: string, usage: string, default: string, shape: 'hero'|'logo'|'photo'}>
     */
    public const array IMAGES = [
        'logo_image' => ['group' => 'ทั่วไป', 'label' => 'โลโก้', 'usage' => 'เมนูด้านบน ท้ายเว็บ และหลังบ้าน', 'default' => 'images/nanocircuit-logo.png', 'shape' => 'logo'],
        'hero_image' => ['group' => 'ทั่วไป', 'label' => 'แบนเนอร์หน้าแรก', 'usage' => 'หน้าแรก และหน้าเข้าสู่ระบบ/สมัครสมาชิก', 'default' => 'images/hero-wardrobe.jpg', 'shape' => 'hero'],
        'contact_cover_image' => ['group' => 'ทั่วไป', 'label' => 'หน้าปกหน้าติดต่อเรา', 'usage' => 'ด้านบนของหน้าติดต่อเรา', 'default' => 'images/about/cover.webp', 'shape' => 'hero'],
        'about_cover_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'หน้าปก', 'usage' => 'ด้านบนของหน้า', 'default' => 'images/about/cover.webp', 'shape' => 'hero'],
        'about_owners_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'ทีมงาน', 'usage' => 'ส่วนแนะนำ Nano Circuit', 'default' => 'images/about/owners.webp', 'shape' => 'photo'],
        'about_track_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'สนาม', 'usage' => 'ส่วน The Professional Kart Thailand', 'default' => 'images/about/track.webp', 'shape' => 'photo'],
        'about_gallery_1_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'แกลเลอรี รูปที่ 1', 'usage' => 'ส่วน Dynamic Racing Experience', 'default' => 'images/about/gallery-1.webp', 'shape' => 'photo'],
        'about_gallery_2_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'แกลเลอรี รูปที่ 2', 'usage' => 'ส่วน Dynamic Racing Experience', 'default' => 'images/about/gallery-2.webp', 'shape' => 'photo'],
        'about_gallery_3_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'แกลเลอรี รูปที่ 3', 'usage' => 'ส่วน Dynamic Racing Experience', 'default' => 'images/about/gallery-3.webp', 'shape' => 'photo'],
        'about_gallery_4_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'แกลเลอรี รูปที่ 4', 'usage' => 'ส่วน Dynamic Racing Experience', 'default' => 'images/about/gallery-4.webp', 'shape' => 'photo'],
        'about_grand_opening_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'ผลงาน: Grand Opening', 'usage' => 'ส่วน Portfolio', 'default' => 'images/about/grand-opening.webp', 'shape' => 'photo'],
        'about_sprint_race_image' => ['group' => 'หน้าเกี่ยวกับเรา', 'label' => 'ผลงาน: Sprint Race #1', 'usage' => 'ส่วน Portfolio', 'default' => 'images/about/sprint-race.webp', 'shape' => 'photo'],
    ];

    public const string UPLOAD_DIRECTORY = 'images/site';

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * @return array<string, string>
     */
    public static function imagePaths(): array
    {
        $stored = static::query()->whereIn('key', array_keys(self::IMAGES))->pluck('value', 'key');

        return collect(self::IMAGES)
            ->map(function (array $image, string $key) use ($stored): string {
                $path = $stored->get($key);

                return filled($path) && is_file(public_path($path)) ? $path : $image['default'];
            })
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function imageUrls(): array
    {
        return collect(static::imagePaths())
            ->map(fn (string $path): string => asset($path).(is_file(public_path($path)) ? '?v='.filemtime(public_path($path)) : ''))
            ->all();
    }
}
