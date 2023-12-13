<?php
declare(strict_types=1);

namespace Tests\Unit\Internal;

use Hourglass\Internal\Timer\TimerInterface;
use Hourglass\Exception\GeneralException;
use Hourglass\Internal\Calculator;

use Mockery\MockInterface;
use Mockery;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use LogicException;
use Closure;

use function array_shift;
use function func_get_args;

class CalculatorTest extends TestCase
{
    private MockInterface&TimerInterface $timer;

    private Calculator $calculator;

    /**
     * @var string[]
     */
    private array $calls = [];

    /**
     * @param string[] $expectedCalls
     * @param string[] $durations
     */
    #[Test]
    #[DataProvider('providerScenarios')]
    public function itCalculates(
        array $expectedCalls,
        string $expectedMean,
        array $durations,
        bool $hasBeforeAll,
        bool $hasAfterAll,
        bool $hasBeforeEach,
        bool $hasAfterEach,
        int $averageOf,
        int $perRun,
    ): void {
        // Assert that the calculator invokes all the closures in the correct order
        //     and calculates the correct mean.

        $beforeAll = $hasBeforeAll ? $this->makeClosure('beforeAll') : null;
        $afterAll = $hasAfterAll ? $this->makeClosure('afterAll') : null;
        $beforeEach = $hasBeforeEach ? $this->makeClosure('beforeEach') : null;
        $afterEach = $hasAfterEach ? $this->makeClosure('afterEach') : null;
        $benchmark = $this->makeClosure('benchmark');

        $this
            ->timer
            ->expects('__invoke')
            ->withArgs(function () use ($benchmark, $perRun): bool {
                $args = func_get_args();

                return (2 === count($args))
                    && ($args[0] === $benchmark)
                    && ($args[1] === $perRun);
            })
            ->andReturnUsing(function (Closure $benchmark) use (&$durations, $perRun): string {
                for ($i = 0; $i < $perRun; $i++) {
                    $benchmark();
                }

                return array_shift($durations) ?? '0';
            });

        $result = ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, $averageOf, $perRun);

