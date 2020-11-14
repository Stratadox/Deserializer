<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use InvalidArgumentException;
use function get_class;
use function sprintf;

/**
 * Notifies the client that the input could not be accepted due to an illegal key.
 *
 * @author Stratadox
 */
final class IllegalInputKey extends InvalidArgumentException implements DeserializationFailure
{
    /**
     * Produces a deserialization exception to throw when a collection input key
     * is not considered valid.
     *
     * @param object  $collection     The collection that was assigned the
     *                                illegal input key.
     * @param string  $key            The input key that was considered invalid.
     * @param mixed[] $input          The input data that was provided.
     * @return DeserializationFailure The deserialization exception to throw.
     */
    public static function illegal(
        object $collection,
        string $key,
        array $input
    ): DeserializationFailure {
        return new IllegalInputKey(sprintf(
            'Invalid collection deserialization input: Unexpected key `%s` for the `%s` class.',
            $key,
            get_class($collection)
        ));
    }
}
