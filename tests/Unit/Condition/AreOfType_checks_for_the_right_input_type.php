<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition;

use function array_combine;
use function array_map;
use function array_merge;
use Faker\Factory as RandomGenerator;
use const PHP_INT_MAX;
use const PHP_INT_MIN;
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
        self::assertTrue(
            AreOfType::boolean()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonBooleanTypes
     */
    function denying_non_boolean_types($input)
    {
        self::assertFalse(
            AreOfType::boolean()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider integerTypes
     */
    function accepting_integer_types($input)
    {
        self::assertTrue(
            AreOfType::integer()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonIntegerTypes
     */
    function denying_non_integer_types($input)
    {
        self::assertFalse(
            AreOfType::integer()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider stringTypes
     */
    function accepting_string_types($input)
    {
        self::assertTrue(
            AreOfType::string()->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider nonStringTypes
     */
    function denying_non_string_types($input)
    {
        self::assertFalse(
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
        return array_merge(
            $this->integerTypes(),
            $this->stringTypes()
        );
    }

    public function integerTypes(): array
    {
        $random = RandomGenerator::create();
        $smallNumber = $random->numberBetween(PHP_INT_MIN, PHP_INT_MIN + 100);
        $negativeNumber = $random->numberBetween(-100, -1);
        $number = $random->numberBetween(-9, 9);
        $positiveNumber = $random->numberBetween(1, 100);
        $bigNumber = $random->numberBetween(PHP_INT_MAX - 100, PHP_INT_MAX);
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
        return array_merge(
            $this->booleanTypes(),
            $this->stringTypes()
        );
    }

    public function stringTypes(): array
    {
        $random = RandomGenerator::create();
        $strings = array_merge(
            $random->words(3),
            $random->sentences(3)
        );
        return array_combine(
            array_map(static function(string $s): string {
                return "String '$s'";
            }, $strings),
            array_map(static function(string $s): array {
                return [$s];
            }, $strings)
        );
    }

    public function nonStringTypes(): array
    {
        return array_merge(
            $this->booleanTypes(),
            $this->integerTypes()
        );
    }
}
