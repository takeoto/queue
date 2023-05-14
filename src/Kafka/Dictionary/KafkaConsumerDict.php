<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka\Dictionary;

final class KafkaConsumerDict
{
    # Base config
    public const CONSUMING_TIMEOUT = 'consuming.timeout';

    # Core config
    public const CONFIG_GROUP_ID = 'group.id';
    public const CONFIG_AUTO_OFFSET_RESET = 'auto.offset.reset';
    public const CONFIG_SESSION_TIMEOUT_MS = 'session.timeout.ms';
    public const CONFIG_ENABLE_AUTO_COMMIT = 'enable.auto.commit';

    public const CONFIG = [
        self::CONFIG_GROUP_ID,
        KafkaDict::CONFIG_METADATA_BROKER_LIST,
        KafkaDict::CONFIG_SECURITY_PROTOCOL,
        KafkaDict::CONFIG_SSL_CERTIFICATE_LOCATION,
        KafkaDict::CONFIG_SSL_KEY_LOCATION,
        self::CONFIG_AUTO_OFFSET_RESET,
        self::CONFIG_ENABLE_AUTO_COMMIT,
        self::CONFIG_SESSION_TIMEOUT_MS,
    ];

    public const ALL = [
        ...self::CONFIG,
        self::CONSUMING_TIMEOUT,
    ];
}
