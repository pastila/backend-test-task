<?php

namespace Raketa\BackendTestTask\Api\Dto;

use Raketa\BackendTestTask\Domain\Entity\CartItem;
use Raketa\BackendTestTask\Infrastructure\AntiCorruption\ReadModel\ProductView;

final readonly class CartItemOutput
{
    public function __construct(
        public string $uuid,
        public float $price,
        public float $total,
        public int $quantity,
        public ProductOutput $product,
    )
    {
    }

    public static function fromCartItemWithProduct(CartItem $cartItem, ProductView $product): self
    {
        return new self(
            uuid: $cartItem->getId()->uuid,
            price: $cartItem->getPrice()->getAmount(),
            total: $cartItem->getTotalItemPrice()->getAmount(),
            quantity: $cartItem->getQuantity(),
            product: ProductOutput::fromProduct($product),
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'price' => $this->price,
            'total' => $this->total,
            'quantity' => $this->quantity,
            'product' => $this->product->toArray(),
        ];
    }
}