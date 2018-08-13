<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Condition;

use Faker\Factory as RandomGenerator;
use PHPUnit\Framework\TestCase;
use function random_int;
use Stratadox\Deserializer\Condition\DidNotGetAccepted;

/**
 * @covers \Stratadox\Deserializer\Condition\DidNotGetAccepted
 */
class DidNotGetAccepted_takes_in_all_leftovers extends TestCase
{
    /**
     * @test
     * @dataProvider randomData
     */
    function accepting_all_input($input)
    {
        $this->assertTrue(DidNotGetAccepted::yet()->isSatisfiedBy($input));
    }

    public function randomData(): array
    {
        $random = RandomGenerator::create();
        return [
            'Random word' => [$random->word],
            'Random sentence' => [$random->sentence],
            'Random sentences' => [$random->sentences(random_int(1, 20))],
            'Random [key => value]' => [[$random->word => $random->word]],
        ];
    }
}
