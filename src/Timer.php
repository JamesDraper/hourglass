<?php
declare(strict_types=1);

namespace Hourglass;

use Closure;

final class Timer
{
    /**
     * @param Closure(): void $benchmark
     * @return string the time taken in nanoseconds.
     */
    public function __invoke(Closure $benchmark, int $times): string
    {
        $before = hrtime();

        for ($i = 0; $i < $times; $i++) {
            $benchmark();
        }

        $after = hrtime();

        $before = Moment::fromSecondsAndNanoseconds($before[0], $before[1]);
        $after = Moment::fromSecondsAndNanoseconds($after[0], $after[1]);

        return Duration::fromMoments($before, $after)->toNanoseconds();
    }
}
