<?php

namespace App\System\Queue\Contract;

interface ConsumerInterface
{
    /**
     * Start process consuming message from queue.
     * @param string $queue
     * @param callable|null $onError
     * @return MessageInterface|null
     */
    public function consume(string $queue, callable $onError = null): ?MessageInterface;

    /**
     * Mark the current as read.
     * @return mixed
     */
    public function commit();
}
