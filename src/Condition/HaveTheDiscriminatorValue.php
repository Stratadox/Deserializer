<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

final class HaveTheDiscriminatorValue implements Specifies
{
    use Specifying;

    private $key;
    private $value;

    private function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public static function of(string $key, string $value): Specifies
    {
        return new self($key, $value);
    }

    public function isSatisfiedBy($input): bool
    {
        return isset($input[$this->key])
            && (string) $input[$this->key] === $this->value;
    }
}
