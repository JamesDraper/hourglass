<?php
declare(strict_types=1);

namespace Tests\Internal;

use Hourglass\Internal\BenchmarkInterface;
use Hourglass\Exception\ConfigException;
use Hourglass\Internal\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Closure;

class ConfigTest extends TestCase
{
    private Config $config;

    #[Test]
    public function beforeAllDefault(): void
    {
        // Assert that beforeAll defaults to null.

        $this->assertNull($this->config->getBeforeAll());
    }

    #[Test]
    #[Depends('beforeAllDefault')]
    public function beforeAllSucceedsWithClosure(): void
    {
        // Assert that beforeAll can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setBeforeAll($closure);

        $this->assertSame($closure, $this->config->getBeforeAll());
    }

    #[Test]
    #[Depends('beforeAllSucceedsWithClosure')]
    public function beforeAllSucceedsWithNull(): void
    {
        // Assert that beforeAll can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setBeforeAll($closure);
        $this->config->setBeforeAll(null);

        $this->assertNull($this->config->getBeforeAll());
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    #[Depends('beforeAllSucceedsWithClosure')]
    public function beforeAllFails(Closure $closure): void
    {
        // Assert that beforeAll fails if provided with an invalid closure.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('beforeAll(): cannot have any parameters.');

        $this->config->setBeforeAll($closure);
    }

    #[Test]
    public function afterAllDefault(): void
    {
        // Assert that afterAll defaults to null.

        $this->assertNull($this->config->getAfterAll());
    }

    #[Test]
    #[Depends('afterAllDefault')]
    public function afterAllSucceedsWithClosure(): void
    {
        // Assert that afterAll can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setAfterAll($closure);

        $this->assertSame($closure, $this->config->getAfterAll());
    }

    #[Test]
    #[Depends('afterAllSucceedsWithClosure')]
    public function afterAllSucceedsWithNull(): void
    {
        // Assert that afterAll can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setAfterAll($closure);
        $this->config->setAfterAll(null);

        $this->assertNull($this->config->getAfterAll());
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    #[Depends('afterAllSucceedsWithClosure')]
    public function afterAllFails(Closure $closure): void
    {
        // Assert that afterAll fails if provided with an invalid closure.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('afterAll(): cannot have any parameters.');

        $this->config->setAfterAll($closure);
    }

    #[Test]
    public function beforeEachDefault(): void
    {
        // Assert that beforeEach defaults to null.

        $this->assertNull($this->config->getBeforeEach());
    }

    #[Test]
    #[Depends('beforeEachDefault')]
    public function beforeEachSucceedsWithClosure(): void
    {
        // Assert that beforeEach can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setBeforeEach($closure);

        $this->assertSame($closure, $this->config->getBeforeEach());
    }

    #[Test]
    #[Depends('beforeEachSucceedsWithClosure')]
    public function beforeEachSucceedsWithNull(): void
    {
        // Assert that beforeEach can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setBeforeEach($closure);
        $this->config->setBeforeEach(null);

        $this->assertNull($this->config->getBeforeEach());
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    #[Depends('beforeEachSucceedsWithClosure')]
    public function beforeEachFails(Closure $closure): void
    {
        // Assert that beforeEach fails if provided with an invalid closure.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('beforeEach(): cannot have any parameters.');

        $this->config->setBeforeEach($closure);
    }

    #[Test]
    public function afterEachDefault(): void
    {
        // Assert that afterEach defaults to null.

        $this->assertNull($this->config->getAfterEach());
    }

    #[Test]
    #[Depends('afterEachDefault')]
    public function afterEachSucceedsWithClosure(): void
    {
        // Assert that afterEach can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setAfterEach($closure);

        $this->assertSame($closure, $this->config->getAfterEach());
    }

    #[Test]
    #[Depends('afterEachSucceedsWithClosure')]
    public function afterEachSucceedsWithNull(): void
    {
        // Assert that afterEach can be set using a valid closure.

        $closure = $this->goodClosure();

        $this->config->setAfterEach($closure);
        $this->config->setAfterEach(null);

        $this->assertNull($this->config->getAfterEach());
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    #[Depends('afterEachSucceedsWithClosure')]
    public function afterEachFails(Closure $closure): void
    {
        // Assert that afterEach fails if provided with an invalid closure.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('afterEach(): cannot have any parameters.');

        $this->config->setAfterEach($closure);
    }

    #[Test]
    public function averageOfDefault(): void
    {
        // Assert that averageOf defaults to BenchmarkInterface::DEFAULT_AVERAGE_OF.

        $this->assertSame(BenchmarkInterface::DEFAULT_AVERAGE_OF, $this->config->getAverageOf());
    }

    #[Test]
    #[DataProvider('providerGoodInts')]
    #[Depends('averageOfDefault')]
    public function averageOfSucceeds(int $averageOf): void
    {
        // Assert that averageOf can be set to a positive integer.

        $this->config->setAverageOf($averageOf);

        $this->assertSame($averageOf, $this->config->getAverageOf());
    }

    #[Test]
    #[DataProvider('providerBadInts')]
    #[Depends('averageOfSucceeds')]
    public function averageOfFails(int $averageOf): void
    {
        // Assert that averageOf cannot be set to a non-positive integer.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('averageOf(): cannot be less than 1.');

        $this->config->setAverageOf($averageOf);
    }

    #[Test]
    public function perRunDefault(): void
    {
        // Assert that perRun defaults to BenchmarkInterface::DEFAULT_PER_RUN.

        $this->assertSame(BenchmarkInterface::DEFAULT_PER_RUN, $this->config->getPerRun());
    }

    #[Test]
    #[DataProvider('providerGoodInts')]
    #[Depends('perRunDefault')]
    public function perRunSucceeds(int $perRun): void
    {
        // Assert that perRun can be set to a positive integer.

        $this->config->setPerRun($perRun);

        $this->assertSame($perRun, $this->config->getPerRun());
    }

    #[Test]
    #[DataProvider('providerBadInts')]
    #[Depends('perRunSucceeds')]
    public function perRunOfFails(int $perRun): void
    {
        // Assert that perRun cannot be set to a non-positive integer.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('perRun(): cannot be less than 1.');

        $this->config->setPerRun($perRun);
    }

    /**
     * @return mixed[]
     */
    public static function providerBadClosures(): array
    {
        return [
            [fn (string $str): string => $str],
            [fn (int $i = 0): int => $i],
            [fn (int $i): int => $i],
            [fn (string $str) => $str],
            [fn (int $i = 0) => $i],
            [fn (int $i) => $i],
            [fn ($i) => $i],
        ];
    }

    /**
     * @return mixed[]
     */
    public static function providerGoodInts(): array
    {
        return [
            [1],
            [2],
            [3],
            [100],
        ];
    }

    /**
     * @return mixed[]
     */
    public static function providerBadInts(): array
    {
        return [
            [0],
            [-1],
            [-2],
            [-100],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new Config;
    }

    private function goodClosure(): Closure
    {
        return function (): void {
            // do nothing.
        };
    }
}
