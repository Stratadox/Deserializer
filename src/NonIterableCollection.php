<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function get_class as theClassOfThe;
use RuntimeException;
use function sprintf as withMessage;

/**
 * NonIterableCollection.
 *
 * @author Stratadox
 */
final class NonIterableCollection extends RuntimeException implements CannotDeserialize
{
    public static function invalid(object $collection): CannotDeserialize
    {
        return new NonIterableCollection(withMessage(
            'Invalid collection deserialization output: The `%s` class is not a collection.',
            theClassOfThe($collection)
        ));
    }
}
