<?php declare(strict_types=1);

namespace Stilus\Exception;

class BootException extends RuntimeException
{
    public static function forInvalidPHPVersion(string $currentVersion): self
    {
        return new self("Stilus requires PHP 7.1.0 or higher, you are running PHP {$currentVersion}.");
    }

    public static function forMissingComposer(): self
    {
        return new self("`vendor` dir is missing. Did you forgot to run `composer install?`");
    }

    public static function forMissingBaseConfiguration(): self
    {
        return new self("`vendor` dir is missing. Did you forgot to run `composer install?`");
    }
}
