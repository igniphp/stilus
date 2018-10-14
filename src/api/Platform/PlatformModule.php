<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Application\ControllerAggregator;
use Igni\Application\Providers\ControllerProvider;
use Igni\Application\Providers\ServiceProvider;
use Igni\Container\ServiceLocator;
use Psr\Container\ContainerInterface;
use Stilus\Kernel\Module;
use Stilus\Platform\Controller\InstallPlatform;
use Stilus\Platform\Controller\GetPlatformStatus;

class PlatformModule implements ControllerProvider, ServiceProvider, Module
{
    public function provideControllers(ControllerAggregator $controllers): void
    {
        $controllers->register(InstallPlatform::class);
        $controllers->register(GetPlatformStatus::class);
    }

    /**
     * @param ContainerInterface|ServiceLocator $container
     */
    public function provideServices(ContainerInterface $container): void
    {
        $container->share(PlatformService::class);
    }

    public static function install(ContainerInterface $container)
    {

    }
}
