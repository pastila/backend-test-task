<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Entity;

use Raketa\BackendTestTask\Domain\ValueObject\CartId;
use Raketa\BackendTestTask\Domain\ValueObject\CartItemId;
use Raketa\BackendTestTask\Domain\ValueObject\PaymentMethodId;
use Raketa\BackendTestTask\Domain\ValueObject\Price;
use Raketa\BackendTestTask\Domain\ValueObject\ProductId;

final class Cart
{
    private Customer $customer;

    /**
     * @var array<CartItem>
     */
    private array $items = [];

    private Price $total;

    private PaymentMethodId $paymentMethod;

    public function __construct(
        readonly private CartId $uuid,
    )
    {
        $this->total = Price::fromAmount(0);
    }

    public function changeCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function changePaymentMethod(PaymentMethodId $paymentMethodId): void
    {
        $this->paymentMethod = $paymentMethodId;
    }

    public function getUuid(): CartId
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): PaymentMethodId
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(
        ProductId $productId,
        Price $price,
        int $quantity,
    ): void
    {
        foreach ($this->items as $item) {
            if ($item->getProductId()->isEqual($item->getProductId())){
                $item->addQuantity($quantity);
                return;
            }
        }

        $this->addCartItem(CartItemId::fromString(CartItemId::nextId()), $productId, $price, $quantity);
    }

    public function addCartItem(CartItemId $cartItemId, ProductId $productId, Price $price, int $quantity): void
    {
        $this->items[] = new CartItem($cartItemId, $productId, $price, $quantity);
        $this->recalculateTotal();
    }

    public function getTotal(): Price
    {
        return $this->total;
    }

    private function recalculateTotal(): void
    {
        $total = Price::fromAmount(0);

        foreach ($this->items as $item) {
            $total->add($item->getTotalItemPrice()->getAmount());
        }
    }
}
