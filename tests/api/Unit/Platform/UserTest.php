<?php declare(strict_types=1);

namespace Stilus\Tests\Unit\Platform;

use PHPUnit\Framework\TestCase;
use Stilus\Exception\ExceptionCode;
use Stilus\Platform\Exception\UserException;
use Stilus\Platform\Persistence\User;

final class UserTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        self::assertInstanceOf(User::class, new User('some@email.com', 'password'));
    }

    public function testFailOnInvalidEmailAddress(): void
    {
        $this->expectException(UserException::class);
        $this->expectExceptionCode(ExceptionCode::INVALID_USER_EMAIL);
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
