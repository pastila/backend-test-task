<?php

namespace Raketa\BackendTestTask\Domain\ValueObject;

final class Email
{
  public function __construct(
    public readonly string $email,
  )
  {
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      throw new \DomainException('Invalid email');
    }
  }

  public function __toString(): string
  {
    return $this->email;
  }
}