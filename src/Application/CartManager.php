<?php

namespace Raketa\BackendTestTask\Application;

use Raketa\BackendTestTask\Application\Exception\CartPersistenceException;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Domain\Entity\Cart;

interface CartManager
{
    /**
     * @throws InitializeCartException
     * @return Cart
     */
    public function getCart(): Cart;

    /**
     * @throws CartPersistenceException
     * @param Cart $cart
     * @return void
     */
    public function save(Cart $cart): void;
}