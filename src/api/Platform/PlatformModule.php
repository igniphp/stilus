<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Application\ControllerAggregator;
use Igni\Application\Providers\ControllerProvider;
use Igni\Application\Providers\ServiceProvider;
use Igni\Container\ServiceLocator;
use Psr\Container\ContainerInterface;
use Stilus\Platform\Controller\CreatePlatform;
use Stilus\Platform\Controller\GetPlatformStatus;

class PlatformModule implements ControllerProvider, ServiceProvider
{
    public function provideControllers(ControllerAggregator $controllers): void
    {
        $controllers->register(CreatePlatform::class);
        $controllers->register(GetPlatformStatus::class);
    }

    /**
     * @param ContainerInterface|ServiceLocator $container
     */
    public function provideServices(ContainerInterface $container): void
    {
        $container->share(PlatformService::class);
    }
}
