<?php

namespace Modules\Domain\Console\Commands;

use Illuminate\Console\Command;
use Modules\Domain\Contracts\PolitiaRomanaContract;
use Modules\Category\Models\Category;
use Modules\Comment\Models\Comment;
use Modules\Post\Models\Post;
use Modules\Tag\Models\Tag;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:import-police';

    /**
     * The console command description.
     */
    protected $description = 'Imports the police database of stolen objects.';

    /**
     * Execute the console command.
     */
    public function handle(PolitiaRomanaContract $police)
    {


        $results = $police->get();

        $this->components->task('Inserting Posts', function () {
            Post::factory()->count(1)->create()->each(function ($post) {
                $post->tags()->attach(
                    Tag::inRandomOrder()->limit(rand(5, 10))->pluck('id')->toArray()
                );
            });
        });

    }

}
