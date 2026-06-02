<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

final class CartItemId
{
    private function __construct(
        public readonly string $uuid
    )
    {
        if (!Uuid::isValid($this->uuid)) {
            throw new \DomainException('Invalid UUID');
        }
    }

    public static function nextId(): string
    {
        return Uuid::uuid7()->toString();
    }

    public static function fromString(string $cartId): self
    {
        return new self($cartId);
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}