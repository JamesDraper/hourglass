<?php
declare(strict_types=1);

namespace Hourglass\Internal;

use Hourglass\Exception\ConfigException;

use Closure;

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

    public function __construct(private readonly Validator $validator)
    {
    }

    /**
     * @param Closure(): void $benchmark
     * @throws ConfigException
     */
    public function setBenchmark(Closure $benchmark): void
    {
        $this->validator->validateBenchmark($benchmark);

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
        $this->validator->validateBeforeAll($beforeAll);

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
        $this->validator->validateAfterAll($afterAll);

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
        $this->validator->validateBeforeEach($beforeEach);

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
        $this->validator->validateAfterEach($afterEach);

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
        $this->validator->validateAverageOf($averageOf);

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
        $this->validator->validatePerRun($perRun);

        $this->perRun = $perRun;
    }

    public function getPerRun(): int
    {
        return $this->perRun;
    }
}
