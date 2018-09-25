<?php declare(strict_types=1);

namespace Stilus\Kernel\Migration;

use Igni\Application\Config;
use Igni\Application\Providers\ConfigProvider;
use Igni\Application\Providers\ServiceProvider;
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
        $modules = [];

        foreach (System::STILUS_MODULES as $module) {
            if (!class_exists($module)) {
                continue;
            }
            $modules[] = new $module;
        }

        foreach ($modules as $module) {
            if ($module instanceof ConfigProvider) {
                $module->provideConfig($locator->get(Config::class));
            }
        }

        foreach ($modules as $module) {
            if ($module instanceof ServiceProvider) {
                $module->provideServices($locator);
            }
        }
    }
}
