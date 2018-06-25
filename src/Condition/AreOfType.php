<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use function gettype;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

final class AreOfType implements Specifies
{
    use Specifying;

    private $expectation;

    private function __construct(string $expectation)
    {
        $this->expectation = $expectation;
    }

    public static function boolean(): Specifies
    {
        return new AreOfType('boolean');
    }

    public static function integer(): Specifies
    {
        return new AreOfType('integer');
    }

    public static function string(): Specifies
    {
        return new AreOfType('string');
    }

    public function isSatisfiedBy($input): bool
    {
        return gettype($input) === $this->expectation;
    }
}
