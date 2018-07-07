<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use RuntimeException;
use Throwable;

/**
 * Notifies the client that the object could not be deserialized.
 *
 * @author Stratadox
 * @license MIT
 */
final class FailedToDeserializeTheObject extends RuntimeException implements CannotDeserialize
{
    /**
     * Produces a deserialization exception to throw when a "foreign" exception
     * was encountered during the object deserialization process.
     *
     * Prepends the original exception message with additional information on
     * what happened when the problem occurred.
     *
     * @param Throwable $exception The original exception that was caught while
     *                             deserialization the object.
     * @return CannotDeserialize   The deserialization exception to throw in
     *                             place of the encountered exception.
     */
    public static function encountered(Throwable $exception): CannotDeserialize
    {
        return new FailedToDeserializeTheObject(
            'Failed to deserialize the object: ' . $exception->getMessage(),
            0,
            $exception
        );
    }
}
