<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('admin.articles.index', [
            'articles' => Article::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->formView(new Article);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: true);
        $data['image'] = $this->storeImage($request);
        $data['slug'] = 'pending-'.Str::uuid();
        $data['sort_order'] = ((int) Article::query()->max('sort_order')) + 1;

        $article = Article::query()->create($data);
        $article->update([
            'slug' => $this->uniqueSlug($request->string('slug')->toString(), $article),
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'เพิ่มข่าวสารเรียบร้อยแล้ว');
    }

    public function edit(Article $article): View
    {
        return $this->formView($article);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: false);
        $previousImage = $article->image;

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $data['slug'] = $this->uniqueSlug($request->string('slug')->toString(), $article);
        $article->update($data);

        if ($previousImage !== $article->image) {
            $this->deleteImageIfUnused($previousImage);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'บันทึกการแก้ไขข่าวสารเรียบร้อยแล้ว');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $image = $article->image;
        $article->delete();
        $this->deleteImageIfUnused($image);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'ลบข่าวสารเรียบร้อยแล้ว');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'body' => ['required', 'string'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:5120'],
        ], [
            'title.required' => 'กรุณากรอกหัวข้อข่าวสาร',
            'excerpt.required' => 'กรุณากรอกคำอธิบายย่อ (สรุปข่าวสาร)',
            'body.required' => 'กรุณากรอกรายละเอียดเนื้อหาข่าวสาร',
            'image.required' => 'กรุณาเลือกรูปภาพข่าวสาร',
            'image.image' => 'ไฟล์รูปภาพต้องเป็น jpg, png หรือ webp',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 5 MB',
        ]);
    }

    private function formView(Article $article): View
    {
        return view('admin.articles.form', [
            'article' => $article,
        ]);
    }

    private function uniqueSlug(string $input, Article $article): string
    {
        $base = Str::slug($input);

        if ($base === '') {
            $base = 'article-'.$article->id;
        }

        $slug = $base;
        $suffix = 2;

        while (Article::query()->where('slug', $slug)->whereKeyNot($article->id)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function storeImage(Request $request): string
    {
        $file = $request->file('image');
        $name = Str::uuid()->toString().'.'.$file->extension();
        $targetDir = public_path('images/articles');

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $name);

        return 'images/articles/'.$name;
    }

    private function deleteImageIfUnused(string $path): void
    {
        if (! str_starts_with($path, 'images/articles/')) {
            return;
        }

        if (Article::query()->where('image', $path)->exists()) {
            return;
        }

        $absolute = public_path($path);

        if (is_file($absolute)) {
            unlink($absolute);
        }
    }
}
