<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Application\ControllerAggregator;
use Igni\Application\Providers\ControllerProvider;
use Stilus\Platform\Controller\CreatePlatform;
use Stilus\Platform\Controller\GetPlatformStatus;

class PlatformModule implements ControllerProvider
{
    public function provideControllers(ControllerAggregator $controllers): void
    {
        $controllers->register(CreatePlatform::class);
        $controllers->register(GetPlatformStatus::class);
    }
}
