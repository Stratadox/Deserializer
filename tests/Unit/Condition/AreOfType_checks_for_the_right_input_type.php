<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition;

use function array_combine as combine;
use function array_map as map;
use function array_merge as merge;
use Faker\Factory as RandomGenerator;
use const PHP_INT_MAX as ABSOLUTE_MAXIMUM;
use const PHP_INT_MIN as ABSOLUTE_MINIMUM;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\Condition\AreOfType;

/**
 * @covers \Stratadox\Deserializer\Condition\AreOfType
 */
class AreOfType_checks_for_the_right_input_type extends TestCase
{
    /**
     * @test
     * @dataProvider booleanTypes
     */
    function accepting_boolean_types($input)
    {
        $this->assertTrue(
            AreOfType::boolean()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonBooleanTypes
     */
    function denying_non_boolean_types($input)
    {
        $this->assertFalse(
            AreOfType::boolean()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider integerTypes
     */
    function accepting_integer_types($input)
    {
        $this->assertTrue(
            AreOfType::integer()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonIntegerTypes
     */
    function denying_non_integer_types($input)
    {
        $this->assertFalse(
            AreOfType::integer()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider stringTypes
     */
    function accepting_string_types($input)
    {
        $this->assertTrue(
            AreOfType::string()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonStringTypes
     */
    function denying_non_string_types($input)
    {
        $this->assertFalse(
            AreOfType::string()->isSatisfiedBy($input)
        );
    }

    public function booleanTypes(): array
    {
        return [
            'Boolean `true`'  => [true],
            'Boolean `false`' => [false],
        ];
    }

    public function nonBooleanTypes(): array
    {
        return merge(
            $this->integerTypes(),
            $this->stringTypes()
        );
    }

    public function integerTypes(): array
    {
        $random = RandomGenerator::create();
        $smallNumber = $random->numberBetween(ABSOLUTE_MINIMUM, ABSOLUTE_MINIMUM + 100);
        $negativeNumber = $random->numberBetween(-100, -1);
        $number = $random->numberBetween(-9, 9);
        $positiveNumber = $random->numberBetween(1, 100);
        $bigNumber = $random->numberBetween(ABSOLUTE_MAXIMUM - 100, ABSOLUTE_MAXIMUM);
        return [
            "Very small integer `$smallNumber`" => [$smallNumber],
            "Negative integer `$negativeNumber`" => [$negativeNumber],
            "Single digit integer `$number`" => [$number],
            "Positive integer `$positiveNumber`" => [$positiveNumber],
            "Very big integer `$bigNumber`" => [$bigNumber],
        ];
    }

    public function nonIntegerTypes(): array
    {
        return merge(
            $this->booleanTypes(),
            $this->stringTypes()
        );
    }

    public function stringTypes(): array
    {
        $random = RandomGenerator::create();
        $strings = merge(
            $random->words(3),
            $random->sentences(3)
        );
        return combine(
            map(function(string $s): string {
                return "String '$s'";
            }, $strings),
            map(function(string $s): array {
                return [$s];
            }, $strings)
        );
    }

    public function nonStringTypes(): array
    {
        return merge(
            $this->booleanTypes(),
            $this->integerTypes()
        );
    }
}
