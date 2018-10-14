<?php declare(strict_types=1);

namespace Stilus\Kernel;

use Psr\Container\ContainerInterface;

interface Module
{
    public static function install(Installer $installer);
}
