<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use function array_combine as combine;
use function assert;
use Faker\Factory as RandomGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\Deserializes;
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\Test\Unit\Fixture\AreDenied;
use Stratadox\Deserializer\Test\Unit\Fixture\Popo;
use Stratadox\Deserializer\Test\Unit\Fixture\AreAccepted;

/**
 * @covers \Stratadox\Deserializer\ForDataSets
 */
class ForDataSets_that_pass_this_deserializer_is_used extends TestCase
{
    /** @var Deserializes */
    private $deserializer;

    protected function setUp(): void
    {
        $this->deserializer = $this->deserializer();
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function checking_that_the_condition_passes(array $input)
    {
        $this->assertTrue(
            ForDataSets::that(AreAccepted::byDefault(), $this->deserializer)->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function checking_that_the_condition_fails(array $input)
    {
        $this->assertFalse(
            ForDataSets::that(AreDenied::byDefault(), $this->deserializer)->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function deferring_deserialization_to_the_deserializer_in_question(array $input)
    {
        /** @var Deserializes|MockObject $deserialize */
        $deserialize = $this->createMock(Deserializes::class);
        $deserialize
            ->expects($this->once())
            ->method('from')
            ->with($input)
            ->willReturn(new Popo);

        ForDataSets::that(AreAccepted::byDefault(), $deserialize)->from($input);
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function checking_the_type(array $input)
    {
        /** @var Deserializes|MockObject $deserialize */
        $deserialize = $this->createMock(Deserializes::class);
        $deserialize
            ->expects($this->once())
            ->method('typeFor')
            ->with($input)
            ->willReturn(Popo::class);

        ForDataSets::that(AreAccepted::byDefault(), $deserialize)->typeFor($input);
    }

    public function keyValueArray(): array
    {
        $random = RandomGenerator::create();
        $n = $random->numberBetween(2, 10);
        return [
            'Random word => sentence'  => [[$random->word => $random->sentence]],
            'Random sentence => int'   => [[$random->sentence => $random->numberBetween(1, 10)]],
            'Random word => datetime'  => [[$random->word => $random->dateTime]],
            "$n × random word => word" => [combine($random->words($n), $random->words($n))],
        ];
    }

    private function deserializer(): Deserializes
    {
        $deserializer = $this->createMock(Deserializes::class);
        assert($deserializer instanceof Deserializes);
        return $deserializer;
    }
}
