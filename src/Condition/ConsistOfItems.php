<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function is_iterable;
use Stratadox\Specification\Contract\Satisfiable;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

final class ConsistOfItems implements Specifies
{
    use Specifying;

    private $condition;

    public function __construct(Satisfiable $condition)
    {
        $this->condition = $condition;
    }

    public static function that(Satisfiable $passingTheCondition): Specifies
    {
        return new ConsistOfItems($passingTheCondition);
    }

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
