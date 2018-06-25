<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Fixture;

use Stratadox\Specification\Contract\Satisfiable;

final class AreDenied implements Satisfiable
{
    public static function byDefault(): Satisfiable
    {
        return new self;
    }

    public function isSatisfiedBy($input): bool
    {
        return false;
    }
}
