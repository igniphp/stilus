<?php declare(strict_types=1);

namespace Stilus\Tests;

use Igni\Storage\Driver\ConnectionManager;
use Igni\Storage\Driver\Pdo\Connection;
use Igni\Storage\EntityManager;
use Igni\Storage\Storage;

trait StorageTestTrait
{
    private $connection;
    private $entityManager;
    private $storage;

    public function createConnection(string $name = 'test'): Connection
    {
        $this->connection = new Connection('sqlite:' . STILUS_TEST_FIXTURE_DIR . '/test.db');
        ConnectionManager::register($name, $this->connection);

        return $this->connection;
    }

    public function createEntityManager(): EntityManager
    {
        $this->entityManager = new EntityManager();

        return $this->entityManager;
    }

    public function createStorage(): Storage
    {
        $this->storage = new Storage($this->entityManager ?? $this->createEntityManager());
    }
}
