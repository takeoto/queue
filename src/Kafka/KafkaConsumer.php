<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka;

use App\System\Queue\Contract\ConfigInterface;
use App\System\Queue\Contract\ConsumerInterface;
use App\System\Queue\Contract\MessageInterface;
use App\System\Queue\Kafka\Dictionary\KafkaConsumerDict;
use App\System\Queue\Kafka\Dictionary\KafkaDict;
use RdKafka\Conf as RdKafkaConf;
use RdKafka\KafkaConsumer as RdKafkaConsumer;
use RdKafka\Exception as RdKafkaException;

class KafkaConsumer implements ConsumerInterface
{
    private RdKafkaConsumer $consumer;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->ensureRequirements($config);
        $this->build($config);
        $this->registerShutdown();
    }

    /**
     * @inheritDoc
     */
    public function consume(string $queue, callable $onError = null): ?MessageInterface
    {
        $this->subscriberOn($this->consumer, $queue);
        $timeout = $this->config->get(KafkaConsumerDict::CONSUMING_TIMEOUT, 120 * 1000);
        $message = $this->consumer->consume($timeout);

        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                return new KafkaMessage($message);
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                return null;
            default:
                if (is_callable($onError)) {
                    $onError($message->errstr());
                }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function commit()
    {
        try {
            /* @see https://github.com/confluentinc/confluent-kafka-python/issues/295#issuecomment-355907183 */
            $this->consumer->commit();
        } catch (RdKafkaException $exception) {
            if ($exception->getCode() !== 168) {
                throw $exception;
            }
        }
    }

    /**
     * Configure and create the consumer instance.
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

        $this->consumer = new RdKafkaConsumer($cfg);
    }

    /**
     * Check that config is correct.
     * @param ConfigInterface $config
     * @throws \Exception
     */
    private function ensureRequirements(ConfigInterface $config): void
    {
        switch (false) {
            case $config->has($name = KafkaDict::CONFIG_METADATA_BROKER_LIST):
                throw new \Exception(sprintf('"%s" is required config for "%s" queue!', $name, self::class));
        }
    }

    /**
     * @throws \RdKafka\Exception
     */
    private function registerShutdown(): void
    {
        register_shutdown_function(fn() => $this->consumer->unsubscribe());
    }

    /**
     * Subscriber consumer on queue listening.
     * @param RdKafkaConsumer $consumer
     * @param string $queue
     * @throws \RdKafka\Exception
     */
    private function subscriberOn(RdKafkaConsumer $consumer, string $queue): void
    {
        if (in_array($queue, $consumer->getSubscription(), true)) {
            return;
        }

        $consumer->unsubscribe();
        $consumer->subscribe([$queue]);
    }
}
