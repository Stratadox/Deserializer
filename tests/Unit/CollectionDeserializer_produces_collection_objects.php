<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use ArrayObject;
use Faker\Factory as RandomGenerator;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\Test\Unit\Fixture\CollectionOfIntegers;
use Stratadox\Deserializer\Test\Unit\Fixture\InconstructibleCollection;
use Stratadox\Deserializer\Test\Unit\Fixture\Popo;
use Stratadox\Hydrator\Hydrator;
use Stratadox\Instantiator\Instantiator;
use function count;
use function implode;
use function sprintf;

class CollectionDeserializer_produces_collection_objects extends TestCase
{
    private const TESTS = 10;

    /**
     * @test
     * @dataProvider integers
     */
    function producing_immutable_collections(
        array $integers,
        CollectionOfIntegers $expectedCollection,
        CollectionOfIntegers $unexpectedCollection
    ) {
        /** @var CollectionOfIntegers $actualCollection */
        $actualCollection = CollectionDeserializer::forImmutable(
            CollectionOfIntegers::class
        )->from($integers);

        self::assertTrue($expectedCollection->equals($actualCollection));
        self::assertFalse($unexpectedCollection->equals($actualCollection));
    }

    /**
     * @test
     * @dataProvider strings
     */
    function producing_mutable_collections(
        array $strings,
        ArrayObject $expectedCollection,
        ArrayObject $unexpectedCollection
    ) {
        /** @var ArrayObject $actualCollection */
        $actualCollection = CollectionDeserializer::forMutable(
            ArrayObject::class
        )->from($strings);

        self::assertEquals($expectedCollection, $actualCollection);
        self::assertNotEquals($unexpectedCollection, $actualCollection);
    }

    /**
     * @test
     * @dataProvider classNames
     */
    function retrieving_the_class_name(string $class)
    {
        self::assertSame(
            $class,
            CollectionDeserializer::forImmutable($class)->typeFor([])
        );
    }

    /** @test */
    function using_a_custom_instantiator_and_hydrator()
    {
        $collection = CollectionOfIntegers::with();

        /** @var MockObject|Instantiator $instantiator */
        $instantiator = $this->createMock(Instantiator::class);
        $instantiator
            ->expects(self::once())
            ->method('instance')
            ->willReturn($collection);

        /** @var MockObject|Hydrator $hydrator */
        $hydrator = $this->createMock(Hydrator::class);
        $hydrator
            ->expects(self::once())
            ->method('writeTo')
            ->with($collection, [1, 2, 3]);

        $deserializer = CollectionDeserializer::using($instantiator, $hydrator);
        $deserializer->from([1, 2, 3]);
    }

    /** @test */
    function throwing_the_right_exceptions()
    {
        $inconstructibleCollection = InconstructibleCollection::class;
        $deserializer = CollectionDeserializer::forImmutable($inconstructibleCollection);

        $this->expectException(DeserializationFailure::class);
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
        $deserializer = CollectionDeserializer::forImmutable($collectionOfIntegers);

        $this->expectException(DeserializationFailure::class);
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
        $deserializer = CollectionDeserializer::forImmutable($popo);

        $this->expectException(DeserializationFailure::class);
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
                CollectionOfIntegers::with(...$otherIntegers),
            ];
        }
        return $sets;
    }

    public function strings(): array
    {
        $random = RandomGenerator::create();
        $sets = [];
        for ($i = self::TESTS; $i > 0; --$i) {
            $randomStrings = $this->generateStrings(
                $random->numberBetween(0, 5),
                $random
            );
            $otherStrings = $this->generateStrings(
                $random->numberBetween(3, 10),
                $random
            );
            $sets[$this->tagFor(...$randomStrings)] = [
                $randomStrings,
                new ArrayObject($randomStrings),
                new ArrayObject($otherStrings),
            ];
        }
        return $sets;
    }

    private function tagFor(...$items): string
    {
        return sprintf(
            '%d item%s: %s',
            count($items),
            count($items) === 1 ? '' : 's',
            implode(', ', $items)
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

    /** @return string[] */
    private function generateStrings(
        int $amount,
        Generator $random
    ): array {
        $strings = [];
        for ($i = $amount; $i > 0; --$i) {
            $strings[] = $random->text(8);
        }
        return $strings;
    }
}
