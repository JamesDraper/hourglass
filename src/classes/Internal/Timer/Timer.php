<?php
declare(strict_types=1);

namespace Hourglass\Internal\Timer;

use Closure;

final class Timer
{
    public function __invoke(Closure $benchmark, int $perRun): string
    {
        $before = hrtime();

        for ($i = 0; $i < $perRun; $i++) {
            $benchmark();
        }

        $after = hrtime();

        $before = Moment::fromSecondsAndNanoseconds($before[0], $before[1]);
        $after = Moment::fromSecondsAndNanoseconds($after[0], $after[1]);

        return Duration::fromMoments($before, $after)->toNanoseconds();
    }
}
