<?php

namespace Database\Seeders;

use App\Models\Service as ModelsService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Service extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsService::insert([
            [
                'name'        => 'Web Design',
                'description' => 'Professional website design with modern technologies.',
                'status'      => 'available',
                'price'       => 500.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'SEO Optimization',
                'description' => 'Improve your website ranking on search engines.',
                'status'      => 'available',
                'price'       => 300.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Content Writing',
                'description' => 'High quality blog posts, articles, and copywriting.',
                'status'      => 'not_available',
                'price'       => 150.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Social Media Management',
                'description' => 'Manage and grow your brand on social platforms.',
                'status'      => 'available',
                'price'       => 250.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
