<?php declare(strict_types=1);

namespace Stilus\Kernel;

use Igni\Application\Config;
use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\ConnectionManager;
use Igni\Storage\Driver\Pdo\Connection;
use Igni\Storage\Migration\Version;
use Igni\Storage\Migration\VersionSynchronizer;
use Stilus\Exception\BootException;

final class MigrationSynchronizer implements VersionSynchronizer
{
    private $system;
    private $connection;

    public static function synchronize(): void
    {
        $system = new System();
        $synchronizer = new self($system->createConnection());

    }

    private static function loadModules(Config $config, ServiceLocator $locator): void
    {
        $modules = System::STILUS_MODULES;

        foreach ($modules as $module) {
            if (class_exists($module)) {
                $module = new $module;
            }
        }
    }
}
