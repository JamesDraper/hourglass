<?php
declare(strict_types=1);

namespace Tests\Internal;

use Hourglass\Internal\BenchmarkInterface;
use Hourglass\Internal\Validator;
use Hourglass\Internal\Config;

use Mockery\MockInterface;
use Mockery;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Closure;

class ConfigTest extends TestCase
{
    private MockInterface&Validator $validator;

    private Config $config;

    #[Test]
    public function benchmarkDefault(): void
    {
        // Assert that benchmark defaults to null.

        $this->assertNull($this->config->getBenchmark());
    }

    #[Test]
    public function benchmarkCanBeSet(): void
    {
        // Assert that benchmark can be set and calls the validator.

        $closure = $this->closure();

        $this
            ->validator
            ->expects()
            ->validateBenchmark($closure);

        $this->config->setBenchmark($closure);

        $this->assertSame($closure, $this->config->getBenchmark());
    }

    #[Test]
    public function beforeAllDefault(): void
    {
        // Assert that beforeAll defaults to null.

        $this->assertNull($this->config->getBeforeAll());
    }

    #[Test]
    public function beforeAllCanBeSet(): void
    {
        // Assert that beforeAll can be set and calls the validator.

        $closure = $this->closure();

        $this
            ->validator
            ->expects()
            ->validateBeforeAll($closure);

        $this->config->setBeforeAll($closure);

        $this->assertSame($closure, $this->config->getBeforeAll());
    }

    #[Test]
    public function afterAllDefault(): void
    {
        // Assert that afterAll defaults to null.

        $this->assertNull($this->config->getAfterAll());
    }

    #[Test]
    public function afterAllCanBeSet(): void
    {
        // Assert that afterAll can be set and calls the validator.

        $closure = $this->closure();

        $this
            ->validator
            ->expects()
            ->validateAfterAll($closure);

        $this->config->setAfterAll($closure);

        $this->assertSame($closure, $this->config->getAfterAll());
    }

    #[Test]
    public function beforeEachDefault(): void
    {
        // Assert that beforeEach defaults to null.

        $this->assertNull($this->config->getBeforeEach());
    }

    #[Test]
    public function beforeEachCanBeSet(): void
    {
        // Assert that beforeEach can be set and calls the validator.

        $closure = $this->closure();

        $this
            ->validator
            ->expects()
            ->validateBeforeEach($closure);

        $this->config->setBeforeEach($closure);

        $this->assertSame($closure, $this->config->getBeforeEach());
    }

    #[Test]
    public function afterEachDefault(): void
    {
        // Assert that afterEach defaults to null.

        $this->assertNull($this->config->getAfterEach());
    }

    #[Test]
    public function afterEachCanBeSet(): void
    {
        // Assert that afterEach can be set and calls the validator.

        $closure = $this->closure();

        $this
            ->validator
            ->expects()
            ->validateAfterEach($closure);

        $this->config->setAfterEach($closure);

        $this->assertSame($closure, $this->config->getAfterEach());
    }

    #[Test]
    public function averageOfDefault(): void
    {
        // Assert that averageOf defaults to BenchmarkInterface::DEFAULT_AVERAGE_OF.

        $this->assertSame(BenchmarkInterface::DEFAULT_AVERAGE_OF, $this->config->getAverageOf());
    }

    #[Test]
    public function averageOfCanBeSet(): void
    {
        // Assert that averageOf can be set and calls the validator.

        $this
            ->validator
            ->expects()
            ->validateAverageOf(123);

        $this->config->setAverageOf(123);

        $this->assertSame(123, $this->config->getAverageOf());
    }

    #[Test]
    public function perRunDefault(): void
    {
        // Assert that perRun defaults to BenchmarkInterface::DEFAULT_PER_RUN.

        $this->assertSame(BenchmarkInterface::DEFAULT_PER_RUN, $this->config->getPerRun());
    }

    #[Test]
    public function perRunCanBeSet(): void
    {
        // Assert that perRun can be set and calls the validator.

        $this
            ->validator
            ->expects()
            ->validatePerRun(123);

        $this->config->setPerRun(123);

        $this->assertSame(123, $this->config->getPerRun());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = Mockery::mock(Validator::class);
        $this->config = new Config($this->validator);
    }

    private function closure(): Closure
    {
        return function (): void {
            // do nothing.
        };
    }
}
