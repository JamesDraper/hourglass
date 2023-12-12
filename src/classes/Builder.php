<?php
declare(strict_types=1);

namespace Hourglass;

use Closure;

final class Builder
{
    /**
     * @param Closure(): void $benchmark
     */
    public function benchmark(Closure $benchmark): self
    {
        //

        return $this;
    }

    public function run(): self
    {
        //

        return $this;
    }

    /**
     * @param Closure(): void|null $beforeAll
     */
    public function beforeAll(?Closure $beforeAll): self
    {
        //

        return $this;
    }

    /**
     * @param Closure(): void|null $afterAll
     */
    public function afterAll(?Closure $afterAll): self
    {
        //

        return $this;
    }

    /**
     * @param Closure(): void|null $beforeEach
     */
    public function beforeEach(?Closure $beforeEach): self
    {
        //

        return $this;
    }

    /**
     * @param Closure(): void|null $afterEach
     */
    public function afterEach(?Closure $afterEach): self
    {
        //

        return $this;
    }

    public function averageOf(int $averageOf): self
    {
        //

        return $this;
    }

    public function perRun(int $perRun): self
    {
        //

        return $this;
    }
}
