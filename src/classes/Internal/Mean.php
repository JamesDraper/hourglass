<?php
declare(strict_types=1);

namespace Hourglass\Internal;

use function bcadd;
use function bcdiv;
use function bcmul;
use function rtrim;

class Mean
{
    private const PRECISION = 10;

    /**
     * @param string[] $durations Array of the durations in nanoseconds.
     * @return string The mean duration in nanoseconds.
     */
    public function __invoke(array $durations, int $averageOf, int $perRun): string
    {
        $dividend = '0';

        foreach ($durations as $duration) {
            $dividend = bcadd($dividend, $duration);
        }

        $averageOf = (string) $averageOf;
        $perRun = (string) $perRun;
        $divisor = bcmul($averageOf, $perRun);

        $mean = bcdiv($dividend, $divisor, self::PRECISION);

        return rtrim(rtrim($mean, '0'), '.');
    }
}
