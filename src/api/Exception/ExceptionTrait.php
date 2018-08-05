<?php declare(strict_types=1);

namespace Stilus\Exception;

use Throwable;

trait ExceptionTrait
{
    public static function withCode(string $message, int $code): self
    {
        return new self($message, $code);
    }

    public static function withMessage(string $message): self
    {
        return new self($message);
    }

    public static function withPrevious(string $message, Throwable $previous, int $code = null): self
    {
        return new self($message, $code ? $code : $previous->getCode(), $previous);
    }
}
