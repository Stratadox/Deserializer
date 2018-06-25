<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Feature\Fixture;

use Stratadox\ImmutableCollection\ImmutableCollection;

final class ListOfIntegers extends ImmutableCollection
{
    public function __construct(int ...$strings)
    {
        parent::__construct(...$strings);
    }

    public function current(): int
    {
        return parent::current();
    }
}
