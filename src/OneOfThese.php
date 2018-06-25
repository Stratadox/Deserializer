<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\ImmutableCollection\ImmutableCollection;

final class OneOfThese extends ImmutableCollection implements Deserializes
{
    private function __construct(Option ...$options)
    {
        parent::__construct(...$options);
    }

    public static function deserializers(Option ...$options): Deserializes
    {
        return new OneOfThese(...$options);
    }

    /** @inheritdoc */
    public function current(): Option
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

    /** @throws CannotDeserialize */
    private function makeFrom(array $input, Option $deserialize)
    {
        return $deserialize->from($input);
    }

    /** @throws CannotDeserialize */
    private function wouldProduceFor(array $input, Option $deserialize): string
    {
        return $deserialize->typeFor($input);
    }

    /** @throws CannotDeserialize */
    private function optionFor(array $input): Option
    {
        foreach ($this as $option) {
            if ($option->isSatisfiedBy($input)) {
                return $option;
            }
        }
        throw UnacceptableInput::illegal($input);
    }
}
