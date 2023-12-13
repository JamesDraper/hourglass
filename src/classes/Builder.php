<?php
declare(strict_types=1);

namespace Hourglass;

use Hourglass\Exception\ConfigException;
use Hourglass\Internal\Config;

use Closure;

use function is_null;

final class Builder
{
    public static function make(): self
    {
        $config = Config::make();

        return new self($config);
    }

    /**
     * @param Closure(): void $benchmark
     * @throws ConfigException
     */
    public function benchmark(Closure $benchmark): self
    {
        $this->config->setBenchmark($benchmark);

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function run(): self
    {
        if (is_null($this->config->getBenchmark())) {
            throw new ConfigException('benchmark(): must be set.');
        }

        return $this;
    }

    /**
     * @param Closure(): void|null $beforeAll
     * @throws ConfigException
     */
    public function beforeAll(?Closure $beforeAll): self
    {
        $this->config->setBeforeAll($beforeAll);

        return $this;
    }

    /**
     * @param Closure(): void|null $afterAll
     * @throws ConfigException
     */
    public function afterAll(?Closure $afterAll): self
    {
        $this->config->setAfterAll($afterAll);

        return $this;
    }

    /**
     * @param Closure(): void|null $beforeEach
     * @throws ConfigException
     */
    public function beforeEach(?Closure $beforeEach): self
    {
        $this->config->setBeforeEach($beforeEach);

        return $this;
    }

    /**
     * @param Closure(): void|null $afterEach
     * @throws ConfigException
     */
    public function afterEach(?Closure $afterEach): self
    {
        $this->config->setAfterEach($afterEach);

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function averageOf(int $averageOf): self
    {
        $this->config->setAverageOf($averageOf);

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function perRun(int $perRun): self
    {
        $this->config->setPerRun($perRun);

        return $this;
    }

    private function __construct(private readonly Config $config)
    {
    }
}
