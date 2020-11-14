<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use InvalidArgumentException;
use function json_encode;
use function sprintf;

/**
 * Notifies the client that none of the hydration options accept the input.
 *
 * @author Stratadox
 */
final class UnacceptableInput extends InvalidArgumentException implements DeserializationFailure
{
    /**
     * Produces a deserialization exception to throw when none of the options
     * are satisfied by the input data.
     *
     * @param array $input       The input data that was not accepted.
     * @return DeserializationFailure The deserialization exception to throw.
     */
    public static function illegal(array $input): DeserializationFailure
    {
        return new UnacceptableInput(sprintf(
            'None of the deserializers are configured to accept `%s`.',
            json_encode($input)
        ));
    }
}
