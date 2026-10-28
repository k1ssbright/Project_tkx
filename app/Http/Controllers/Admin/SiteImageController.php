<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteImageController extends Controller
{
    public function edit(): View
    {
        $paths = SiteSetting::imagePaths();
        $urls = SiteSetting::imageUrls();

        $groups = collect(SiteSetting::IMAGES)
            ->map(fn (array $image, string $key): array => [
                ...$image,
                'key' => $key,
                'url' => $urls[$key],
                'isDefault' => $paths[$key] === $image['default'],
            ])
            ->groupBy('group');

        return view('admin.site-images', [
            'groups' => $groups,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array:'.implode(',', array_keys(SiteSetting::IMAGES))],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'images.required' => 'เลือกรูปที่ต้องการเปลี่ยนอย่างน้อย 1 รูป',
            'images.array' => 'เลือกรูปที่ต้องการเปลี่ยนอย่างน้อย 1 รูป',
            'images.*.image' => 'ไฟล์รูปต้องเป็น jpg, png หรือ webp',
            'images.*.mimes' => 'ไฟล์รูปต้องเป็น jpg, png หรือ webp',
            'images.*.max' => 'รูปต้องมีขนาดไม่เกิน 5 MB',
        ]);

        foreach ($request->file('images') as $key => $file) {
            $previousImage = SiteSetting::get($key);

            $name = Str::slug($key).'-'.Str::uuid()->toString().'.'.$file->extension();
            $file->move(public_path(SiteSetting::UPLOAD_DIRECTORY), $name);

            SiteSetting::put($key, SiteSetting::UPLOAD_DIRECTORY.'/'.$name);
            $this->deleteUploadedImage($previousImage);
        }

        return redirect()
            ->route('admin.site-images.edit')
            ->with('status', 'บันทึกรูปแล้ว');
    }

    public function destroy(string $image): RedirectResponse
    {
        $previousImage = SiteSetting::get($image);

        SiteSetting::put($image, null);
        $this->deleteUploadedImage($previousImage);

        return redirect()
            ->route('admin.site-images.edit')
            ->with('status', 'กลับไปใช้รูปเริ่มต้นแล้ว');
    }

    private function deleteUploadedImage(?string $path): void
    {
        if ($path === null || ! str_starts_with($path, SiteSetting::UPLOAD_DIRECTORY.'/')) {
            return;
        }

        $absolute = public_path($path);

        if (is_file($absolute)) {
            unlink($absolute);
        }
    }
}
