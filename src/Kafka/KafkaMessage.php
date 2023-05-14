<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka;

use App\System\Queue\Contract\MessageInterface;
use RdKafka\Message as RdKafkaMessage;

class KafkaMessage implements MessageInterface
{
    private RdKafkaMessage $message;

    /**
     * @var mixed
     */
    private $preparedData = null;

    public function __construct(RdKafkaMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getKey(): ?string
    {
        return $this->message->key;
    }

    /**
     * @inheritDoc
     */
    public function getData(bool $original = false)
    {
        return $original ? $this->message->payload : $this->prepareData($this->message->payload);
    }

    /**
     * @param string $data
     * @return mixed[]|string
     */
    private function prepareData(string $data)
    {
        if ($this->preparedData !== null) {
            return $this->preparedData;
        }

        $prepared = json_decode($data, true);

        return $this->preparedData = json_last_error() === JSON_ERROR_NONE ? $prepared : $data;
    }
}
