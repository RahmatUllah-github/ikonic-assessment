<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Bug Report'],
            ['name' => 'Feature Request'],
            ['name' => 'Improvement Suggestion'],
            ['name' => 'General Feedback'],
            ['name' => 'Usability Issue'],
            ['name' => 'Performance Problem'],
            ['name' => 'Security Concern'],
            ['name' => 'Compatibility Issue'],
            ['name' => 'User Interface (UI) Feedback'],
            ['name' => 'Documentation Issue'],
            ['name' => 'Other'],
        ];

        Category::insert($categories);
    }
}
