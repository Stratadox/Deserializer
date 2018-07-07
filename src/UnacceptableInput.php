<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use InvalidArgumentException;
use function json_encode;
use function sprintf as withMessage;

/**
 * Notifies the client that none of the hydration options accept the input.
 *
 * @author Stratadox
 * @license MIT
 */
final class UnacceptableInput extends InvalidArgumentException implements CannotDeserialize
{
    /**
     * Produces a deserialization exception to throw when none of the options
     * are satisfied by the input data.
     *
     * @param array $input       The input data that was not accepted.
     * @return CannotDeserialize The deserialization exception to throw.
     */
    public static function illegal(array $input): CannotDeserialize
    {
        return new UnacceptableInput(withMessage(
            'None of the deserializers are configured to accept `%s`.',
            json_encode($input)
        ));
    }
}
