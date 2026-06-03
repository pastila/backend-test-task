<?php

namespace Raketa\BackendTestTask\Application;

use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Repository\CartRepository;

class CartManager
{
    public function __construct(
        private CartRepository $cartRepository,
    )
    {
    }

    public function getCart(): Cart
    {
        return $this->cartRepository->getCart();
    }

    public function save(Cart $cart): void
    {
        $this->cartRepository->save($cart);
    }
}