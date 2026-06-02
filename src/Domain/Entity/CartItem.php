<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Entity;

use Raketa\BackendTestTask\Domain\ValueObject\CartItemId;
use Raketa\BackendTestTask\Domain\ValueObject\Price;
use Raketa\BackendTestTask\Domain\ValueObject\ProductId;

final class CartItem
{
    private Price $totalItemPrice;

    public function __construct(
        private CartItemId $id,
        private ProductId $productId,
        private Price $price,
        private int $quantity,
    ) {
        $this->totalItemPrice = Price::fromAmount($this->price->getAmount() * $this->quantity);
    }

    public function getId(): CartItemId
    {
        return $this->id;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
        $this->totalItemPrice = $this->totalItemPrice->add($quantity * $this->price->getAmount());
    }

    public function getTotalItemPrice(): Price
    {
        return $this->totalItemPrice;
    }
}
