<?php

declare(strict_types=1);

namespace Modules\Domain\Services;

use Illuminate\Support\Collection;
use Modules\Domain\Contracts\AmanetContract;
use Modules\Domain\Saloon\Connectors\AmanetConnector;
use Modules\Domain\Saloon\Requests\Amanet\GetCategoryProducts;
use Modules\Domain\Saloon\Requests\Amanet\GetProduct;


class AmanetService implements AmanetContract
{

    public AmanetConnector $connector;

    public function __construct(AmanetConnector $connector)
    {
        $this->connector = $connector;
    }

    public function getProducts(string $category): Collection
    {

        try {

            $request = new GetCategoryProducts($category);

            $results = $this->connector->send($request);

            return collect($results->dto());

        } catch (\Exception $e) {

            info($e->getMessage());

            return collect([]);
        }
    }


    public function getProduct(string $source): Collection
    {

        try{

            $request = new GetProduct($source);

            $results = $this->connector->send($request);

            return collect($results->dto());

        }catch (\Exception $e) {

            info($e->getMessage());

            return collect([]);
        }
    }
}
