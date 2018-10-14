<?php declare(strict_types=1);

namespace Stilus\Kernel\Migration;

use Igni\Application\Config;
use Igni\Application\Providers\ConfigProvider;
use Igni\Application\Providers\ServiceProvider;
use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\Connection;
use Igni\Storage\Migration\Version;
use Igni\Storage\MigrationManager;
use Stilus\Kernel\System;
use Composer\Script\Event;

final class MigrationCommand
{
    public static function synchronize(Event $event, System $system = null): void
    {
        $system = $system ?? new System();
        $container = $system->createServiceLocator();
        $connection = $system->createDatabaseConnection();
        $container->set(Connection::class, $connection);

        $versionSynchronizer = new VersionSynchronizer($connection);
        $migrationManager = new MigrationManager($versionSynchronizer);
        $container->set(MigrationManager::class, $migrationManager);

        self::loadModules($container);
        $arguments = $event->getArguments();

        if (isset($arguments[0])) {
            $migrationManager->migrate(Version::fromString($arguments[0]));
        } else {
            $migrationManager->migrate();
        }
    }

    private static function loadModules(ServiceLocator $locator): void
    {
        $modules = [];

        foreach (System::BASE_MODULES as $module) {
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
