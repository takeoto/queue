<?php

namespace App\System\Queue\Contract;

interface ConfigInterface
{
    /**
     * Set the config value.
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void;

    /**
     * Get the config value.
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Check that the config value is set.
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Convert the object in to array.
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * Extend the config.
     * @param ConfigInterface $config
     * @param bool $immutable - create new instance of config.
     * @return self
     */
    public function extend(ConfigInterface $config, bool $immutable = true): self;
}
