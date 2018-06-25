<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use InvalidArgumentException;
use function json_encode;
use function sprintf as withMessage;

/**
 * UnacceptableInput.
 *
 * @author Stratadox
 */
final class UnacceptableInput extends InvalidArgumentException implements CannotDeserialize
{
    public static function illegal(array $input): CannotDeserialize
    {
        return new UnacceptableInput(withMessage(
            'None of the deserializers are configured to accept `%s`.',
            json_encode($input)
        ));
    }
}
