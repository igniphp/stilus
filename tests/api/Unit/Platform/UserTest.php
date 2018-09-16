<?php declare(strict_types=1);

namespace StilusTests\Unit\Platform;

use PHPUnit\Framework\TestCase;
use Stilus\Platform\User;

final class UserTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        self::assertInstanceOf(User::class, new User('some@email.com', 'password'));
    }
}
