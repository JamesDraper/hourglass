<?php
declare(strict_types=1);

namespace Hourglass;

use Hourglass\Internal\BenchmarkInterface;

abstract class Benchmark implements BenchmarkInterface
{
    public function beforeAll(): void
    {
    }

    public function beforeEach(): void
    {
    }

    public function afterAll(): void
    {
    }

    public function afterEach(): void
    {
    }

    public function averageOf(): int
    {
        return BenchmarkInterface::DEFAULT_AVERAGE_OF;
    }

    public function perRun(): int
    {
        return BenchmarkInterface::DEFAULT_PER_RUN;
    }

    final public function __construct()
    {
    }
}
