<?php

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Entity\Cart;

interface CartRepository
{
    public function getCart(): Cart;

    public function save(Cart $cart): void;
}