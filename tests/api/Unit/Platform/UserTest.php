<?php declare(strict_types=1);

namespace StilusTests\Unit\Platform;

use PHPUnit\Framework\TestCase;
use Stilus\Platform\Exception\UserException;
use Stilus\Platform\User;

final class UserTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        self::assertInstanceOf(User::class, new User('some@email.com', 'password'));
    }

    public function testFailOnInvalidEmailAddress(): void
    {
        $this->expectException(UserException::class);
        new User('invalidemail', 'aa');
    }

    public function testCreatePassword(): void
    {
        $user = new User('test@email.com', 'password');
        self::assertTrue($user->validatePassword('password'));
    }

    public function testVerifyPassword(): void
    {
        $user = new User('test@email.com', 'password');
        self::assertTrue($user->validatePassword('password'));
        self::assertFalse($user->validatePassword('invalid'));
        self::assertFalse($user->validatePassword('error'));
    }
}
