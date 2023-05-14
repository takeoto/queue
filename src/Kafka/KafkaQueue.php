<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka;

use App\System\Queue\Config;
use App\System\Queue\Contract\ConfigInterface;
use App\System\Queue\Contract\ConsumerInterface;
use App\System\Queue\Contract\ProducerInterface;
use App\System\Queue\Contract\QueueInterface;

class KafkaQueue implements QueueInterface
{
    /**
     * Base config.
     * @var mixed[]
     */
    private array $config;

    /**
     * @param mixed[] $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function makeConsumer(?ConfigInterface $config = null): ConsumerInterface
    {
        return new KafkaConsumer($this->makeConfig('consumer', $config));
    }

    /**
     * @inheritDoc
     */
    public function makeProducer(?ConfigInterface $config = null): ProducerInterface
    {
        return new KafkaProducer($this->makeConfig('producer', $config));
    }

    /**
     * @param string $type
     * @param ConfigInterface|null $overwrite
     * @return ConfigInterface
     */
    private function makeConfig(string $type, ?ConfigInterface $overwrite = null): ConfigInterface
    {
        $default = new Config(
            array_merge(
                $this->config['common'],
                $this->config[$type] ?? [],
            )
        );

        return $overwrite === null ? $default : $default->extend($overwrite);
    }
}
