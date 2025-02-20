<?php

namespace Modules\Post\Console\Commands;

use Illuminate\Console\Command;
use Modules\Post\Models\Post;
use Modules\Category\Models\Category;
use Modules\Post\Enums\PostStatus;
use Illuminate\Support\Str;

class PostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed predefined posts and assign them to respective roles/categories';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // List of institutions and their categories
        $institutions = [
            [
                'category_name' => 'Medical Chief',
                'title' => 'Spitalul Clinic de Urgență pentru Copii „Grigore Alexandrescu”',
                'content' => 'Str. Iancu de Hunedoara nr. 30-32, Sector 1, 11743'
            ],
            [
                'category_name' => 'Law Enforcement Chief',
                'title' => 'Secția 1 Poliție',
                'content' => 'Str. Lascar Catargiu nr. 20A, Sector 1, 10663'
            ],
            [
                'category_name' => 'Fire & HAZMAT Chief',
                'title' => 'Detașamentul de Pompieri “Mihai Vodă”',
                'content' => 'Splaiul Independenței nr. 172-178, Sector 5, 50096'
            ]
        ];

        // Loop through each institution and create posts
        foreach ($institutions as $institution) {
            // Check if category exists by name
            $category = Category::where('name', $institution['category_name'])->first();

            if (!$category) {
                $this->error("Category '{$institution['category_name']}' not found. Skipping post creation.");
                continue; // Skip to the next institution if category not found
            }

            // Create the post and assign it to the found category
            Post::create([
                'category_id' => $category->id,
                'title' => $institution['title'],
                'content' => $institution['content'],
                'status' => PostStatus::Published->value, // Assuming 'Published' is a valid status in your PostStatus enum
                'published_at' => now(),
            ]);

            $this->info("Post '{$institution['title']}' has been created and assigned to '{$institution['category_name']}' category.");
        }

        $this->info('Posts have been seeded successfully.');
        return Command::SUCCESS;
    }
}
