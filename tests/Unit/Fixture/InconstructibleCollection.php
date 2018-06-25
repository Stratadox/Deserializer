<?php
declare(strict_types=1);

namespace Stratadox\Deserializer\Test\Unit\Fixture;

use BadMethodCallException;
use function implode;
use function sprintf;
use Stratadox\ImmutableCollection\ImmutableCollection;

final class InconstructibleCollection extends ImmutableCollection
{
    public function __construct(string ...$items)
    {
        parent::__construct();
        throw new BadMethodCallException(sprintf(
            'Cannot construct (%s)',
            implode(', ', $items)
        ));
    }
}
