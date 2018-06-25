<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

/**
 * Array deserializer.
 *
 * @author Stratadox
 */
final class ArrayDeserializer implements DeserializesCollections
{
    public static function make(): DeserializesCollections
    {
        return new ArrayDeserializer;
    }

    public function from(array $input): iterable
    {
        return $input;
    }

    public function typeFor(array $input): string
    {
        return 'array';
    }
}
