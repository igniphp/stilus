<?php declare(strict_types=1);

namespace Stilus\Exception;

class RuntimeException extends \RuntimeException implements StilusException
{
    use ExceptionTrait;
}
