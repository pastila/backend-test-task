<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

final class CartId
{
    private function __construct(
        public readonly string $id
    )
    {
    }

    public static function fromString(string $cartId): self
    {
        return new self($cartId);
    }

    public function __toString(): string
    {
        return $this->id;
    }
}