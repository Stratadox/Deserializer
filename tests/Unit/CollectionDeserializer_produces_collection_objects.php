<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use function count;
use Faker\Factory as RandomGenerator;
use Faker\Generator;
use function implode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function sprintf;
use Stratadox\Deserializer\CannotDeserialize;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\Test\Unit\Fixture\CollectionOfIntegers;
use Stratadox\Deserializer\Test\Unit\Fixture\InconstructibleCollection;
use Stratadox\Deserializer\Test\Unit\Fixture\Popo;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Instantiator\ProvidesInstances;

/**
 * @covers \Stratadox\Deserializer\CollectionDeserializer
 * @covers \Stratadox\Deserializer\FailedToDeserializeTheCollection
 * @covers \Stratadox\Deserializer\IllegalInputKey
 * @covers \Stratadox\Deserializer\NonIterableCollection
 */
class CollectionDeserializer_produces_collection_objects extends TestCase
{
    private const TESTS = 10;

    /**
     * @test
     * @dataProvider integers
     */
    function producing_collections(
        array $integers,
        CollectionOfIntegers $expectedCollection,
        CollectionOfIntegers $unexpectedCollection
    ) {
        /** @var CollectionOfIntegers $actualCollection */
        $actualCollection = CollectionDeserializer::forThe(
            CollectionOfIntegers::class
        )->from($integers);

        $this->assertTrue($expectedCollection->equals($actualCollection));
        $this->assertFalse($unexpectedCollection->equals($actualCollection));
    }

    /**
     * @test
     * @dataProvider classNames
     */
    function retrieving_the_class_name(string $class)
    {
        $this->assertSame(
            $class,
            CollectionDeserializer::forThe($class)->typeFor([])
        );
    }

    /** @test */
    function using_a_custom_instantiator_and_hydrator()
    {
        $collection = CollectionOfIntegers::with();

        /** @var MockObject|ProvidesInstances $instantiator */
        $instantiator = $this->createMock(ProvidesInstances::class);
        $instantiator
            ->expects($this->once())
            ->method('instance')
            ->willReturn($collection);

        /** @var MockObject|Hydrates $hydrator */
        $hydrator = $this->createMock(Hydrates::class);
        $hydrator
            ->expects($this->once())
            ->method('writeTo')
            ->with($collection, [1, 2, 3]);

        $deserializer = CollectionDeserializer::using($instantiator, $hydrator);
        $deserializer->from([1, 2, 3]);
    }

    /** @test */
    function throwing_the_right_exceptions()
    {
        $inconstructibleCollection = InconstructibleCollection::class;
        $deserializer = CollectionDeserializer::forThe($inconstructibleCollection);

        $this->expectException(CannotDeserialize::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to deserialize the collection: ' .
            "Could not hydrate the `$inconstructibleCollection`: " .
            'Cannot construct (foo, bar)'
        );

        $deserializer->from(['foo', 'bar']);
    }

    /** @test */
    function only_accepting_lists_as_input()
    {
        $collectionOfIntegers = CollectionOfIntegers::class;
        $deserializer = CollectionDeserializer::forThe($collectionOfIntegers);

        $this->expectException(CannotDeserialize::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Invalid collection deserialization input: ' .
            "Unexpected key `foo` for the `$collectionOfIntegers` class."
        );

        $deserializer->from(['foo' => 'bar']);
    }

    /** @test */
    function only_accepting_iterable_output()
    {
        $popo = Popo::class;
        $deserializer = CollectionDeserializer::forThe($popo);

        $this->expectException(CannotDeserialize::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Invalid collection deserialization output: ' .
            "The `$popo` class is not a collection."
        );

        $deserializer->from(['foo', 'bar']);
    }

    public function classNames(): array
    {
        return [
            'CollectionOfIntegers'      => [CollectionOfIntegers::class],
            'InconstructibleCollection' => [InconstructibleCollection::class],
        ];
    }

    public function integers(): array
    {
        $random = RandomGenerator::create();
        $sets = [];
        for ($i = self::TESTS; $i > 0; --$i) {
            $randomIntegers = $this->generateIntegers(
                $random->numberBetween(0, 15),
                $random
            );
            $otherIntegers = $this->generateIntegers(
                $random->numberBetween(5, 15),
                $random
            );
            $sets[$this->tagFor(...$randomIntegers)] = [
                $randomIntegers,
                CollectionOfIntegers::with(...$randomIntegers),
                CollectionOfIntegers::with(...$otherIntegers)
            ];
        }
        return $sets;
    }

    private function tagFor(int ...$integers): string
    {
        return sprintf(
            '%d number%s: %s',
            count($integers),
            count($integers) === 1 ? '' : 's',
            implode(', ', $integers)
        );
    }

    /** @return int[] */
    private function generateIntegers(
        int $amount,
        Generator $random
    ): array {
        $integers = [];
        for ($i = $amount; $i > 0; --$i) {
            $integers[] = $random->numberBetween(1, 99);
        }
        return $integers;
    }
}
