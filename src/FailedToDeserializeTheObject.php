<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use RuntimeException;
use Throwable;

/**
 * Notifies the client that the object could not be deserialized.
 *
 * @author Stratadox
 */
final class FailedToDeserializeTheObject extends RuntimeException implements CannotDeserialize
{
    public static function encountered(Throwable $exception): CannotDeserialize
    {
        return new FailedToDeserializeTheObject(
            'Failed to deserialize the object: ' . $exception->getMessage(),
            0,
            $exception
        );
    }
}
