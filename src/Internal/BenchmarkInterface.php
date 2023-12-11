<?php
declare(strict_types=1);

namespace Hourglass\Internal;

interface BenchmarkInterface
{
    public const DEFAULT_AVERAGE_OF = 1000;

    public const DEFAULT_PER_RUN = 1;

    public function benchmark(): void;

    public function beforeAll(): void;

    public function beforeEach(): void;

    public function afterAll(): void;

    public function afterEach(): void;

    public function averageOf(): int;

    public function perRun(): int;
}
