<?php declare(strict_types=1);

namespace Stilus\Kernel\Migration;

use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\Connection;
use Igni\Storage\MigrationManager;
use Stilus\Kernel\System;

final class MigrationService
{
    public static function synchronize(System $system = null): void
    {
        $system = $system ?? new System();
        $container = $system->createServiceLocator();
        $connection = $system->createDatabaseConnection();

        $versionSynchronizer = new VersionSynchronizer($connection);
        $migrationManager = new MigrationManager($versionSynchronizer);
        $container->set(MigrationManager::class, $migrationManager);
        $container->set(Connection::class, $connection);

        self::loadModules($container);
        $migrationManager->migrate();
    }

    private static function loadModules(ServiceLocator $locator): void
    {
        $modules = System::STILUS_MODULES;

        foreach ($modules as $module) {
            if (!class_exists($module)) {
                continue;
            }
            $module = new $module;
        }
    }
}