        $this->assertSame($expectedCalls, $this->calls);
        $this->assertSame($expectedMean, $result);
    }

    /**
     * @return mixed[]
     */
    public static function providerScenarios(): array
    {
        return [
            'all closures, 1 value only' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '1',
                'durations' => ['1'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 1,
                'perRun' => 1,
            ],
            'all closures, 1 per run, average of 8, decimal number' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '4.5',
                'durations' => ['1', '2', '3', '4', '5', '6', '7', '8'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 8,
                'perRun' => 1,
            ],
            'all closures, 1 per run, average of 8, whole number' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '3',
                'durations' => ['2', '4', '2', '4', '2', '4', '2', '4'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 8,
                'perRun' => 1,
            ],
            'all closures, 3 per run, average of 3' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'benchmark',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'benchmark',
                    'benchmark',
                    'afterEach',
                    'beforeEach',
                    'benchmark',
                    'benchmark',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '0.6666666666',
                'durations' => ['1', '2', '3', '4', '5', '6', '7', '8', '9'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 3,
                'perRun' => 3,
            ],
            'without beforeAll' => [
                'expectedCalls' => [
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '1',
                'durations' => ['1'],
                'hasBeforeAll' => false,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 1,
                'perRun' => 1,
            ],
            'without beforeEach' => [
                'expectedCalls' => [
                    'beforeAll',
                    'benchmark',
                    'afterEach',
                    'afterAll',
                ],
                'expectedMean' => '1',
                'durations' => ['1'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => false,
                'hasAfterEach' => true,
                'averageOf' => 1,
                'perRun' => 1,
            ],
            'without afterEach' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'afterAll',
                ],
                'expectedMean' => '1',
                'durations' => ['1'],
                'hasBeforeAll' => true,
                'hasAfterAll' => true,
                'hasBeforeEach' => true,
                'hasAfterEach' => false,
                'averageOf' => 1,
                'perRun' => 1,
            ],
            'without afterAll' => [
                'expectedCalls' => [
                    'beforeAll',
                    'beforeEach',
                    'benchmark',
                    'afterEach',
                ],
                'expectedMean' => '1',
                'durations' => ['1'],
                'hasBeforeAll' => true,
                'hasAfterAll' => false,
                'hasBeforeEach' => true,
                'hasAfterEach' => true,
                'averageOf' => 1,
                'perRun' => 1,
            ],
        ];
    }

    #[Test]
    public function beforeAllFails(): void
    {
        // Assert that beforeAll() handles thrown errors properly.

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('beforeAll(): An unexpected error has occurred.');

        $beforeAll = $this->makeErroneousClosure();
        $afterAll = $this->makeClosure('afterAll');
        $beforeEach = $this->makeClosure('beforeEach');
        $afterEach = $this->makeClosure('afterEach');
        $benchmark = $this->makeClosure('benchmark');

        ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, 1, 1);
    }

    #[Test]
    public function afterAllFails(): void
    {
        // Assert that afterAll() handles thrown errors properly.

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('afterAll(): An unexpected error has occurred.');

        $this->timer->expects('__invoke');

        $beforeAll = $this->makeClosure('beforeAll');
        $afterAll = $this->makeErroneousClosure();
        $beforeEach = $this->makeClosure('beforeEach');
        $afterEach = $this->makeClosure('afterEach');
        $benchmark = $this->makeClosure('benchmark');

        ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, 1, 1);
    }

    #[Test]
    public function beforeEachFails(): void
    {
        // Assert that beforeEach() handles thrown errors properly.

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('beforeEach(): An unexpected error has occurred.');

        $beforeAll = $this->makeClosure('beforeAll');
        $afterAll = $this->makeClosure('afterAll');
        $beforeEach = $this->makeErroneousClosure();
        $afterEach = $this->makeClosure('afterEach');
        $benchmark = $this->makeClosure('benchmark');

        ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, 1, 1);
    }

    #[Test]
    public function afterEachFails(): void
    {
        // Assert that afterEach() handles thrown errors properly.

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('afterEach(): An unexpected error has occurred.');

        $this->timer->expects('__invoke');

        $beforeAll = $this->makeClosure('beforeAll');
        $afterAll = $this->makeClosure('afterAll');
        $beforeEach = $this->makeClosure('beforeEach');
        $afterEach = $this->makeErroneousClosure();
        $benchmark = $this->makeClosure('benchmark');

        ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, 1, 1);
    }

    #[Test]
    public function benchmarkFails(): void
    {
        // Assert that benchmark() handles thrown errors properly.

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('benchmark(): An unexpected error has occurred.');

        $beforeAll = $this->makeClosure('beforeAll');
        $afterAll = $this->makeClosure('afterAll');
        $beforeEach = $this->makeClosure('beforeEach');
        $afterEach = $this->makeClosure('afterEach');
        $benchmark = $this->makeErroneousClosure();

        $this
            ->timer
            ->expects('__invoke')
            ->withArgs(function () use ($benchmark): bool {
                $args = func_get_args();

                return (2 === count($args))
                    && ($args[0] === $benchmark)
                    && ($args[1] === 1);
            })
            ->andReturnUsing(function (Closure $benchmark): string {
                $benchmark();

                return '1';
            });

        ($this->calculator)($benchmark, $beforeAll, $afterAll, $beforeEach, $afterEach, 1, 1);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->timer = Mockery::mock(TimerInterface::class);
        $this->calculator = new Calculator($this->timer);
    }

    /**
     * @return Closure(): void
     */
    private function makeClosure(string $name): Closure
    {
        return function () use ($name): void {
            $this->calls[] = $name;
        };
    }

    /**
     * @return Closure(): void
     */
    private function makeErroneousClosure(): Closure
    {
        return function (): void {
            throw new LogicException;
        };
    }
}
