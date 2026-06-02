<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

final class Price
{
    private function __construct(private readonly float $amount)
    {
        if ($this->amount < 0) {
            throw new \DomainException(sprintf('Amount must be greater than 0 (%s)', $this->amount));
        }
    }

    public static function fromAmount(float $amount): self
    {
        return new self($amount);
    }

    public function add(float $amount): Price
    {
        return self::fromAmount($amount + $this->amount);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return $this->amount;
    }
}