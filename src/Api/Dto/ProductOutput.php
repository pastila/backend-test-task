<?php

namespace Raketa\BackendTestTask\Api\Dto;

use Raketa\BackendTestTask\Infrastructure\AntiCorruption\ReadModel\ProductView;

final readonly class ProductOutput
{
    public function __construct(
        public string $id,
        public string $uuid,
        public string $name,
        public string $thumbnail,
        public string $price,
    )
    {
    }

    public static function fromProduct(ProductView $product): self
    {
        return new self(
            id: $product->getId(),
            uuid: $product->getUuid(),
            name: $product->getName(),
            thumbnail: $product->getThumbnail(),
            price: $product->getPrice()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
        ];
    }
}