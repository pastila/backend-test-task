<?php

namespace Raketa\BackendTestTask\Application\Provider;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Api\Dto\CartItemOutput;
use Raketa\BackendTestTask\Api\Dto\CartOutput;
use Raketa\BackendTestTask\Api\Dto\CustomerOutput;
use Raketa\BackendTestTask\Application\CartManager;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Domain\Repository\ProductRepository;

readonly class CartProvider
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository,
        private LoggerInterface $logger
    )
    {
    }

    public function provide (): CartOutput
    {
        try{
            $cart = $this->cartManager->getCart();
        } catch (InitializeCartException $e){
            $this->logger->error(sprintf('Failed initializing cart: %s', $e->getMessage()));
            throw $e;
        }

        $items = [];

        foreach ($cart->getItems() as $cartItem) {
            $product = $this->productRepository->getById($cartItem->getProductId());

            if (null === $product) {
                $this->logger->warning(sprintf("Product %s not found in Cart %s", $cartItem->getProductId()->uuid, $cart->getUuid()->id));
                continue;
            }

            $items[] = CartItemOutput::fromCartItemWithProduct($cartItem, $product);
        }

        return new CartOutput(
            uuid: $cart->getUuid(),
            total: $cart->getTotal()->getAmount(),
            items: $items,
            customer: $cart->getCustomer() ? CustomerOutput::fromCustomer($cart->getCustomer()) : null,
            paymentMethod: $cart->getPaymentMethod(),
        );
    }
}