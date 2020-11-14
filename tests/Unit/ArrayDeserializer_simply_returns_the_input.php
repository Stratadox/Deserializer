<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use Faker\Factory as RandomGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Deserializer\ArrayDeserializer;

class ArrayDeserializer_simply_returns_the_input extends TestCase
{
    /**
     * @test
     * @dataProvider inputArrays
     */
    function retrieving_the_exact_input(array $input)
    {
        self::assertSame(
            $input,
            ArrayDeserializer::make()->from($input)
        );
    }

    /**
     * @test
     * @dataProvider inputArrays
     */
    function making_array_type_output(array $input)
    {
        self::assertSame(
            'array',
            ArrayDeserializer::make()->typeFor($input)
        );
    }

    public function inputArrays(): array
    {
        $random = RandomGenerator::create();
        $map = [];
        $mapAmount = $random->numberBetween(1, 25);
        for ($i = $mapAmount; $i > 0; $i--) {
            $map[$random->word] = $random->sentence;
        }
        $thisMany = $random->numberBetween(1, 25);
        return [
            "List of $thisMany words" => [
                $random->words($thisMany)
            ],
            "Map of $mapAmount times [word => sentence]" => [
                $map
            ],
            'List of objects' => [
                [new stdClass, $this, new InvalidArgumentException]
            ],
            'Map of 3 times [tag => object]' => [
                [
                    'stdClass'  => new stdClass,
                    'this'      => $this,
                    'exception' => new InvalidArgumentException,
                ]
            ],
        ];
    }
}
