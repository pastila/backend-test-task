<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Api\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Application\Provider\CartProvider;

readonly class GetCartController
{
    public function __construct(
        public CartProvider $cartProvider,
    ) {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        try {
            $cart = $this->cartProvider->provide();
        } catch (InitializeCartException){
            $response->setJsonArray([
                'error' => 'Failed initializing cart',
            ]);
            return $response->withStatus(404);
        }

        $response->setJsonArray($cart->toArray());

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
