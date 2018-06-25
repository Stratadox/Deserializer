<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition;

use function array_combine;
use function array_map;
use Faker\Factory as RandomGenerator;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use function range;
use Stratadox\Deserializer\Condition\AreOfList;

/**
 * @covers \Stratadox\Deserializer\Condition\AreOfList
 */
class AreOfList_type_checks_that_the_keys_are_sequential_numbers extends TestCase
{
    /**
     * @test
     * @dataProvider acceptedData
     */
    function check_that_the_right_value_gets_accepted(array $input)
    {
        $this->assertTrue(AreOfList::type()->isSatisfiedBy($input));
    }

    /**
     * @test
     * @dataProvider deniedData
     */
    function check_that_the_wrong_value_gets_denied(array $input)
    {
        $this->assertFalse(AreOfList::type()->isSatisfiedBy($input));
    }

    public function acceptedData(): array
    {
        $random = RandomGenerator::create();
        $words = $random->numberBetween(0, 25);
        $numbers = $random->numberBetween(0, 25);
        $dates = $random->numberBetween(0, 25);
        return [
            "List of $words words"     => [
                $random->words($words)
            ],
            "List of $numbers numbers" => [
                array_map([$random, 'numberBetween'], range(1, $numbers))
            ],
            "List of $dates DateTimes" => [
                array_map([$random, 'dateTime'], range(1, $dates))
            ],
        ];
    }

    public function deniedData(): array
    {
        $random = RandomGenerator::create();
        $words = $random->numberBetween(1, 25);
        $count = $random->numberBetween(5, 25);
        $numbers = range(0, $count - 1);
        $originalNumbers = $numbers;
        while ($numbers === $originalNumbers) {
            shuffle($numbers);
        }
        return [
            "Map of $words word/word combinations" => [
                array_combine($random->words($words), $random->words($words))
            ],
            "Unordered list of $count numbers" => [
                array_combine($numbers, $originalNumbers)
            ]
        ];
    }
}
