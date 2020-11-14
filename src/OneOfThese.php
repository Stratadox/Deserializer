<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\ImmutableCollection\ImmutableCollection;

/**
 * Uses one of several deserialization options to deserialize the input array
 * into objects.
 *
 * @author Stratadox
 */
final class OneOfThese extends ImmutableCollection implements Deserializer
{
    private function __construct(DeserializationOption ...$options)
    {
        parent::__construct(...$options);
    }

    /**
     * Makes a new collection of deserialization options.
     *
     * @param DeserializationOption ...$options The options to consider when
     *                                          deserializing an input array.
     * @return Deserializer                     The deserialization options.
     */
    public static function deserializers(DeserializationOption ...$options): Deserializer
    {
        return new OneOfThese(...$options);
    }


    /** @inheritdoc */
    public function current(): DeserializationOption
    {
        return parent::current();
    }

    /** @inheritdoc */
    public function from(array $input)
    {
        return $this->makeFrom($input, $this->optionFor($input));
    }

    /** @inheritdoc */
    public function typeFor(array $input): string
    {
        return $this->wouldProduceFor($input, $this->optionFor($input));
    }

    /**
     * @throws DeserializationFailure
     * @return mixed
     */
    private function makeFrom(array $input, DeserializationOption $deserialize)
    {
        return $deserialize->from($input);
    }

    /** @throws DeserializationFailure */
    private function wouldProduceFor(array $input, DeserializationOption $deserialize): string
    {
        return $deserialize->typeFor($input);
    }

    /** @throws DeserializationFailure */
    private function optionFor(array $input): DeserializationOption
    {
        foreach ($this as $option) {
            if ($option->isSatisfiedBy($input)) {
                return $option;
            }
        }
        throw UnacceptableInput::illegal($input);
    }
}
