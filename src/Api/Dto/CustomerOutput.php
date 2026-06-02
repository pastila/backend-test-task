<?php

namespace Raketa\BackendTestTask\Api\Dto;

use Raketa\BackendTestTask\Domain\Entity\Customer;

final readonly class CustomerOutput
{
    public function __construct(
        public string $id,
        public string $email,
        public int    $name,
    )
    {
    }

    public static function fromCustomer(Customer $customer): self
    {
        return new self($customer->getId()->uuid, $customer->getEmail()->email, $customer->getFullName());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}