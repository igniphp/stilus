<?php declare(strict_types=1);

namespace Stilus\Exception;

use Throwable;

class BootException extends RuntimeException
{
    public static function forInvalidPHPVersion(string $currentVersion): self
    {
        return new self("Stilus requires PHP 7.1.0 or higher, you are running PHP {$currentVersion}.");
    }

    public static function forMissingComposer(): self
    {
        return new self('`vendor` dir is missing. Did you forgot to run `composer install?`');
    }

    public static function forMissingBaseConfiguration(): self
    {
        return new self('`.stilus.yml` file is missing. Did you deleted it by accident?');
    }

    public static function forInvalidBaseConfiguration(Throwable $previous): self
    {
        return new self(
            'There was a problem with parsing `.stilus.yml` file. Please check the config file.',
            $previous->getCode(),
            $previous
        );
    }

    public static function forMissingConfigurationOption(string $name): self
    {
        return new self("`{$name}`` configuration option is missing.");
    }
}
