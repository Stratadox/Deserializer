<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;


use Stratadox\Specification\Contract\Satisfiable;

/**
 * Condition that accepts all input.
 *
 * Can be used like the default clause in a switch statement, but in the context
 * of a discriminator map.
 *
 * @author Stratadox
 */
final class DidNotGetAccepted implements Satisfiable
{
    public static function yet(): Satisfiable
    {
        return new self();
    }

    public function isSatisfiedBy($object): bool
    {
        return true;
    }
}
