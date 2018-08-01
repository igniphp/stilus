<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Application\Controller\ControllerAggregate;
use Igni\Application\Providers\ControllerProvider;
use Stilus\Platform\Controller\CreatePlatform;
use Stilus\Platform\Controller\GetPlatformStatus;

class PlatformModule implements ControllerProvider
{
    public function provideControllers(ControllerAggregate $controllers): void
    {
        $controllers->add(CreatePlatform::class);
        $controllers->add(GetPlatformStatus::class);
    }
}
