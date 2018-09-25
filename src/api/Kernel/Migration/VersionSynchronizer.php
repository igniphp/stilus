<?php declare(strict_types=1);

namespace Stilus\Kernel\Migration;

use Igni\Storage\Driver\Pdo\Connection;
use Igni\Storage\Migration\VersionSynchronizer as MigrationVersionSynchronizer;
use Igni\Storage\Migration\Version;

class VersionSynchronizer implements MigrationVersionSynchronizer
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        $this->prepareMigrationTable();
    }

    private function prepareMigrationTable(): void
    {
        $cursor = $this->connection->execute(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='migrations'"
        );

        $tableExists = $cursor->current();

        if (!$tableExists) {
            $this->createMigrationTable();
        }
    }

    private function createMigrationTable(): void
    {
        $cursor = $this->connection->execute(
            "CREATE TABLE migrations (
              major INTEGER NOT NULL DEFAULT 0, 
              minor INTEGER NOT NULL DEFAULT 0, 
              patch INTEGER NOT NULL DEFAULT 0
          )"
        );

        $cursor->execute();
    }

    public function getVersion(): Version
    {
        $cursor = $this->connection->execute(
            'SELECT major, minor, patch FROM migrations ORDER BY major DESC, minor DESC, patch DESC'
        );

        $current = $cursor->current();
        if ($current === null) {
            $current = Version::fromString('0.0.0');
        } else {
            $current = Version::fromString(implode('.', $current));
        }

        return $current;
    }

    public function setVersion(Version $version): void
    {
        $cursor = $this->connection->execute(
            'INSERT INTO migrations (major, minor, patch) VALUES (:major, :minor, :patch)',
            [
                $version->getMajor(),
                $version->getMinor(),
                $version->getPatch()
            ]
        );

        $cursor->execute();
    }
}
