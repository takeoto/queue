<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka;

use App\System\Queue\Contract\ConfigInterface;
use App\System\Queue\Contract\ProducerInterface;
use App\System\Queue\Kafka\Dictionary\KafkaConsumerDict;
use App\System\Queue\Kafka\Dictionary\KafkaProducerDict;
use RdKafka\Conf as RdKafkaConf;
use RdKafka\Producer as RdKafkaProducer;

class KafkaProducer implements ProducerInterface
{
    private RdKafkaProducer $producer;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->build($config);
    }

    /**
     * @inheritDoc
     */
    public function produce(string $queue, $data, string $key = null): void
    {
        $topic = $this->producer->newTopic($queue);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $this->prepareData($data), $key);
    }

    /**
     * @inheritDoc
     */
    public function commit(): void
    {
        $this->producer->poll(0);
        $this->producer->flush((int)$this->config->get(KafkaProducerDict::PRODUCING_TIMEOUT, 1000));
    }

    /**
     * Configure and create the producer instance.
     * @param ConfigInterface $config
     */
    private function build(ConfigInterface $config): void
    {
        $this->config = $config;
        $cfg = new RdKafkaConf();

        foreach (KafkaConsumerDict::CONFIG as $name) {
            if ($config->has($name)) {
                $cfg->set($name, $config->get($name));
            }
        }

        $this->producer = new RdKafkaProducer($cfg);
    }

    /**
     * @param mixed $data
     * @return mixed
     * @throws \Throwable
     */
    private function prepareData($data)
    {
        return is_string($data) ? $data : \json_encode($data, JSON_THROW_ON_ERROR);
    }
}
