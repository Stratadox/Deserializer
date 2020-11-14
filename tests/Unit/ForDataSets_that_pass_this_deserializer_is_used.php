<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit;

use Stratadox\Deserializer\Deserializer;
use function array_combine as combine;
use function assert;
use Faker\Factory as RandomGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\Test\Unit\Fixture\AreDenied;
use Stratadox\Deserializer\Test\Unit\Fixture\Popo;
use Stratadox\Deserializer\Test\Unit\Fixture\AreAccepted;

class ForDataSets_that_pass_this_deserializer_is_used extends TestCase
{
    /** @var Deserializer */
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
        self::assertTrue(
            ForDataSets::that(AreAccepted::byDefault(), $this->deserializer)->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function checking_that_the_condition_fails(array $input)
    {
        self::assertFalse(
            ForDataSets::that(AreDenied::byDefault(), $this->deserializer)->isSatisfiedBy($input)
        );
    }

    /**
     * @test
     * @dataProvider keyValueArray
     */
    function deferring_deserialization_to_the_deserializer_in_question(array $input)
    {
        /** @var Deserializer|MockObject $deserialize */
        $deserialize = $this->createMock(Deserializer::class);
        $deserialize
            ->expects(self::once())
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
        /** @var Deserializer|MockObject $deserialize */
        $deserialize = $this->createMock(Deserializer::class);
        $deserialize
            ->expects(self::once())
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

    private function deserializer(): Deserializer
    {
        $deserializer = $this->createMock(Deserializer::class);
        assert($deserializer instanceof Deserializer);
        return $deserializer;
    }
}
