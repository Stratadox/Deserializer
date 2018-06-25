<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition;

use Exception;
use Faker\Factory as RandomGenerator;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\Condition\ConsistOfItems;
use Stratadox\Deserializer\Test\Unit\Condition\Fixture\Greater;

/**
 * @covers \Stratadox\Deserializer\Condition\ConsistOfItems
 */
class ConsistOfItems_checks_a_condition_on_all_items extends TestCase
{
    /**
     * @test
     * @dataProvider listOfPositiveNumbers
     */
    function accepting_a_list_of_numbers_greater_than_zero(array $numbers)
    {
        $this->assertTrue(
            ConsistOfItems::that(Greater::than(0))->isSatisfiedBy($numbers)
        );
    }

    /**
     * @test
     * @dataProvider listOfPositiveNumbersWithOneNegativeNumber
     */
    function denying_a_list_of_numbers_when_not_all_are_positive(array $numbers)
    {
        $this->assertFalse(
            ConsistOfItems::that(Greater::than(0))->isSatisfiedBy($numbers)
        );
    }

    /**
     * @test
     * @dataProvider nonIterableInput
     */
    function denying_all_non_iterable_input($input)
    {
        $this->assertFalse(
            ConsistOfItems::that(Greater::than(0))->isSatisfiedBy($input)
        );
    }

    public function nonIterableInput(): array
    {
        $random = RandomGenerator::create();
        return [
            'A word' => [$random->word],
            'A sentence' => [$random->sentence],
            'An exception' => [new Exception],
        ];
    }

    public function listOfPositiveNumbers(): array
    {
        $random = RandomGenerator::create();
        $n = $random->numberBetween(0, 10);
        $list = [];
        for ($i = $n; $i > 0; $i--) {
            $list[] = $random->numberBetween(1, 10);
        }
        return [
            "List of $n positive numbers" => [$list],
        ];
    }

    public function listOfPositiveNumbersWithOneNegativeNumber(): array
    {
        $random = RandomGenerator::create();
        $n = $random->numberBetween(5, 10);
        $negative = $random->numberBetween(1, $n);
        $list = [];
        for ($i = $n; $i > 0; --$i) {
            if ($i === $negative) {
                $list[] = $random->numberBetween(-10, -1);
            } else {
                $list[] = $random->numberBetween(1, 10);
            }
        }
        $negatives = [];
        for ($i = $random->numberBetween(1, 10); $i > 0; $i--) {
            $negatives[] = $random->numberBetween(-10, -1);
        }
        return [
            implode(',', $list) => [$list],
            implode(',', $negatives) => [$negatives],
        ];
    }
}
