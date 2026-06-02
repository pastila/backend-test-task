<?php

namespace Raketa\BackendTestTask\Api\Dto;

use Raketa\BackendTestTask\Domain\ValueObject\ProductId;

final readonly class AddCartInput
{
    public function __construct(public ProductId $productId, public int $quantity)
    {
    }
}