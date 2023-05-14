<?php

namespace App\System\Queue\Contract;

interface QueueInterface
{
    /**
     * Make consumer instance.
     * @param ConfigInterface|null $config
     * @return ConsumerInterface
     */
    public function makeConsumer(?ConfigInterface $config = null): ConsumerInterface;

    /**
     * Make consumer instance.
     * @param ConfigInterface|null $config
     * @return ProducerInterface
     */
    public function makeProducer(?ConfigInterface $config = null): ProducerInterface;
}
