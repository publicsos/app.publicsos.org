<?php

namespace Modules\Domain\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Category\Models\Category;
use Modules\Comment\Models\Comment;
use Modules\Post\Models\Post;
use Modules\Tag\Models\Tag;

use function Laravel\Prompts\confirm;

class BuildDomain extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:build {--fresh}';

    /**
     * The console command description.
     */
    protected $description = 'Builds the application database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Auth::loginUsingId(1);

        $fresh = $this->option('fresh');

        if ($fresh) {
            $this->truncate_tables();
        }

        $this->insert_demo_data();
    }

    public function insert_demo_data()
    {
        $this->info('Inserting Demo Data');

        /**
         * Insert Categories (with predefined values).
         */
        $this->components->task('Inserting Categories', function () {
            $this->seedCategories();
        });

        /**
         * Insert Tags.
         */
        $this->components->task('Inserting Tags', function () {
            Tag::factory()->count(10)->create();
        });

        /**
         * Insert Posts.
         */
        $this->components->task('Inserting Posts', function () {
            Post::factory()->count(25)->create()->each(function ($post) {
                $post->tags()->attach(
                    Tag::inRandomOrder()->limit(rand(5, 10))->pluck('id')->toArray()
                );
            });
        });

        // /**
        //  * Insert Comments.
        //  */
        // $this->components->task("Inserting Comments", function () {
        //     Comment::factory()->count(25)->create();
        // });

        $this->newLine(2);
        $this->info('-- Completed --');
        $this->newLine();
    }

    /**
     * Seed categories into the database.
     */
    protected function seedCategories()
    {
        // Define categories (avoiding negative ID issues)
        $existing_categories = [
            "Arme",
            "Bijuterii",
            "Tablouri",
            "Icoane",
            "Statueta",
            "Arta decorativa",
            "Carte",
            "Monede",
            "Trofeu Vanatoare",
            "Obiecte de cult",
            "Obiecte ceramica",
            "Sticlarie",
            "Mobilier",
            "Covoare",
            "Gravura",
            "Instrumente muzicale",
            "Sculptura"
        ];

        $new_categories = [
            "Bijuterii și ceasuri de valoare",
            "Electronice și dispozitive mobile",
            "Instrumente muzicale",
            "Echipamente sportive și de recreere",
            "Artă și obiecte de colecție"
        ];

        // Merge categories (remove duplicates)
        $all_categories = array_unique(array_merge($existing_categories, $new_categories));

        // Insert categories if they don't exist
        foreach ($all_categories as $category) {
            Category::updateOrCreate(
                ['name' => $category], // Prevent duplicates
                ['name' => $category]
            );
        }
    }

    /**
     * Truncate tables if the --fresh option is used.
     */
    public function truncate_tables()
    {
        $tables_list = [
            'posts',
            'categories',
            'tags',
            'taggables',
            // 'comments',
            'activity_log',
        ];

        $confirmed = confirm(
            label: 'Database tables (posts, categories, tags, comments) will become empty. Confirm truncate tables?',
            default: false,
        );

        $this->info('Truncate tables');

        if ($confirmed) {
            // Disable foreign key checks!
            Schema::disableForeignKeyConstraints();

            foreach ($tables_list as $table_name) {
                $this->components->task("Truncate Table: {$table_name}", function () use ($table_name) {
                    DB::table($table_name)->truncate();
                });
            }

            // Enable foreign key checks!
            Schema::enableForeignKeyConstraints();
        } else {
            $this->warn('Skipped database truncate.');
        }
        $this->newLine();
    }
}
