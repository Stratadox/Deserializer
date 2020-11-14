<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function gettype;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

/**
 * Condition that accepts a specific input type.
 *
 * Can be used to have the deserializer act differently upon receiving a
 * particular type of data. Generally combined with @see ConsistOfItems in order
 * to check for the data types of all items in a list.
 *
 * @author Stratadox
 */
final class AreOfType implements Specifies
{
    use Specifying;

    /** @var string */
    private $expectation;

    private function __construct(string $expectation)
    {
        $this->expectation = $expectation;
    }

    /**
     * Produces a condition that accepts boolean type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function boolean(): Specifies
    {
        return new self('boolean');
    }

    /**
     * Produces a condition that accepts integer type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function integer(): Specifies
    {
        return new self('integer');
    }

    /**
     * Produces a condition that accepts string type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function string(): Specifies
    {
        return new self('string');
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        return gettype($input) === $this->expectation;
    }
}
