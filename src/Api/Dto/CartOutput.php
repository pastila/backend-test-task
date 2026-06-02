<?php

namespace Raketa\BackendTestTask\Api\Dto;

final readonly class CartOutput
{
    public function __construct(
        public string $uuid,
        public float $total,
        /** @var array<CartItemOutput> $items */
        public array $items,
        public ?CustomerOutput $customer,
        public ?string $paymentMethod,
    )
    {
    }

    public function toArray(): array
    {
        $items = [];

        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        return [
            'total' => $this->total,
            'customer' => $this->customer?->toArray(),
            'paymentMethod' => $this->paymentMethod,
            'items' => $items,
        ];
    }
}