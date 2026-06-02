<?php

namespace Raketa\BackendTestTask\Infrastructure\Redis;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Infrastructure\Exception\RedisConnectorException;
use Redis;
use RedisException;

class RedisRegistry
{
    private ?Redis $connection;

    public function __construct(
        private LoggerInterface $logger,
        private string $redisHost,
        private int $redisPort = 6379,
        private ?string $redisPassword,
        private ?int $redisDbIndex
    )
    {
    }

    public function getConnection(): Redis
    {
        if (null !== $this->connection) {
            return $this->connection;
        }

        $this->connection = new Redis();

        if ($this->connection->isConnected() === false) {
            try {
                $this->connection->connect($this->redisHost, $this->redisPort);
            } catch (RedisException $exception) {
                $this->logger->critical(sprintf('Failed to connect to redis. %s', $exception->getMessage()));
                throw new RedisConnectorException(message: sprintf('Failed to connect to Redis. %s', $exception->getMessage()), previous: $exception);
            }
        }

        $this->connection->auth($this->redisPassword);
        $this->connection->select($this->redisDbIndex);

        $this->healthcheck();
        $this->writeCheck();

        return $this->connection;
    }

    private function healthcheck()
    {
        $pong = $this->connection->ping();

        if ($pong !== '+PONG' && $pong !== true) {
            throw new RedisConnectorException('Redis don`t answer to PING');
        }
    }

    private function writeCheck()
    {
        $testKey = 'healthcheck:' . uniqid('', true);
        $testValue = time();
        $written = $this->connection->set($testKey, $testValue, 10);

        if (!$written) {
            throw new RedisConnectorException('Redis cannot write data');
        }
    }
}