<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\Specification\Contract\Satisfiable;

/**
 * Represents a deserialization option, consisting of a condition and a
 * deserializer.
 *
 * The embedded deserializer is used for data sets that pass the condition.
 *
 * @author Stratadox
 * @license MIT
 */
final class ForDataSets implements Option
{
    private $condition;
    private $deserialize;

    private function __construct(Satisfiable $condition, Deserializes $deserialize)
    {
        $this->condition = $condition;
        $this->deserialize = $deserialize;
    }

    /**
     * Produce an option for data sets that pass the condition.
     *
     * @param Satisfiable  $condition   The condition that must be satisfied by
     *                                  the data set.
     * @param Deserializes $deserialize The deserializer that will deserialize
     *                                  satisfying data sets.
     * @return Option                   The option object to supply to the
     *                                  @see OneOfThese::deserializers() method.
     */
    public static function that(Satisfiable $condition, Deserializes $deserialize): Option
    {
        return new ForDataSets($condition, $deserialize);
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        return $this->condition->isSatisfiedBy($input);
    }

    /** @inheritdoc */
    public function from(array $input)
    {
        return $this->deserialize->from($input);
    }

    /** @inheritdoc */
    public function typeFor(array $input): string
    {
        return $this->deserialize->typeFor($input);
    }
}
