<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function gettype;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

/**
 * Condition that accepts a specific input type.
 *
 * @author Stratadox
 * @license MIT
 */
final class AreOfType implements Specifies
{
    use Specifying;

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
        return new AreOfType('boolean');
    }

    /**
     * Produces a condition that accepts integer type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function integer(): Specifies
    {
        return new AreOfType('integer');
    }

    /**
     * Produces a condition that accepts string type input.
     *
     * @return Specifies The type enforcing condition.
     */
    public static function string(): Specifies
    {
        return new AreOfType('string');
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        return gettype($input) === $this->expectation;
    }
}
