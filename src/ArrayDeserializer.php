<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

/**
 * Deserializes the input array into collection arrays, serving as null object
 * for collection deserializers.
 *
 * @author Stratadox
 */
final class ArrayDeserializer implements Deserializer
{
    /**
     * Makes a new deserializer for arrays.
     *
     * @return Deserializer The array deserializer.
     */
    public static function make(): Deserializer
    {
        return new ArrayDeserializer;
    }

    /** @inheritdoc */
    public function from(array $input): iterable
    {
        return $input;
    }

    /** @inheritdoc */
    public function typeFor(array $input): string
    {
        return 'array';
    }
}
