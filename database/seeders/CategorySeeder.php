<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => '(261xxx) Computer Engineering / Computer Network', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '(255xxx) Software Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'หมวดหมู่อื่นๆ', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}