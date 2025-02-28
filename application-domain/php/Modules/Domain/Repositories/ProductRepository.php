<?php

declare(strict_types=1);

namespace Modules\Domain\Repositories;

use Modules\Post\Models\Post;
use Modules\Domain\Models\Products\Product;

class ProductRepository
{



    public function find(): Post
    {
        return Post::find(1);
    }



/**
     * Save a new product or update an existing one by title
     *
     * @param array $postDetails The product details to save/update
     * @return Product The saved or updated product
     */
    public function saveOrUpdate(array $postDetails): Product
    {
        unset($postDetails['image']);

        info(json_encode($postDetails));
        $product = Product::where('name', $postDetails['name'])->first();

        if ($product) {
            $product->update($postDetails);
            return $product->fresh();
        }

        return Product::create($postDetails);
    }

    /**
     * Find a product by its title
     *
     * @param string $title The title to search for
     * @return Product|null The found product or null
     */
    public function findByTitle(string $title): ?Product
    {
        return Product::where('title', $title)->first();
    }
}
