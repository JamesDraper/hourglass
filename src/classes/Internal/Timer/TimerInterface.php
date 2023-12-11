<?php

namespace Hourglass\Internal\Timer;

use Closure;

interface TimerInterface
{
    /**
     * @param Closure(): void $benchmark
     * @return string the time taken in nanoseconds.
     */
    public function __invoke(Closure $benchmark, int $perRun): string;
}
