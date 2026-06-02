<?php

namespace Raketa\BackendTestTask\Api\Dto;

class ProductCollectionOutput
{
    public function __construct(
        /** @var array<ProductOutput> $products */
        private array $products,
    )
    {
    }

    public function toArray(): array
    {
        $output = [];

        foreach ($this->products as $product) {
            $output[] = $product->toArray();
        }

        return $output;
    }
}