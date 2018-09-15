<?php declare(strict_types=1);

namespace Stilus\Platform;

use Igni\Application\Config;

final class PlatformService
{
    public const STATUS_INSTALLED = 'installed';
    public const STATUS_NOT_INSTALLED = 'not_installed';

    private $config;

    public function __construct(Config $paths)
    {
        $this->config = $paths;
    }

    public function getStatus()
    {

    }
}
