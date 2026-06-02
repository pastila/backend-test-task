<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

final class PaymentMethodId
{
    private function __construct(
        public readonly string $uuid
    )
    {
        if (!Uuid::isValid($this->uuid)) {
            throw new \DomainException('Invalid UUID');
        }
    }

    public static function fromString(string $uuid): PaymentMethodId
    {
        return new self($uuid);
    }


    public function __toString(): string
    {
        return $this->uuid;
    }
}