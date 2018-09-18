<?php declare(strict_types=1);

namespace Stilus\Exception;

final class ExceptionCode
{
    public const INVALID_USER_EMAIL = 1;
    public const USER_NOT_FOUND = 2;

    private function __construct()
    {}
}
