<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

/**
 * Deserializes the input array into collection arrays, serving as null object
 * for collection deserializers.
 *
 * @author Stratadox
 * @license MIT
 */
final class ArrayDeserializer implements DeserializesCollections
{
    /**
     * Makes a new deserializer for arrays.
     *
     * @return DeserializesCollections The array deserializer.
     */
    public static function make(): DeserializesCollections
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
