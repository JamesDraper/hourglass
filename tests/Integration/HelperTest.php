<?php
declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function str_ends_with;
use function hourglass;

class HelperTest extends TestCase
{
    #[Test]
    public function itRunsFromAHelper(): void
    {
        $result = hourglass()
            ->benchmark(function (): void {
                // do nothing.
            })
            ->run();

        $this->assertTrue(str_ends_with($result, 'nanoseconds'));
    }
}
