<?php

namespace Raketa\BackendTestTask\Infrastructure\Redis;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\Exception\CartPersistenceException;
use Raketa\BackendTestTask\Application\Exception\InitializeCartException;
use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Entity\Customer;
use Raketa\BackendTestTask\Domain\Repository\CartRepository;
use Raketa\BackendTestTask\Domain\ValueObject\CartId;
use Raketa\BackendTestTask\Domain\ValueObject\CartItemId;
use Raketa\BackendTestTask\Domain\ValueObject\CustomerId;
use Raketa\BackendTestTask\Domain\ValueObject\Email;
use Raketa\BackendTestTask\Domain\ValueObject\PaymentMethodId;
use Raketa\BackendTestTask\Domain\ValueObject\ProductId;
use Raketa\BackendTestTask\Infrastructure\Exception\RedisConnectorException;
use RedisException;
use ValueError;

class RedisCartRepository implements CartRepository
{
    private const TTL = 86400;
    private const VERSION = 1;

    public function __construct(
        private RedisRegistry $redisRegistry,
        private LoggerInterface $logger
    )
    {
    }

    public function getCart(): Cart
    {
        try {
            $con = $this->redisRegistry->getConnection();
            $dataStr = $con->get($this->getRedisKey());
        } catch (RedisException|RedisConnectorException $e) {
            $this->logger->error(sprintf('Failed to get cart: %s', $e->getMessage()));
        }

        $cart = new Cart(CartId::fromString($this->getCartKey()));

        if (!isset($dataStr) || $dataStr === false) {
            return $cart;
        }

        try {
            $data = json_decode($dataStr, true);
        } catch (ValueError $error) {
            $this->logger->error(sprintf("Failed to restore Cart from Redis. Json decode: %s", $error->getMessage()));
            return $cart;
        }

        if (!isset($data['version']) || $data['version'] !== self::VERSION) {
            $this->logger->warning('Failed to restore Cart from Redis. Version: ' . $data['version'] ?? 'miss');
            return $cart;
        }

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $cart->addCartItem(CartItemId::fromString($item['id']), ProductId::fromString($item['productId']), $item['price'], $item['quantity']);
            }
        }

        if (isset($data['customer']) && is_array($data['customer'])) {
            $cart->changeCustomer(new Customer(CustomerId::fromString($data['customer']['id']), $data['customer']['firstName'], $data['customer']['lastName'], $data['customer']['middleName'], new Email($data['customer']['email'])));
        }

        if (isset($data['paymentMethod']) && is_array($data['paymentMethod'])) {
            $cart->changePaymentMethod(PaymentMethodId::fromString($data['paymentMethod']['id']));
        }

        return $cart;
    }

    public function save(Cart $cart): void
    {
        $items = [];

        foreach ($cart->getItems() as $item) {
            $items[] = [
                'id' => $item->getId()->uuid,
                'productId' => $item->getProductId()->uuid,
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice()->getAmount(),
            ];
        }

        $data = [
            'version' => self::VERSION,
            'paymentMethod' => $cart->getPaymentMethod()->uuid,
            'customer' => $cart->getCustomer() ? [
                'id' => $cart->getCustomer()->getId(),
                'firstName' => $cart->getCustomer()->getFirstName(),
                'lastName' => $cart->getCustomer()->getLastName(),
                'middleName' => $cart->getCustomer()->getMiddleName(),
                'email' => $cart->getCustomer()->getEmail(),
            ] : null,
            'items' => $items,
        ];

        try {
            $con = $this->redisRegistry->getConnection();
            $con->setex($this->getRedisKey(), json_encode($data), self::TTL);
        } catch (RedisException|RedisConnectorException $e) {
            $this->logger->error(sprintf('Failed to save cart: %s', $e->getMessage()));
            throw new CartPersistenceException(sprintf('Failed to save cart: %s', $e->getMessage()), previous: $e);
        }
    }

    private function getCartKey(): string
    {
        $key = session_id();

        if ($key === '' || $key === false) {
            throw new InitializeCartException('Failed to initialize Cart');
        }

        return $key;
    }

    private function getRedisKey(): string
    {
        return sprintf('app|cart|%s', $this->getCartKey());
    }
}