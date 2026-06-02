<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Api\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Api\Dto\ProductByCategoryInput;
use Raketa\BackendTestTask\Application\Provider\ProductCollectionByCategoryProvider;

readonly class GetProductsByCategoryController
{
    public function __construct(
        private ProductCollectionByCategoryProvider $provider,
    ) {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
//        $rawRequest = json_decode($request->getBody()->getContents(), true);
//        $category = $rawRequest['category'];
        $query = $request->getUri()->getQuery();
        parse_str($query, $params);

        $products = $this->provider->provide(new ProductByCategoryInput($params['category']));
        $response->setJsonArray($products->toArray());

        return $response
            ->withStatus(200);
    }
}
