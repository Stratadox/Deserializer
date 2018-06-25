# Deserializer

Transforms serialized data into an object graph.

## Installation

Install with `composer require stratadox/deserializer`

## Basic Usage

```php
<?php
use Stratadox\Deserializer\ObjectDeserializer;

$deserialize = ObjectDeserializer::forThe(Foo::class);
$foo = $deserialize->from([
    'bar' => 'Bar.',
    'baz' => 'BAZ!',
]);
assert($foo instanceof Foo);
assert('Bar.' === $foo->bar);
assert('BAZ!' === $foo->getBaz());
```
