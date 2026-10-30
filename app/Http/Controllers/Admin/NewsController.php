<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    private const string IMAGE_DIRECTORY = 'images/news';

    public function index(): View
    {
        return view('admin.news.index', [
            'articles' => Article::query()
                ->withCount([
                    'images',
                    'views',
                    'views as recent_views_count' => fn (Builder $query) => $query->where('viewed_at', '>=', now()->subDays(30)),
                ])
                ->latest()
                ->latest('id')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'article' => new Article,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request->file('image'));
        $data['slug'] = 'pending-'.Str::uuid();

        $article = Article::query()->create($data);
        $article->update([
            'slug' => $this->uniqueSlug($request->string('slug')->toString(), $request->string('title')->toString(), $article),
        ]);

        $this->storeGallery($request, $article);

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'เพิ่มข่าวสารแล้ว');
    }

    public function edit(Article $article): View
    {
        $article->load('images');

        return view('admin.news.form', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: false);
        $previousImage = $article->image;

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
        }

        $data['slug'] = $this->uniqueSlug($request->string('slug')->toString(), $data['title'], $article);
        $article->update($data);

        if ($previousImage !== $article->image) {
            $this->deleteImageIfUnused($previousImage);
        }

        $removedImages = $article->images()->whereKey($request->input('remove_images', []))->get();

        foreach ($removedImages as $removedImage) {
            $removedImage->delete();
            $this->deleteImageIfUnused($removedImage->image);
        }

        $this->storeGallery($request, $article);

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'บันทึกข่าวสารแล้ว');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $images = $article->images()->pluck('image')->push($article->image);
        $article->delete();

        foreach ($images as $image) {
            $this->deleteImageIfUnused($image);
        }

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'ลบข่าวสารแล้ว');
    }

    /**
     * @return array{title: string, excerpt: string, body: string}
     */
    private function validated(Request $request, bool $imageRequired = true): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:12'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ], [
            'title.required' => 'กรอกหัวข้อข่าว',
            'excerpt.required' => 'กรอกข้อความสรุป',
            'excerpt.max' => 'ข้อความสรุปต้องไม่เกิน 500 ตัวอักษร',
            'body.required' => 'กรอกรายละเอียดข่าว',
            'image.required' => 'เลือกรูปหน้าปก',
            'image.image' => 'ไฟล์รูปต้องเป็น jpg, png หรือ webp',
            'image.mimes' => 'ไฟล์รูปต้องเป็น jpg, png หรือ webp',
            'image.max' => 'รูปต้องมีขนาดไม่เกิน 5 MB',
            'gallery.max' => 'อัปโหลดรูปเพิ่มเติมได้ครั้งละไม่เกิน 12 รูป',
            'gallery.*.image' => 'รูปเพิ่มเติมต้องเป็น jpg, png หรือ webp',
            'gallery.*.mimes' => 'รูปเพิ่มเติมต้องเป็น jpg, png หรือ webp',
            'gallery.*.max' => 'รูปเพิ่มเติมแต่ละรูปต้องมีขนาดไม่เกิน 5 MB',
        ]);

        return [
            'title' => $data['title'],
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
        ];
    }

    private function storeGallery(Request $request, Article $article): void
    {
        $sortOrder = (int) $article->images()->max('sort_order');

        foreach ($request->file('gallery', []) as $file) {
            $sortOrder++;

            $article->images()->create([
                'image' => $this->storeImage($file),
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function uniqueSlug(string $input, string $title, Article $article): string
    {
        $base = Str::slug($input !== '' ? $input : $title);

        if ($base === '') {
            $base = 'news-'.$article->id;
        }

        $slug = $base;
        $suffix = 2;

        while (Article::query()->where('slug', $slug)->whereKeyNot($article->id)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function storeImage(UploadedFile $file): string
    {
        $name = Str::uuid()->toString().'.'.$file->extension();
        $file->move(public_path(self::IMAGE_DIRECTORY), $name);

        return self::IMAGE_DIRECTORY.'/'.$name;
    }

    private function deleteImageIfUnused(string $path): void
    {
        if (! str_starts_with($path, self::IMAGE_DIRECTORY.'/')) {
            return;
        }

        if (Article::query()->where('image', $path)->exists()) {
            return;
        }

        if (ArticleImage::query()->where('image', $path)->exists()) {
            return;
        }

        $absolute = public_path($path);

        if (is_file($absolute)) {
            unlink($absolute);
        }
    }
}
