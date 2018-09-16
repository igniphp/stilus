<?php declare(strict_types=1);

namespace Stilus\Exception;

use RuntimeException as PhpRuntimeException;

class RuntimeException extends PhpRuntimeException implements StilusException
{
    use ExceptionTrait;
}
