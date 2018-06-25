<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function array_values;
use function is_array;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

final class AreOfList implements Specifies
{
    use Specifying;

    public static function type(): Specifies
    {
        return new self;
    }

    public function isSatisfiedBy($input): bool
    {
        return is_array($input)
            && $input === array_values($input);
    }
}
