<?php
declare(strict_types=1);

namespace Hourglass\Internal;

use Hourglass\Internal\Timer\TimerInterface;
use Hourglass\Exception\GeneralException;
use Hourglass\Exception\ConfigException;
use Hourglass\Internal\Timer\Timer;
use Hourglass\Benchmark;

use Throwable;
use Closure;

use function is_null;
use function bcadd;
use function bcdiv;
use function bcmul;
use function rtrim;

class Calculator
{
    private const PRECISION = 10;

    /**
     * @throws GeneralException
     * @throws ConfigException
     */
    public static function runBenchmark(Benchmark $benchmark): string
    {
        $validator = Validator::make();

        $averageOf = $benchmark->averageOf();
        $perRun = $benchmark->perRun();

        $validator->validateAverageOf($averageOf);
        $validator->validatePerRun($perRun);

        $calculator = self::make();

        return $calculator(
            static function () use ($benchmark): void {
                $benchmark->benchmark();
            },
            static function () use ($benchmark): void {
                $benchmark->beforeAll();
            },
            static function () use ($benchmark): void {
                $benchmark->afterAll();
            },
            static function () use ($benchmark): void {
                $benchmark->beforeEach();
            },
            static function () use ($benchmark): void {
                $benchmark->afterEach();
            },
            $averageOf,
            $perRun,
        );
    }

    /**
     * @throws GeneralException
     */
    public static function runConfig(Config $config): string
    {
        $calculator = self::make();

        /** @var Closure(): void $benchmark */
        $benchmark = $config->getBenchmark();

        return $calculator(
            $benchmark,
            $config->getBeforeAll(),
            $config->getAfterAll(),
            $config->getBeforeEach(),
            $config->getAfterEach(),
            $config->getAverageOf(),
            $config->getPerRun(),
        );
    }

    private static function make(): self
    {
        static $instance = null;

        if (is_null($instance)) {
            $timer = Timer::make();

            $instance = new self($timer);
        }

        return $instance;
    }

    public function __construct(private readonly TimerInterface $timer)
    {
    }

    /**
     * @param Closure(): void $benchmark
     * @param Closure(): void|null $beforeAll
     * @param Closure(): void|null $afterAll
     * @param Closure(): void|null $beforeEach
     * @param Closure(): void|null $afterEach
     * @param int $averageOf
     * @param int $perRun
     * @return string the mean duration in nanoseconds.
     * @throws GeneralException
     */
    public function __invoke(
        Closure $benchmark,
        ?Closure $beforeAll,
        ?Closure $afterAll,
        ?Closure $beforeEach,
        ?Closure $afterEach,
        int $averageOf,
        int $perRun,
    ): string {
        $dividend = '0';

        $this->runNullableCallable($beforeAll, 'beforeAll');

        for ($i = 0; $i < $averageOf; $i++) {
            $this->runNullableCallable($beforeEach, 'beforeEach');

            try {
                $duration = ($this->timer)($benchmark, $perRun);
            } catch (Throwable $throwable) {
                throw new GeneralException('benchmark', $throwable);
            }

            $dividend = bcadd($dividend, $duration);

            $this->runNullableCallable($afterEach, 'afterEach');
        }

        $this->runNullableCallable($afterAll, 'afterAll');

        $averageOf = (string) $averageOf;
        $perRun = (string) $perRun;
        $divisor = bcmul($averageOf, $perRun);

        $mean = bcdiv($dividend, $divisor, self::PRECISION);

        return rtrim(rtrim($mean, '0'), '.');
    }

    /**
     * @param Closure(): void|null $closure
     * @throws GeneralException
     */
    private function runNullableCallable(?Closure $closure, string $name): void
    {
        if (!is_null($closure)) {
            try {
                $closure();
            } catch (Throwable $throwable) {
                throw new GeneralException($name, $throwable);
            }
        }
    }
}
