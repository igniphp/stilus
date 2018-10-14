<?php declare(strict_types=1);

namespace Stilus\Kernel;

use Igni\Storage\Driver\Connection;
use Igni\Storage\MigrationManager;
use Psr\Container\ContainerInterface;

class Installer
{
    public function __construct(Connection $connection, string $directory)
    {

    }

    public function addFile(string $path, string $contents)
    {

    }

    public function addMigration(): void
    {

    }
}
