<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka\Dictionary;

final class KafkaProducerDict
{
    public const PRODUCING_TIMEOUT = 'producing.timeout';

    public const CONFIG = [
    ];

    public const ALL = [
        ...self::CONFIG,
        self::PRODUCING_TIMEOUT,
    ];
}
