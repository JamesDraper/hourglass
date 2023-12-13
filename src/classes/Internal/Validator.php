<?php
declare(strict_types=1);

namespace Hourglass\Internal;

use Hourglass\Exception\ConfigException;

use ReflectionFunction;
use Closure;

use function is_null;
use function sprintf;

class Validator
{
    public static function make(): self
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self;
        }

        return $instance;
    }

    /**
     * @param Closure(): void $benchmark
     * @throws ConfigException
     */
    public function validateBenchmark(Closure $benchmark): void
    {
        $this->assertClosure($benchmark, 'benchmark');
    }

    /**
     * @param Closure(): void|null $beforeAll
     * @throws ConfigException
     */
    public function validateBeforeAll(?Closure $beforeAll): void
    {
        $this->assertNullableClosure($beforeAll, 'beforeAll');
    }

    /**
     * @param Closure(): void|null $afterAll
     * @throws ConfigException
     */
    public function validateAfterAll(?Closure $afterAll): void
    {
        $this->assertNullableClosure($afterAll, 'afterAll');
    }

    /**
     * @param Closure(): void|null $beforeEach
     * @throws ConfigException
     */
    public function validateBeforeEach(?Closure $beforeEach): void
    {
        $this->assertNullableClosure($beforeEach, 'beforeEach');
    }

    /**
     * @param Closure(): void|null $afterEach
     * @throws ConfigException
     */
    public function validateAfterEach(?Closure $afterEach): void
    {
        $this->assertNullableClosure($afterEach, 'afterEach');
    }

    /**
     * @throws ConfigException
     */
    public function validateAverageOf(int $averageOf): void
    {
        $this->assertPositiveInt($averageOf, 'averageOf');
    }

    /**
     * @throws ConfigException
     */
    public function validatePerRun(int $perRun): void
    {
        $this->assertPositiveInt($perRun, 'perRun');
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
            throw new ConfigException(sprintf('%s(): must be 1 or greater.', $name));
        }
    }
}
