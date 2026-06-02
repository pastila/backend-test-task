<?php

namespace Raketa\BackendTestTask\Application\Processor;

use Raketa\BackendTestTask\Api\Dto\AddCartInput;
use Raketa\BackendTestTask\Application\CartManager;
use Raketa\BackendTestTask\Application\Exception\CartPersistenceException;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Application\Exception\ProductNotFoundException;
use Raketa\BackendTestTask\Domain\Repository\ProductRepository;
use Raketa\BackendTestTask\Domain\ValueObject\Price;

class AddToCartProcessor
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository
    )
    {
    }

    /**
     * @param AddCartInput $input
     * @return void
     * @throws CartPersistenceException
     * @throws ProductNotFoundException
     * @throws InitializeCartException
     */
    public function process (AddCartInput $input): void
    {
        $product = $this->productRepository->getById($input->productId);

        if (null === $product || $product->isActive() === false) {
            throw new ProductNotFoundException();
        }

        $cart = $this->cartManager->getCart();
        $cart->addItem(
            productId: $input->productId,
            price: Price::fromAmount($product->getPrice()),
            quantity: $input->quantity,
        );

        $this->cartManager->save($cart);
    }
}