<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Feature\Fixture;

use Stratadox\ImmutableCollection\ImmutableCollection;

final class ListOfStrings extends ImmutableCollection
{
    public function __construct(string ...$strings)
    {
        parent::__construct(...$strings);
    }

    public function current(): string
    {
        return parent::current();
    }
}
