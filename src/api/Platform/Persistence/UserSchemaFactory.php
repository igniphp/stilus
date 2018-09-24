<?php declare(strict_types=1);

namespace Stilus\Platform\Persistence;

use Igni\Container\ServiceLocator;
use Igni\Storage\Driver\Pdo\Connection;

final class UserSchemaFactory
{
    public function __invoke(ServiceLocator $locator)
    {
        return new UserSchema($locator->get(Connection::class));
    }
}
