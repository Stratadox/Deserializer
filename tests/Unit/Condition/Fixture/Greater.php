<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition\Fixture;

use function is_numeric;
use Stratadox\Specification\Contract\Satisfiable;

final class Greater implements Satisfiable
{
    private $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public static function than(int $minimum): Satisfiable
    {
        return new self($minimum);
    }

    public function isSatisfiedBy($input): bool
    {
        return is_numeric($input)
            && $input > $this->minimum;
    }
}
