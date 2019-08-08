<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use Faker\Factory as RandomGenerator;
use function json_encode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\CannotDeserialize;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Deserializer\Test\Unit\Fixture\ChildWithoutPropertyAccess;
use Stratadox\Deserializer\Test\Unit\Fixture\NoMagic;
use Stratadox\Deserializer\Test\Unit\Fixture\Popo;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Instantiator\ProvidesInstances;

/**
 * @covers \Stratadox\Deserializer\ObjectDeserializer
 * @covers \Stratadox\Deserializer\FailedToDeserializeTheObject
 */
class ObjectDeserializer_produces_objects extends TestCase
{
    private const TESTS = 10;

    /**
     * @test
     * @dataProvider properties
     */
    function producing_simple_objects(array $properties)
    {
        $deserializer = ObjectDeserializer::forThe(Popo::class);

        $object = $deserializer->from($properties);

        foreach ($properties as $name => $expectedValue) {
            $this->assertSame($expectedValue, $object->$name);
        }
    }

    /** @test */
    function producing_inheriting_objects()
    {
        $deserializer = ObjectDeserializer::forThe(ChildWithoutPropertyAccess::class);

        /** @var ChildWithoutPropertyAccess $object */
        $object = $deserializer->from(['property' => 'The expected value.']);

        $this->assertSame('The expected value.', $object->property());
    }

    /**
     * @test
     * @dataProvider classes
     */
    function retrieving_the_class_name(string $class)
    {
        $this->assertSame(
            $class,
            ObjectDeserializer::forThe($class)->typeFor([])
        );
    }

    /** @test */
    function using_a_custom_instantiator_and_hydrator()
    {
        $object = new Popo;

        /** @var MockObject|ProvidesInstances $instantiator */
        $instantiator = $this->createMock(ProvidesInstances::class);
        $instantiator
            ->expects($this->once())
            ->method('instance')
            ->willReturn($object);

        /** @var MockObject|Hydrates $hydrator */
        $hydrator = $this->createMock(Hydrates::class);
        $hydrator
            ->expects($this->once())
            ->method('writeTo')
            ->with($object, ['foo' => 'bar']);

        $deserializer = ObjectDeserializer::using($instantiator, $hydrator);
        $deserializer->from(['foo' => 'bar']);
    }

    /** @test */
    function throwing_the_right_exceptions()
    {
        $noMagic = NoMagic::class;
        $deserializer = ObjectDeserializer::forThe($noMagic);

        $this->expectException(CannotDeserialize::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to deserialize the object: ' .
            "Could not hydrate the `$noMagic`: " .
            'Thou shalt not write to foo.'
        );

        $deserializer->from(['foo' => 'bar']);
    }

    public function classes(): array
    {
        return [
            'Popo'    => [Popo::class],
            'NoMagic' => [NoMagic::class],
            'Child'   => [ChildWithoutPropertyAccess::class],
        ];
    }

    public function properties(): array
    {
        $random = RandomGenerator::create();
        $sets = [];
        for ($i = self::TESTS; $i > 0; --$i) {
            $properties = [];
            for ($j = $random->numberBetween(1, 5); $j > 0; --$j) {
                $properties[$random->word] = $random->randomElement([
                    $random->numberBetween(-999, 999),
                    $random->words,
                    $random->uuid,
                    $random->sentence,
                    $random->dateTime
                ]);
            }
            $sets[json_encode($properties)] = [$properties];
        }
        return $sets;
    }
}
