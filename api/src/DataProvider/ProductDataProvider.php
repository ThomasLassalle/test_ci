<?php

declare(strict_types=1);

namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Product;

class ProductDataProvider implements RestrictedDataProviderInterface, ItemDataProviderInterface, CollectionDataProviderInterface
{
    /**
     * @var Product[]
     */
    private $products;

    public function __construct()
    {
        $p1 = new Product();
        $p1->id = '8450a46c-327b-46ad-83b8-e42c433cdf3a';
        $p1->name = 'Burger';
        $p1->price = 499;

        $p2 = new Product();
        $p2->id = '496e8aa2-0d1d-46ec-ac38-b7b168446186';
        $p2->name = 'Coca';
        $p2->price = 100;

        $this->products[$p1->id] = $p1;
        $this->products[$p2->id] = $p2;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Product::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return $this->products;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->products[$id] ?? null;
    }
}
