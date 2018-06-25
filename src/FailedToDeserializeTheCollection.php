<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use RuntimeException;
use Throwable;

/**
 * Notifies the client that the collection could not be deserialized.
 *
 * @author Stratadox
 */
final class FailedToDeserializeTheCollection extends RuntimeException implements CannotDeserialize
{
    public static function encountered(Throwable $exception): CannotDeserialize
    {
        return new FailedToDeserializeTheCollection(
            'Failed to deserialize the collection: ' . $exception->getMessage(),
            0,
            $exception
        );
    }
}
