<?php

declare(strict_types=1);

namespace App\System\Queue\Kafka\Dictionary;

final class KafkaDict
{
    public const CONFIG_METADATA_BROKER_LIST = 'metadata.broker.list';
    public const CONFIG_SECURITY_PROTOCOL = 'security.protocol';
    public const CONFIG_SSL_CERTIFICATE_LOCATION = 'ssl.certificate.location';
    public const CONFIG_SSL_KEY_LOCATION = 'ssl.key.location';
}
