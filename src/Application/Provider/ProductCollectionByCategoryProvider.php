<?php

namespace Raketa\BackendTestTask\Application\Provider;

use Raketa\BackendTestTask\Api\Dto\ProductByCategoryInput;
use Raketa\BackendTestTask\Api\Dto\ProductCollectionOutput;
use Raketa\BackendTestTask\Api\Dto\ProductOutput;
use Raketa\BackendTestTask\Domain\Repository\ProductRepository;

class ProductCollectionByCategoryProvider
{
    public function __construct(
        private readonly ProductRepository $repository,
    )
    {
    }

    public function provide(ProductByCategoryInput $input): ProductCollectionOutput
    {
        $products = $this->repository->getByCategoryName($input->category);
        return new ProductCollectionOutput(array_map(ProductOutput::fromProduct(...), $products));
    }
}