<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Feature\Fixture;

final class Foo
{
    private $name;
    private $type = 'foo';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return "$this->type $this->name";
    }
}
