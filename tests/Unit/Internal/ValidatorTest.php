<?php
declare(strict_types=1);

namespace Tests\Unit\Internal;

use PHPUnit\Framework\Attributes\DataProvider;
use Hourglass\Exception\ConfigException;
use Hourglass\Internal\Validator;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Closure;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertBenchmarkSucceeds(): void
    {
        // Assert that benchmark passes validation with a closure that takes no parameters.

        $this->validator->validateBenchmark($this->goodClosure());
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    public function assertBenchmarkFails(Closure $closure): void
    {
        // Assert that benchmark passes fails with a closure that takes parameters.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('benchmark(): cannot have any parameters.');

        $this->validator->validateBenchmark($closure);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertBeforeAllSucceedsWithClosure(): void
    {
        // Assert that benchmark passes validation with a closure that takes no parameters.

        $this->validator->validateBeforeAll($this->goodClosure());
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertBeforeAllSucceedsWithNull(): void
    {
        // Assert that benchmark passes validation with null.

        $this->validator->validateBeforeAll(null);
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    public function assertBeforeAllFails(Closure $closure): void
    {
        // Assert that benchmark fails validation with a closure that takes parameters.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('beforeAll(): cannot have any parameters.');

        $this->validator->validateBeforeAll($closure);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertAfterAllSucceedsWithClosure(): void
    {
        // Assert that benchmark passes validation with a closure that takes no parameters.

        $this->validator->validateAfterAll($this->goodClosure());
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertAfterAllSucceedsWithNull(): void
    {
        // Assert that benchmark passes validation with null.

        $this->validator->validateAfterAll(null);
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    public function assertAfterAllFails(Closure $closure): void
    {
        // Assert that benchmark fails validation with a closure that takes parameters.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('afterAll(): cannot have any parameters.');

        $this->validator->validateAfterAll($closure);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertBeforeEachSucceedsWithClosure(): void
    {
        // Assert that benchmark passes validation with a closure that takes no parameters.

        $this->validator->validateBeforeEach($this->goodClosure());
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertBeforeEachSucceedsWithNull(): void
    {
        // Assert that benchmark passes validation with null.

        $this->validator->validateBeforeEach(null);
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    public function assertBeforeEachFails(Closure $closure): void
    {
        // Assert that benchmark fails validation with a closure that takes parameters.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('beforeEach(): cannot have any parameters.');

        $this->validator->validateBeforeEach($closure);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertAfterEachSucceedsWithClosure(): void
    {
        // Assert that benchmark passes validation with a closure that takes no parameters.

        $this->validator->validateAfterEach($this->goodClosure());
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function assertAfterEachSucceedsWithNull(): void
    {
        // Assert that benchmark passes validation with null.

        $this->validator->validateAfterEach(null);
    }

    #[Test]
    #[DataProvider('providerBadClosures')]
    public function assertAfterEachFails(Closure $closure): void
    {
        // Assert that benchmark fails validation with a closure that takes parameters.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('afterEach(): cannot have any parameters.');

        $this->validator->validateAfterEach($closure);
    }

    #[Test]
    #[DataProvider('providerGoodInts')]
    #[DoesNotPerformAssertions]
    public function validateAverageOfSucceeds(int $i): void
    {
        // Assert that averageOf passes validation if the value is 1 or greater.

        $this->validator->validateAverageOf($i);
    }

    #[Test]
    #[DataProvider('providerBadInts')]
    public function validateAverageOfFails(int $i): void
    {
        // Assert that averageOf fails validation if the value is less than 1.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('averageOf(): must be 1 or greater.');

        $this->validator->validateAverageOf($i);
    }

    #[Test]
    #[DataProvider('providerGoodInts')]
    #[DoesNotPerformAssertions]
    public function validatePerRunSucceeds(int $i): void
    {
        // Assert that perRun passes validation if the value is 1 or greater.

        $this->validator->validatePerRun($i);
    }

    #[Test]
    #[DataProvider('providerBadInts')]
    public function validatePerRunFails(int $i): void
    {
        // Assert that perRun fails validation if the value is less than 1.

        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('perRun(): must be 1 or greater.');

        $this->validator->validatePerRun($i);
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

        $this->validator = new Validator;
    }

    private function goodClosure(): Closure
    {
        return function (): void {
            // do nothing.
        };
    }
}
