<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $shirts = Category::query()->updateOrCreate(
            ['slug' => 'shirts'],
            [
                'name' => 'เสื้อ',
                'subtitle' => 'Shirts',
                'description' => 'เสื้อเชิ้ต เสื้อยืด และเสื้อโปโล โทนขาว งาช้าง และครีม ตัดเย็บเรียบ ใส่ได้ทุกวัน',
            ],
        );

        $pants = Category::query()->updateOrCreate(
            ['slug' => 'pants'],
            [
                'name' => 'กางเกง',
                'subtitle' => 'Trousers',
                'description' => 'กางเกงขายาว กางเกงขากว้าง และกางเกงชิโน สีขาว ครีม และเบจ',
            ],
        );

        $products = [
            [
                'category_id' => $shirts->id,
                'name' => 'เสื้อเชิ้ตลินินสีงาช้าง',
                'slug' => 'ivory-linen-shirt',
                'price' => 1890,
                'fabric' => 'ลินิน',
                'description' => 'เชิ้ตผ้าลินินสีงาช้าง เนื้อบาง ระบายอากาศดี ทรงหลวมเล็กน้อย เหมาะกับอากาศร้อนและลุคเรียบ',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['งาช้าง', 'ขาว'],
                'image' => 'images/products/linen-shirt.jpg',
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'category_id' => $shirts->id,
                'name' => 'เสื้อยืดคอตตอนขาว',
                'slug' => 'white-cotton-tee',
                'price' => 690,
                'fabric' => 'คอตตอนคอมแพค',
                'description' => 'เสื้อยืดคอกลมผ้าคอตตอนสีขาวล้วน คอไม่ย้วยง่าย ใส่เดี่ยวหรือซ้อนในเชิ้ตได้',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['ขาว'],
                'image' => 'images/products/white-tee.jpg',
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'category_id' => $shirts->id,
                'name' => 'เสื้อเชิ้ตขาวคอปก',
                'slug' => 'white-collar-shirt',
                'price' => 1590,
                'fabric' => 'คอตตอน',
                'description' => 'เชิ้ตคอปกสีขาว กระดุมมุก ทรงสุภาพ ใส่ทำงานหรือออกงานเรียบได้โดยไม่ต้องมีลวดลาย',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['ขาว'],
                'image' => 'images/products/white-shirt.jpg',
                'sort_order' => 3,
                'is_featured' => true,
            ],
            [
                'category_id' => $shirts->id,
                'name' => 'เสื้อโปโลปิเก้ขาว',
                'slug' => 'white-pique-polo',
                'price' => 1490,
                'fabric' => 'ปิเก้คอตตอน',
                'description' => 'โปโลแขนสั้นผ้าปิเก้สีขาว ปกเรียบ เนื้อผ้ามีลายถักเล็กน้อย ดูสุภาพทั้งวันทำงานและวันหยุด',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['ขาว'],
                'image' => 'images/products/ivory-polo.jpg',
                'sort_order' => 4,
                'is_featured' => false,
            ],
            [
                'category_id' => $pants->id,
                'name' => 'กางเกงขายาวสีครีม',
                'slug' => 'cream-straight-pants',
                'price' => 1690,
                'fabric' => 'คอตตอนทวิล',
                'description' => 'กางเกงขายาวสีครีม ทรงตรง เอวกลาง พับขาได้ เข้ากับเสื้อขาวทั้งชุด',
                'sizes' => ['28', '30', '32', '34', '36'],
                'colors' => ['ครีม'],
                'image' => 'images/products/cream-pants.jpg',
                'sort_order' => 5,
                'is_featured' => true,
            ],
            [
                'category_id' => $pants->id,
                'name' => 'กางเกงขากว้างสีขาว',
                'slug' => 'white-wide-pants',
                'price' => 1790,
                'fabric' => 'ผ้ายืดเนื้อเนียน',
                'description' => 'กางเกงขากว้างสีขาว เอวสูงเล็กน้อย ขาบานพอดีตัว เคลื่อนไหวสบายและดูยาว',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['ขาว'],
                'image' => 'images/products/white-wide.jpg',
                'sort_order' => 6,
                'is_featured' => true,
            ],
            [
                'category_id' => $pants->id,
                'name' => 'กางเกงชิโนสีเบจ',
                'slug' => 'beige-chino',
                'price' => 1390,
                'fabric' => 'ผ้าชิโนคอตตอน',
                'description' => 'ชิโนสีเบจอ่อน ทรงสลิมเล็กน้อย ใส่กับเสื้อยืดขาวหรือเชิ้ตได้ทุกวัน',
                'sizes' => ['28', '30', '32', '34', '36'],
                'colors' => ['เบจ'],
                'image' => 'images/products/beige-chino.jpg',
                'sort_order' => 7,
                'is_featured' => false,
            ],
            [
                'category_id' => $pants->id,
                'name' => 'กางเกงยีนส์ฟอกอ่อน',
                'slug' => 'light-wash-jeans',
                'price' => 1590,
                'fabric' => 'เดนิมฟอกอ่อน',
                'description' => 'ยีนส์ขาตรงฟอกสีอ่อน ไม่มีรอยขาด เข้ากับเสื้อขาวและลุคมินิมอล',
                'sizes' => ['28', '30', '32', '34', '36'],
                'colors' => ['ฟอกอ่อน'],
                'image' => 'images/products/light-jeans.jpg',
                'sort_order' => 8,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product,
            );
        }
    }
}
