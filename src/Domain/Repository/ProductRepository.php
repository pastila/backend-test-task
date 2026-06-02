<?php

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\ValueObject\ProductId;
use Raketa\BackendTestTask\Infrastructure\AntiCorruption\ReadModel\ProductView;

interface ProductRepository
{
    public function getById(ProductId $productId): ?ProductView;

    /**
     * @param string $categoryName
     * @return array<ProductView>
     */
    public function getByCategoryName (string $categoryName): array;
}