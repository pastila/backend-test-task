<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\AntiCorruption;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Repository\ProductRepository;
use Raketa\BackendTestTask\Domain\ValueObject\ProductId;
use Raketa\BackendTestTask\Infrastructure\AntiCorruption\ReadModel\ProductView;
use Raketa\BackendTestTask\Repository\Exception;

class DoctrineProductRepository implements ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getById(ProductId $productId): ProductView
    {
        $stmt = $this->connection->prepare("SELECT id, uuid, is_active, category, name, description, thumbnail, price FROM products WHERE uuid = :uuid");
        $stmt->bindValue(':uuid', $productId->uuid);
        $resultSet = $stmt->executeQuery();
        $row = $resultSet->fetchAssociative();

        if (false === $row) {
            throw new \Exception(sprintf('Product %s not found', $productId->uuid));
        }

        return ProductView::fromArray($row);
    }

    public function getByCategoryName(string $categoryName): array
    {
        $stmt = $this->connection->prepare("SELECT id, uuid, is_active, category, name, description, thumbnail, price FROM products WHERE is_active = 1 AND category = :categoryName");
        $stmt->bindValue(':categoryName', $categoryName);
        $resultSet = $stmt->executeQuery();
        $rows = $resultSet->fetchAllAssociative();

        return array_map(ProductView::fromArray(...), $rows);
    }
}
