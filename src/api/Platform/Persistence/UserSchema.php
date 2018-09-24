<?php declare(strict_types=1);

namespace Stilus\Platform\Persistence;

use Igni\Storage\Driver\Pdo\Connection;
use Igni\Storage\Migration;
use Igni\Storage\Migration\Version;
use Psr\Container\ContainerInterface;

class UserSchema implements Migration
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function up(): void
    {
        $cursor = $this->connection->execute('CREATE TABLE IF NOT EXISTS "users" (
            "id" char(22) PRIMARY KEY NOT NULL,
            "email" char(128) NOT NULL,
            "password" char(128) NOT NULL
          );'
        );
        $cursor->execute();
    }

    public function down(): void
    {
        $cursor = $this->connection->execute('DROP TABLE IF EXISTS "users"');
        $cursor->execute();
    }

    public function getVersion(): Version
    {
        return Version::fromString('1.0.0');
    }

    public static function create(ContainerInterface $container): self
    {
        return new self($container->get(Connection::class));
    }
}
