<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function get_class as classOfThe;
use InvalidArgumentException;
use function sprintf as withMessage;

/**
 * NonNumericInputKey.
 *
 * @author Stratadox
 */
final class IllegalInputKey extends InvalidArgumentException implements CannotDeserialize
{
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
