<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use RuntimeException;
use function get_class;
use function sprintf;

/**
 * Notifies the client that the result of the collection deserialization process
 * resulted in output that is not iterable.
 *
 * @author Stratadox
 */
final class NonIterableCollection extends RuntimeException implements DeserializationFailure
{
    /**
     * Produces a deserialization exception to throw when a collection turns out
     * not to be iterable.
     *
     * @param object $collection      The object that turns out not to be iterable.
     * @return DeserializationFailure The deserialization exception to throw.
     */
    public static function invalid(object $collection): DeserializationFailure
    {
        return new NonIterableCollection(sprintf(
            'Invalid collection deserialization output: The `%s` class is not a collection.',
            get_class($collection)
        ));
    }
}
