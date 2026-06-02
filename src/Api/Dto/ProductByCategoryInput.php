<?php

namespace Raketa\BackendTestTask\Api\Dto;

final readonly class ProductByCategoryInput
{
    public function __construct(
        public string $category
    )
    {
    }
}