<?php

namespace App\System\Queue\Contract;

interface ProducerInterface
{
    /**
     * Produce message in to queue.
     * @param string $queue
     * @param mixed $data
     * @param string|null $key
     */
    public function produce(string $queue, $data, string $key = null): void;

    /**
     * Push produced messages in to queue.
     */
    public function commit(): void;
}
