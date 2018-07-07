<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function get_class as classOfThe;
use InvalidArgumentException;
use function sprintf as withMessage;

/**
 * Notifies the client that the input could not be accepted due to an illegal key.
 *
 * @author Stratadox
 * @license MIT
 */
final class IllegalInputKey extends InvalidArgumentException implements CannotDeserialize
{
    /**
     * Produces a deserialization exception to throw when a collection input key
     * is not considered valid.
     *
     * @param object $collection The collection that was assigned the illegal
     *                           input key.
     * @param string $key        The input key that was considered invalid.
     * @return CannotDeserialize The deserialization exception to throw.
     */
    public static function illegal(
        object $collection,
        string $key
    ): CannotDeserialize {
        return new IllegalInputKey(withMessage(
            'Invalid collection deserialization input: Unexpected key `%s` for the `%s` class.',
            $key,
            classOfThe($collection)
        ));
    }
}
