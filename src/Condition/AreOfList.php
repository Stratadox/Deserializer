<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function array_values;
use function is_array;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

/**
 * Condition that accepts list typed input.
 *
 * @author Stratadox
 * @license MIT
 */
final class AreOfList implements Specifies
{
    use Specifying;

    /**
     * Produces a condition that accepts list type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function type(): Specifies
    {
        return new self;
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        return is_array($input)
            && $input === array_values($input);
    }
}
