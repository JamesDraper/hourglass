<?php
declare(strict_types=1);

namespace Hourglass\Internal;

use Hourglass\Exception\ConfigException;

use ReflectionFunction;
use Closure;

use function is_null;

class Config
{
    /**
     * @var Closure(): void
     */
    private Closure $benchmark;

    /**
     * @var Closure(): void|null
     */
    private ?Closure $beforeAll = null;

    /**
     * @var Closure(): void|null
     */
    private ?Closure $afterAll = null;

    /**
     * @var Closure(): void|null
     */
    private ?Closure $beforeEach = null;

    /**
     * @var Closure(): void|null
     */
    private ?Closure $afterEach = null;

    private int $averageOf = BenchmarkInterface::DEFAULT_AVERAGE_OF;

    private int $perRun = BenchmarkInterface::DEFAULT_PER_RUN;

    /**
     * @param Closure(): void $benchmark
     */
    public function setBenchmark(Closure $benchmark): void
    {
        $this->benchmark = $benchmark;
    }

    /**
     * @return Closure(): void
     */
    public function getBenchmark(): Closure
    {
        return $this->benchmark;
    }

    /**
     * @param Closure(): void|null $beforeAll
     * @throws ConfigException
     */
    public function setBeforeAll(?Closure $beforeAll): void
    {
        $this->assertNullableClosure($beforeAll, 'beforeAll');

        $this->beforeAll = $beforeAll;
    }

    /**
     * @return Closure(): void|null
     */
    public function getBeforeAll(): ?Closure
    {
        return $this->beforeAll;
    }

    /**
     * @param Closure(): void|null $afterAll
     * @throws ConfigException
     */
    public function setAfterAll(?Closure $afterAll): void
    {
        $this->assertNullableClosure($afterAll, 'afterAll');

        $this->afterAll = $afterAll;
    }

    /**
     * @return Closure(): void|null
     */
    public function getAfterAll(): ?Closure
    {
        return $this->afterAll;
    }

    /**
     * @param Closure(): void|null $beforeEach
     * @throws ConfigException
     */
    public function setBeforeEach(?Closure $beforeEach): void
    {
        $this->assertNullableClosure($beforeEach, 'beforeEach');

        $this->beforeEach = $beforeEach;
    }

    /**
     * @return Closure(): void|null
     */
    public function getBeforeEach(): ?Closure
    {
        return $this->beforeEach;
    }

    /**
     * @param Closure(): void|null $afterEach
     * @throws ConfigException
     */
    public function setAfterEach(?Closure $afterEach): void
    {
        $this->assertNullableClosure($afterEach, 'afterEach');

        $this->afterEach = $afterEach;
    }

    /**
     * @return Closure(): void|null
     */
    public function getAfterEach(): ?Closure
    {
        return $this->afterEach;
    }

    /**
     * @throws ConfigException
     */
    public function setAverageOf(int $averageOf): void
    {
        $this->assertPositiveInt($averageOf, 'averageOf');

        $this->averageOf = $averageOf;
    }

    public function getAverageOf(): int
    {
        return $this->averageOf;
    }

    /**
     * @throws ConfigException
     */
    public function setPerRun(int $perRun): void
    {
        $this->assertPositiveInt($perRun, 'perRun');

        $this->perRun = $perRun;
    }

    public function getPerRun(): int
    {
        return $this->perRun;
    }

    /**
     * @param Closure(): void|null $closure
     * @throws ConfigException
     */
    private function assertNullableClosure(?Closure $closure, string $name): void
    {
        if (!is_null($closure)) {
            $this->assertClosure($closure, $name);
        }
    }

    /**
     * @param Closure(): void $closure
     * @throws ConfigException
     */
    private function assertClosure(Closure $closure, string $name): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionFunction($closure);

        if ($reflection->getParameters()) {
            throw new ConfigException(sprintf('%s(): cannot have any parameters.', $name));
        }
    }

    /**
     * @throws ConfigException
     */
    private function assertPositiveInt(int $i, string $name): void
    {
        if ($i < 1) {
            throw new ConfigException(sprintf('%s(): cannot be less than 1.', $name));
        }
    }
}
