<?php

namespace Raketa\BackendTestTask\Api\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Api\Dto\AddCartInput;
use Raketa\BackendTestTask\Application\Exception\CartPersistenceException;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Application\Exception\ProductNotFoundException;
use Raketa\BackendTestTask\Application\Processor\AddToCartProcessor;
use Raketa\BackendTestTask\Application\Provider\CartProvider;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private AddToCartProcessor $processor,
        private CartProvider       $cartProvider
    )
    {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $input = new AddCartInput($rawRequest['productUuid'], $rawRequest['quantity']);

        try {
            $this->processor->process($input);
        } catch (ProductNotFoundException $exception) {
            $response = new JsonResponse();
            $response->setJsonArray([
                'error' => 'Product not found',
            ]);

            return $response
                ->withStatus(404);
        } catch (CartPersistenceException|InitializeCartException) {
            $response = new JsonResponse();
            $response->setJsonArray([
                'error' => 'Error adding item to cart. Please try again later',
            ]);

            return $response
                ->withStatus(500);
        }

        try {
            $cartOutput = $this->cartProvider->provide();
        } catch (InitializeCartException) {
            $response = new JsonResponse();
            $response->setJsonArray([
                'error' => 'Error adding item to cart. Please try again later',
            ]);
            return $response->withStatus(500);
        }


        $response = new JsonResponse();
        $response->setJsonArray([
            'status' => 'success',
            'cart' => $cartOutput->toArray()
        ]);

        return $response
            ->withStatus(200);
    }
}
