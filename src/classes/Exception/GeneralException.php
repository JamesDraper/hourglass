<?php
declare(strict_types=1);

namespace Hourglass\Exception;

use Throwable;

use function sprintf;

class GeneralException extends Exception
{
    private const MESSAGE = '%s(): An unexpected error has occurred.';

    public function __construct(string $method, ?Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $method), 0, $previous);
    }
}
