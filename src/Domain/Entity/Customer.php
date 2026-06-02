<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Entity;

use Raketa\BackendTestTask\Domain\ValueObject\CustomerId;
use Raketa\BackendTestTask\Domain\ValueObject\Email;

final readonly class Customer
{
    public function __construct(
        private CustomerId $id,
        private string $firstName,
        private string $lastName,
        private string $middleName,
        private Email $email,
    ) {
    }

    /**
     * @return CustomerId
     */
    public function getId(): CustomerId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getFullName(): string
    {
        return implode(' ', [$this->lastName, $this->firstName, $this->middleName]);
    }
}
