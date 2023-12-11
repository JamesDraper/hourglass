<?php
declare(strict_types=1);

namespace Tests;

use Hourglass\Duration;
use Hourglass\Moment;

use Mockery\MockInterface;
use Mockery;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DurationTest extends TestCase
{
    #[Test]
    #[DataProvider('providerMoments')]
    public function itCanBeCreatedFromMoments(string $expected, string $before, string $after): void
    {
        // Assert that a Duration object can be created from 2 Moment objects.

        [$before, $after] = [
            $this->makeMomentMock($before),
            $this->makeMomentMock($after),
        ];

        $duration = Duration::fromMoments($before, $after);

        $this->assertInstanceOf(Duration::class, $duration);
        $this->assertSame($expected, $duration->toNanoseconds());
    }

    /**
     * @return mixed[]
     */
    public static function providerMoments(): array
    {
        return [
            ['333', '123', '456'],
            ['333', '456', '789'],
            ['4444', '1234', '5678'],
        ];
    }

    private function makeMomentMock(string $nanoseconds): MockInterface&Moment
    {
        $moment = Mockery::mock(Moment::class);

        $moment
            ->allows()
            ->toNanoseconds()
            ->andReturn($nanoseconds);

        return $moment;
    }
}
