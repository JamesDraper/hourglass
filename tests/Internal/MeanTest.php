<?php
declare(strict_types=1);

namespace Tests\Internal;

use Hourglass\Internal\Mean;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MeanTest extends TestCase
{
    #[Test]
    public function itCalculates(): void
    {
        $mean = new Mean;

        $durations = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        $result = $mean($durations, 3, 4);

        $this->assertSame('6.5', $result);
    }
}
