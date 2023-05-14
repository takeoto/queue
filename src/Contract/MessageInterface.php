<?php

namespace App\System\Queue\Contract;

interface MessageInterface
{
    /**
     * Get message uniq key.
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * Get message data.
     * @return mixed
     */
    public function getData(bool $original = false);
}
