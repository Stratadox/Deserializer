<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Condition;

use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

/**
 * Condition that accepts data with a specific discriminator key and value.
 *
 * Particularly useful when deserializing relational data into polymorphic
 * object structures.
 *
 * @author Stratadox
 */
final class HaveTheDiscriminatorValue implements Specifies
{
    use Specifying;

    /** @var string */
    private $key;
    /** @var string */
    private $value;

    private function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Produces a condition that checks a discriminator key/value combination.
     *
     * @param string $key   The discriminator key, for instance a column name.
     * @param string $value The discriminator value to be triggered by.
     * @return Specifies    The discriminating condition.
     */
    public static function of(string $key, string $value): Specifies
    {
        return new self($key, $value);
    }

    /** @inheritdoc */
    public function isSatisfiedBy($input): bool
    {
        return isset($input[$this->key])
            && (string) $input[$this->key] === $this->value;
    }
}
