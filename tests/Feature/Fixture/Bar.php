<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Feature\Fixture;

final class Bar
{
    private $type = 'bar';
    private $name;
    private $drinks;

    public function __construct(string $name, string ...$drinks)
    {
        $this->name = $name;
        $this->drinks = $drinks;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function drinks(): array
    {
        return $this->drinks;
    }

    public function __toString(): string
    {
        return "$this->type $this->name";
    }
}
