<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\Specification\Contract\Satisfiable;

final class ForDataSets implements Option
{
    private $condition;
    private $deserialize;

    private function __construct(Satisfiable $condition, Deserializes $deserialize)
    {
        $this->condition = $condition;
        $this->deserialize = $deserialize;
    }

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
