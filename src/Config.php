<?php

declare(strict_types=1);

namespace App\System\Queue;

use App\System\Queue\Contract\ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var mixed[]
     */
    private array $config;

    /**
     * @param mixed[] $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, $value): void
    {
        $this->config[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name, $default = null)
    {
        return $this->has($name) ? $this->config[$name] : $default;
    }

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return isset($this->config[$name]);
    }

    /**
     * @inheritDoc
     */
    public function extend(ConfigInterface $config, bool $immutable = false): ConfigInterface
    {
        $config = array_merge($this->config, $config->toArray());

        if ($immutable) {
            return new Config($config);
        }

        $this->config = $config;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->config;
    }
}
