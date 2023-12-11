<?php
declare(strict_types=1);

namespace Hourglass\Internal;

class Moment
{
    private const NANOSECONDS_IN_SECOND = 1000000000;

    public static function fromSecondsAndNanoseconds(int $seconds, int $nanoseconds): self
    {
        [$seconds, $nanoseconds] = [(string) $seconds, (string) $nanoseconds];

        return new self(bcadd(
            bcmul($seconds, (string) self::NANOSECONDS_IN_SECOND),
            $nanoseconds,
        ));
    }

    public function __construct(private readonly string $nanoseconds)
    {
    }

    public function toNanoseconds(): string
    {
        return $this->nanoseconds;
    }
}
