<?php
declare(strict_types=1);

namespace Hourglass;

use function bcsub;

class Duration
{
    public static function fromMoments(Moment $before, Moment $after): self
    {
        $nanoseconds = bcsub($after->toNanoseconds(), $before->toNanoseconds());

        return new self($nanoseconds);
    }

    public function __construct(private readonly string $nanoseconds)
    {
    }

    public function toNanoseconds(): string
    {
        return $this->nanoseconds;
    }
}
