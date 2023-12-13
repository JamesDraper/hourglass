<?php
declare(strict_types=1);

namespace Tests\Unit\Internal\Timer;

use Hourglass\Internal\Timer\Timer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use ReflectionClass;

class TimerTest extends TestCase
{
    private int $calls = 0;

    private Timer $timer;

    #[Test]
    public function assertFinal(): void
    {
        // Assert that the class is final (so it runs faster).

        $reflection = new ReflectionClass($this->timer);

        $this->assertTrue($reflection->isFinal());
    }

    #[Test]
    public function assertRuns(): void
    {
        // Assert that when run with a callback, a duration is returned and the callback
        //     is called the specified number of times.

        $closure = function (): void {
            $this->calls++;
        };

        $result = ($this->timer)($closure, 123);

        $this->assertIsString($result);
        $this->assertSame(123, $this->calls);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->timer = new Timer;
    }
}
