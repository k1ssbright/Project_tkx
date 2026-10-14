<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('manage.index', [
            'products' => Product::query()->with('category')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->formView(new Product);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request);
        $data['slug'] = 'pending-'.Str::uuid();
        $data['sort_order'] = ((int) Product::query()->max('sort_order')) + 1;

        $product = Product::query()->create($data);
        $product->update([
            'slug' => $this->uniqueSlug($request->string('slug')->toString(), $product),
        ]);

        return redirect()
            ->route('products.manage.index')
            ->with('status', 'เพิ่มสินค้าแล้ว');
    }

    public function edit(Product $product): View
    {
        return $this->formView($product);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: false);
        $previousImage = $product->image;

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $data['slug'] = $this->uniqueSlug($request->string('slug')->toString(), $product);
        $product->update($data);

        if ($previousImage !== $product->image) {
            $this->deleteImageIfUnused($previousImage);
        }

        return redirect()
            ->route('products.manage.index')
            ->with('status', 'บันทึกสินค้าแล้ว');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $image = $product->image;
        $product->delete();
        $this->deleteImageIfUnused($image);

        return redirect()
            ->route('products.manage.index')
            ->with('status', 'ลบสินค้าแล้ว');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, bool $imageRequired = true): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'fabric' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'colors' => ['required', 'string', 'max:255'],
            'sizes' => ['required', 'string', 'max:255'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:5120'],
        ], [
            'name.required' => 'กรอกชื่อสินค้า',
            'category_id.required' => 'เลือกหมวดหมู่',
            'price.required' => 'กรอกราคา',
            'price.integer' => 'ราคาต้องเป็นจำนวนเต็ม',
            'fabric.required' => 'กรอกชนิดผ้า',
            'description.required' => 'กรอกคำอธิบาย',
            'colors.required' => 'ใส่สีอย่างน้อย 1 ค่า',
            'sizes.required' => 'ใส่ไซส์อย่างน้อย 1 ค่า',
            'image.required' => 'เลือกรูปสินค้า',
            'image.image' => 'ไฟล์รูปต้องเป็น jpg, png หรือ webp',
            'image.max' => 'รูปต้องมีขนาดไม่เกิน 5 MB',
        ]);

        $colors = $this->splitList($data['colors']);
        $sizes = $this->splitList($data['sizes']);
        $errors = [];

        if ($colors === []) {
            $errors['colors'] = 'ใส่สีอย่างน้อย 1 ค่า คั่นด้วยจุลภาค';
        }

        if ($sizes === []) {
            $errors['sizes'] = 'ใส่ไซส์อย่างน้อย 1 ค่า คั่นด้วยจุลภาค';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'fabric' => $data['fabric'],
            'description' => $data['description'],
            'colors' => $colors,
            'sizes' => $sizes,
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function formView(Product $product): View
    {
        return view('manage.form', [
            'product' => $product,
            'categories' => Category::query()->orderBy('id')->get(),
        ]);
    }

    private function uniqueSlug(string $input, Product $product): string
    {
        $base = Str::slug($input);

        if ($base === '') {
            $base = 'product-'.$product->id;
        }

        $slug = $base;
        $suffix = 2;

        while (Product::query()->where('slug', $slug)->whereKeyNot($product->id)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * @return list<string>
     */
    private function splitList(string $value): array
    {
        return array_values(array_filter(array_map(trim(...), explode(',', $value)), fn (string $item) => $item !== ''));
    }

    private function storeImage(Request $request): string
    {
        $file = $request->file('image');
        $name = Str::uuid()->toString().'.'.$file->extension();
        $file->move(public_path('images/products'), $name);

        return 'images/products/'.$name;
    }

    private function deleteImageIfUnused(string $path): void
    {
        if (! str_starts_with($path, 'images/products/')) {
            return;
        }

        if (Product::query()->where('image', $path)->exists()) {
            return;
        }

        $absolute = public_path($path);

        if (is_file($absolute)) {
            unlink($absolute);
        }
    }
}
