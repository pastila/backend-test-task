<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

class ProductId
{
    private function __construct(
        public readonly string $uuid
    )
    {
        if (!Uuid::isValid($this->uuid)) {
            throw new \DomainException('Invalid UUID');
        }
    }

    public static function fromString(string $cartId): self
    {
        return new self($cartId);
    }

    public function isEqual(ProductId $productId): bool
    {
        return $this->uuid === $productId->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}