<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function is_iterable;
use Stratadox\Specification\Contract\Satisfiable;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

/**
 * Condition that accepts data where all items satisfy a condition.
 *
 * Used to check that all elements in the input array satisfy a particular
 * condition.
 *
 * @author Stratadox
 */
final class ConsistOfItems implements Specifies
{
    use Specifying;

    /** @var Satisfiable */
    private $condition;

    private function __construct(Satisfiable $condition)
    {
        $this->condition = $condition;
    }

    /**
     * Produces a condition that checks if all items satisfy a condition.
     *
     * @param Satisfiable $passingTheCondition The condition that must pass on
     *                                         all items.
     * @return Specifies                       The condition to apply on the
     *                                         collection.
     */
    public static function that(Satisfiable $passingTheCondition): Specifies
    {
        return new self($passingTheCondition);
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        if (!is_iterable($input)) {
            return false;
        }
        foreach ($input as $item) {
            if (!$this->condition->isSatisfiedBy($item)) {
                return false;
            }
        }
        return true;
    }
}
