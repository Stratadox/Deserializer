<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function get_class as theClassOfThe;
use RuntimeException;
use function sprintf as withMessage;

/**
 * Notifies the client that the result of the collection deserialization process
 * resulted in output that is not iterable.
 *
 * @author Stratadox
 * @license MIT
 */
final class NonIterableCollection extends RuntimeException implements CannotDeserialize
{
    /**
     * Produces a deserialization exception to throw when a collection turns out
     * not to be iterable.
     *
     * @param object $collection The object that turns out not to be iterable.
     * @return CannotDeserialize The deserialization exception to throw.
     */
    public static function invalid(object $collection): CannotDeserialize
    {
        return new NonIterableCollection(withMessage(
            'Invalid collection deserialization output: The `%s` class is not a collection.',
            theClassOfThe($collection)
        ));
    }
}
