<?php declare(strict_types=1);

namespace Stilus\Platform\Exception;

use Stilus\Exception\Code;
use Stilus\Exception\DomainException;

final class UserException extends DomainException
{
    public static function forUserCreation(): self
    {
        throw new self('Could not create user', Code::INVALID_USER_EMAIL);
    }
}
