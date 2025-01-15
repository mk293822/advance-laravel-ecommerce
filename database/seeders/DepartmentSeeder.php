<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                "name" => "Electronics",
                "slug" => "electronics",
                "active" => true,
            ],
            [
                "name" => "Furniture",
                "slug" => "furniture",
                "active" => true,
            ],
            [
                "name" => "Foods",
                "slug" => "foods",
                "active" => true,
            ],
            [
                "name" => "Fashions",
                "slug" => "fashions",
                "active" => true,
            ],
            [
                "name" => "Books",
                "slug" => "books",
                "active" => true,
            ],
            [
                "name" => "Health & Beauty",
                "slug" => Str::slug('Health & Beauty'),
                "active" => true,
            ],
            [
                "name" => "Gadgets",
                "slug" => "gadgets",
                "active" => true,
            ],
            [
                "name" => "Sports & Outdoors",
                "slug" => "sports-outdoors",
                "active" => true,
            ],
            [
                "name" => "Toys & Games",
                "slug" => "toys-games",
                "active" => true,
            ],
            [
                "name" => "Automotive",
                "slug" => "automotive",
                "active" => true,
            ],
            [
                "name" => "Books & Stationery",
                "slug" => "books-stationery",
                "active" => true,
            ],
            [
                "name" => "Music & Movies",
                "slug" => "music-movies",
                "active" => true,
            ],
            [
                "name" => "Home & Garden",
                "slug" => "home-garden",
                "active" => true,
            ],
            [
                "name" => "Pets",
                "slug" => "pets",
                "active" => true,
            ],
            [
                "name" => "Office Supplies",
                "slug" => "office-supplies",
                "active" => true,
            ],
            [
                "name" => "Gifts & Flowers",
                "slug" => "gifts-flowers",
                "active" => true,
            ],
            [
                "name" => "Kitchen & Dining",
                "slug" => "kitchen-dining",
                "active" => true,
            ],
            [
                "name" => "Baby Products",
                "slug" => "baby-products",
                "active" => true,
            ],
            [
                "name" => "Travel & Luggage",
                "slug" => "travel-luggage",
                "active" => true,
            ]
        ];


        DB::table('departments')->insert($departments);
    }
}
