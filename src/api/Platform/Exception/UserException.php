<?php declare(strict_types=1);

namespace Stilus\Platform\Exception;

use Stilus\Exception\EntityNotFound;
use Stilus\Exception\ExceptionCode;
use Stilus\Exception\DomainException;

class UserException extends DomainException implements PlatformException
{
    public static function forCreationFailure(): self
    {
        throw new self('Could not create user', ExceptionCode::INVALID_USER_EMAIL);
    }

    public static function forNotFound(): self
    {
        return new class("User not found", ExceptionCode::USER_NOT_FOUND) extends UserException implements EntityNotFound {};
    }
}
