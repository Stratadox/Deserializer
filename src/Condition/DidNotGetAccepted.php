<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;


use Stratadox\Specification\Contract\Satisfiable;

final class DidNotGetAccepted implements Satisfiable
{
    public static function yet(): Satisfiable
    {
        return new DidNotGetAccepted();
    }

    public function isSatisfiedBy($object): bool
    {
        return true;
    }
}
