<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Fixture;

use function count;
use Stratadox\ImmutableCollection\ImmutableCollection;

final class CollectionOfIntegers extends ImmutableCollection
{
    private function __construct(int ...$integers)
    {
        parent::__construct(...$integers);
    }

    public static function with(int ...$integers): CollectionOfIntegers
    {
        return new CollectionOfIntegers(...$integers);
    }

    public function current(): int
    {
        return parent::current();
    }

    public function equals(CollectionOfIntegers $other): bool
    {
        if (count($this) !== count($other)) {
            return false;
        }
        foreach ($this as $i => $int) {
            if ($other[$i] !== $int) {
                return false;
            }
        }
        return true;
    }
}
