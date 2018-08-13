<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Feature;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\Condition\AreOfType;
use Stratadox\Deserializer\Condition\ConsistOfItems;
use Stratadox\Deserializer\Condition\DidNotGetAccepted;
use Stratadox\Deserializer\Deserializes;
use Stratadox\Deserializer\Test\Feature\Fixture\ListOfIntegers;
use Stratadox\Deserializer\Condition\HaveTheDiscriminatorValue;
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\Condition\AreOfList;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Deserializer\OneOfThese;
use Stratadox\Deserializer\Test\Feature\Fixture\Bar;
use Stratadox\Deserializer\Test\Feature\Fixture\Foo;
use Stratadox\Deserializer\Test\Feature\Fixture\ListOfStrings;

/**
 * @coversNothing
 */
class Deserializing_a_mixed_type extends TestCase
{
    /** @var Deserializes */
    private $make;

    protected function setUp(): void
    {
        $this->make = OneOfThese::deserializers(

            ForDataSets::that(
                HaveTheDiscriminatorValue::of('type', 'foo'),
                ObjectDeserializer::forThe(Foo::class)
            ),

            ForDataSets::that(
                HaveTheDiscriminatorValue::of('type', 'bar'),
                ObjectDeserializer::forThe(Bar::class)
            ),

            ForDataSets::that(
                AreOfList::type()->and(ConsistOfItems::that(AreOfType::integer())),
                CollectionDeserializer::forThe(ListOfIntegers::class)
            ),

            ForDataSets::that(
                AreOfList::type()->and(ConsistOfItems::that(AreOfType::string())),
                CollectionDeserializer::forThe(ListOfStrings::class)
            ),

            ForDataSets::that(
                DidNotGetAccepted::yet(),
                ArrayDeserializer::make()
            )

        );
    }

    /**
     * @test
     * @dataProvider input
     */
    function deserializing_the(array $input, $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->make->from($input)
        );
    }

    public function input(): array
    {
        return [
            'Foo' => [
                [
                    'type' => 'foo',
                    'name' => 'Foo!',
                ],
                new Foo('Foo!'),
            ],
            'Bar' => [
                [
                    'type' => 'bar',
                    'name' => 'The foo bar.',
                    'drinks' => ['Whiskey', 'Wine'],
                ],
                new Bar('The foo bar.', 'Whiskey', 'Wine'),
            ],
            'List of strings' => [
                [
                    'Whiskey',
                    'Wine',
                ],
                new ListOfStrings('Whiskey', 'Wine'),
            ],
            'List of integers' => [
                [
                    1,
                    2,
                    3,
                ],
                new ListOfIntegers(1, 2, 3),
            ],
            'List of mixed values' => [
                [
                    1,
                    '2',
                    3.0,
                ],
                [
                    1,
                    '2',
                    3.0,
                ],
            ]
        ];
    }
}
