<?php
declare(strict_types=1);

namespace Tests\Unit\Internal\Timer;

use Hourglass\Internal\Timer\Moment;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MomentTest extends TestCase
{
    #[Test]
    #[DataProvider('providerSecondsAndNanoseconds')]
    public function itCanBeCreated(string $expected, int $seconds, int $nanoseconds): void
    {
        // Assert that a Moment object can be created from a number of seconds
        //     and nanoseconds representing a moment in time.

        $moment = Moment::fromSecondsAndNanoseconds($seconds, $nanoseconds);

        $this->assertInstanceOf(Moment::class, $moment);
        $this->assertSame($expected, $moment->toNanoseconds());
    }

    /**
     * @return mixed[]
     */
    public static function providerSecondsAndNanoseconds(): array
    {
        return [
            ['1000000001', 1, 1],
            ['1000000002', 1, 2],
            ['1000000003', 1, 3],
            ['123000000456', 123, 456],
        ];
    }
}
