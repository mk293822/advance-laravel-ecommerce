<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => "Smartphones",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Laptops & Computers",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Cameras & Photography",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Home Appliances",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Audio & Headphones",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Wearables",
                "department_id" => 1, // Electronics
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Living Room Furniture",
                "department_id" => 2, // Furniture
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Bedroom Furniture",
                "department_id" => 2, // Furniture
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Office Furniture",
                "department_id" => 2, // Furniture
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Storage & Organization",
                "department_id" => 2, // Furniture
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Outdoor Furniture",
                "department_id" => 2, // Furniture
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Snacks & Confectionery",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Beverages",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Canned & Packaged Goods",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Dairy & Eggs",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Fresh Produce",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Frozen Foods",
                "department_id" => 3, // Foods
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Men's Clothing",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Women's Clothing",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Kids' Clothing",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Shoes & Accessories",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Jewelry",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Bags & Handbags",
                "department_id" => 4, // Fashions
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Men's Shoes",
                "department_id" => 5, // Books
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Women's Shoes",
                "department_id" => 5, // Books
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Children's Books",
                "department_id" => 5, // Books
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Fiction",
                "department_id" => 5, // Books
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Non-Fiction",
                "department_id" => 5, // Books
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Health & Fitness",
                "department_id" => 6, // Health & Beauty
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Skin Care",
                "department_id" => 6, // Health & Beauty
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Hair Care",
                "department_id" => 6, // Health & Beauty
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Makeup & Cosmetics",
                "department_id" => 6, // Health & Beauty
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Fragrance",
                "department_id" => 6, // Health & Beauty
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Gadgets & Gizmos",
                "department_id" => 7, // Gadgets
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Smart Home Devices",
                "department_id" => 7, // Gadgets
                "parent_id" => null,
                "active" => true,
            ],
            [
                "name" => "Wearables & Accessories",
                "department_id" => 7, // Gadgets
                "parent_id" => null,
                "active" => true,
            ]
        ];

        DB::table("categories")->insert($categories);
    }
}
